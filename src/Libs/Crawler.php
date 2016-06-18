<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

/**
 * 
 */
class Crawler
{
    /**
     * [$_curlHandle description].
     *
     * @var [type]
     */
    protected $_curlHandle;
    /**
     * [$_csrf_token description].
     *
     * @var string
     */
    protected $_csrf_token = '';
    /**
     * Parser Instance.
     *
     * @var [type]
     */
    protected $_parser;
    /**
     * [$_uberLoginURL description].
     *
     * @var string
     */
    protected $_uberLoginURL = '';
    /**
     * [$_uberTripsURL description].
     *
     * @var string
     */
    protected $_uberTripsURL = '';

    /**
     * [__construct description].
     */
    public function __construct()
    {
        $this->_curlHandle = curl_init();
        $this->_parser = new Parser();
        // Set the URLs
        $this->setLoginURL(App::$APP_SETTINGS['uber_login_url']);
        $this->setTripsURL(App::$APP_SETTINGS['uber_trips_url']);
    }

    /**
     * [__destruct description].
     */
    public function __destruct()
    {
        curl_close($this->_curlHandle);
    }

    /**
     * [getLoginURL description].
     *
     * @return [type] [description]
     */
    public function getLoginURL()
    {
        return $this->_uberLoginURL;
    }

    /**
     * [setLoginURL description].
     *
     * @param [type] $url [description]
     */
    public function setLoginURL($url)
    {
        if (empty($url)) {
            throw new GeneralException('Login URL cannot be empty!',
                                 'FATAL');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new GeneralException('Invalid Login URL configured. '.
                                 'Check your App.php config file.',
                                 'FATAL');
        }

        $this->_uberLoginURL = $url;
    }

    /**
     * [getTripsURL description].
     *
     * @return [type] [description]
     */
    public function getTripsURL()
    {
        return $this->_uberTripsURL;
    }

    /**
     * [setTripsURL description].
     *
     * @param [type] $url [description]
     */
    public function setTripsURL($url)
    {
        if (empty($url)) {
            throw new GeneralException('Trips URL cannot be empty!',
                                 'FATAL');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new GeneralException('Invalid Trips URL configured. '.
                                 'Check your App.php config file.',
                                 'FATAL');
        }

        $this->_uberTripsURL = $url;
    }

    /**
     * [getParser description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function getParser()
    {
        return $this->_parser;
    }

    /**
     * [getTripsCollection description].
     * 
     * @return [type] [description]
     */
    public function getTripsCollection()
    {
        return $this->_parser->getTripsCollection();
    }

    /** 
     * [execute description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function execute()
    {
        // Grab the Login form and CSRF token
        $this->grabLoginForm();
        // Attempt to Login now
        $this->getData();
    }

    /**
     * [curlSetOptions description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    protected function setCurlOptions($post = false,
                                    $postFields = '',
                                    $headers = [],
                                    $url = '',
                                    $autoref = true,
                                    $returnTrans = true)
    {

        /*
        * If URL is specified use it, else
        * use the default login url
        */
        curl_setopt($this->_curlHandle,
                    CURLOPT_URL,
                    empty($url) ? $this->_uberLoginURL : $url);

        /*
         * Request Timeout
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_TIMEOUT,
                    App::$APP_SETTINGS['curl_timeout']);
        /*
         * Session Data Storage File
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_COOKIEJAR,
                    App::$APP_SETTINGS['cookies_storage_file']);
        /*
         * Session Data Storage File
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_COOKIEFILE,
                    App::$APP_SETTINGS['cookies_storage_file']);

        /*
         * TBD
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_AUTOREFERER,
                    $autoref);

        /*
         * TBD
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_FOLLOWLOCATION,
                    true);

        /*
         * 
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_RETURNTRANSFER,
                    $returnTrans);

        /*
         * 
         */
        curl_setopt($this->_curlHandle,
                    CURLOPT_USERAGENT,
                    App::$APP_SETTINGS['user_agent']);

        /*
         * POST Request
         */
        if ($post) {
            curl_setopt($this->_curlHandle,
                    CURLOPT_HTTPHEADER,
                    $headers);

            curl_setopt($this->_curlHandle,
                      CURLOPT_POST,
                      $post);

            curl_setopt($this->_curlHandle,
                      CURLOPT_POSTFIELDS,
                      $postFields);
        }
    }

    /**
     * [grabLoginForm description].
     *
     * @return [type] [description]
     */
    protected function grabLoginForm()
    {
        // Set the Request Options
        $this->setCurlOptions();
        // Retrieve the form data
        $rawFormData = curl_exec($this->_curlHandle);

        if (!curl_errno($this->_curlHandle)) {
            // Printout informative messages
            Helper::printOut('Retrieving CSRF Token');
            // Retrieve the csrf token
            $this->_csrf_token = $this->getCSRFToken($rawFormData);
            // Check that we have successfully retrieved the
            // token
            if (empty($this->_csrf_token)) {
                throw new GeneralException('Grabbing CSRF Token Failed - Empty',
                                       'FATAL');
            }

            Helper::printOut("CSRF TOKEN: {$this->_csrf_token}");
        } else {
            // Failed to retrieve CSRF Token
            $errorMessage = curl_error($this->_curlHandle);
            throw new GeneralException('Grabbing CSRF Token Failed',
                                     'FATAL');
        }

        // If we get here, the CSRF token
        // was retrieved
        return $this->_csrf_token;
    }

    /**
     * [getCSRFToken description].
     *
     * @param [type] $formData [description]
     *
     * @return [type] [description]
     */
    protected function getCSRFToken($formData)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($formData);
        $inputElements = $dom->getElementsByTagName('input');

        foreach ($inputElements as $elem) {
            if ($elem->hasAttributes()) {
                $attrName = $elem->getAttribute('name');
                $value = $elem->getAttribute('value');

                if ($attrName == '_csrf_token') {
                    return $value;
                }
            }
        }

        return '';
    }

    /**
     * [getData description].
     *
     * @return [type] [description]
     */
    protected function getData()
    {
        // Login String
        $loginString = $this->getLoginString();

        // Set Header information (Form POST Request)
        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
        // Set cURL Options
        $this->setCurlOptions(true,
                              $loginString,
                              $headers);

        // Execute the Request
        $postLoginRawData = curl_exec($this->_curlHandle);
        // Check if there were any errors in the process
        if (!curl_errno($this->_curlHandle)) {
            Helper::printOut('Retrieved Data');
            $this->storeIntoFile($postLoginRawData, 1);

            // Parse the retrieved page
            $this->_parser->parsePage($postLoginRawData);
            // Parsed the next page if it's available
            $this->getNextPagesData();
        } else {
            // Failed to Login
          $errorMessage = curl_error($this->_curlHandle);
            throw new GeneralException('Login Attempt Failed! '.
                                       $errorMessage,
                                       'FATAL');
        }
    }

    /**
     * [getNextPagesData description].
     *
     * @return [type] [description]
     */
    protected function getNextPagesData()
    {
        $i = 1;
        while ($this->_parser->getNextPage()) {
            $nextPage = $this->_parser->getNextPage();
            ++$i;

            $pageUrl = $this->_uberTripsURL.$nextPage;

            Helper::printOut("Retrieving Page: {$i}");
            // Set cURL Options
            $this->setCurlOptions(false,
                                  '',
                                  [],
                                  $pageUrl);

            // Execute the Request
            $pageRawData = curl_exec($this->_curlHandle);
            // Check if there were any errors in the process
            if (!curl_errno($this->_curlHandle)) {
                Helper::printOut('Retrieved Data');
                $this->storeIntoFile($pageRawData, $i);

                // Parse the retrieved page
                $this->_parser->parsePage($pageRawData);
            } else {
                // Failed to Retrieve all pages
                $errorMessage = curl_error($this->_curlHandle);
                throw new GeneralException('Failed to retrieve all pages! '.
                                             $errorMessage,
                                             'FATAL');
            }
        }

        return $this->_parser->getTripsCollection();
    }

    /**
     * Stores the grabbed HTML pages onto the localdisk
     * for cashing purposes. The stored files are not being
     * used in this project yet. Might introduce this feature
     * in future releases.
     *
     * @param [type] $data     [description]
     * @param int    $pageNumb [description]
     *
     * @return [type] [description]
     */
    protected function storeIntoFile($data,
                                   $pageNumb = 1)
    {
        // Get the appropriate filename
        $fileName = $this->buildStorageFilePath($pageNumb);
        // Build the necessary dirs if they
        // don't exist already
        Helper::makedirs(App::$APP_SETTINGS['data_storage_dir']);
        // Store data into a file
        file_put_contents($fileName, $data);
    }

    /**
     * [buildStorageFilePath description].
     *
     * @param [type] $pageNumb [description]
     *
     * @return [type] [description]
     */
    protected function buildStorageFilePath($pageNumb)
    {
        // Build the filename
        $fileName = "data-page_{$pageNumb}.html";

        return implode(DIRECTORY_SEPARATOR,
                       [App::$APP_SETTINGS['data_storage_dir'],
                       $fileName]);
    }

    /**
     * [getLoginString description].
     * 
     * @return [type] [description]
     */
    protected function getLoginString()
    {
        return http_build_query(['_csrf_token' => $this->_csrf_token,
                                'access_token' => '',
                                'email' => App::$APP_SETTINGS['username'],
                                'password' => App::$APP_SETTINGS['password'],
                                ]);
    }
}
