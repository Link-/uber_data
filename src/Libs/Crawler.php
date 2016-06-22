<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

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
     * [$_uberUsername description]
     *
     * @var string
     */
    protected $_uberUsername = '';

    /**
     * [$_uberPassword description]
     *
     * @var string
     */
    protected $_uberPassword = '';

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->_curlHandle = curl_init();
        $this->_parser = new Parser();
        // Set the credentials
        $this->setUsername(App::$APP_SETTINGS['username']);
        $this->setPassword(App::$APP_SETTINGS['password']);
        // Set the URLs
        $this->setLoginURL(App::$APP_SETTINGS['uber_login_url']);
        $this->setTripsURL(App::$APP_SETTINGS['uber_trips_url']);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        curl_close($this->_curlHandle);
    }

    /**
     * Getter method for the _csrf_token attribute
     *
     * @return string Value of the CSRF token
     */
    public function getCSRFToken()
    {
        return $this->_csrf_token;
    }

    /**
     * Setter method for the _csrf_token attribute
     *
     * @param string $token Developer defined token
     */
    public function setCSRFToken($token)
    {
        if (empty($token)) {
            throw new GeneralException(
                'CSRF Token cannot be empty!',
                'FATAL'
            );
        }

        $this->_csrf_token = $token;
    }

    /**
     * Getter method for the Parser instance and attribute
     * of the Crawler
     *
     * @codeCoverageIgnore
     *
     * @return Parser Parser Instance
     */
    public function getParser()
    {
        return $this->_parser;
    }

    /**
     * Getter method for the Uber Login URL
     * 
     * @return string Login URL
     */
    public function getLoginURL()
    {
        return $this->_uberLoginURL;
    }

    /**
     * Setter method for the Uber Login URL
     * this method identifies empty args and invalid URLs
     * provided as arguments
     *
     * @param string $url Uber's Login URL
     */
    public function setLoginURL($url)
    {
        if (empty($url)) {
            throw new GeneralException(
                'Login URL cannot be empty!',
                'FATAL'
            );
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new GeneralException(
                'Invalid Login URL configured. '.
                'Check your App.php config file.',
                'FATAL'
            );
        }

        $this->_uberLoginURL = $url;
    }

    /**
     * Setter method for the Uber Account attribute
     *
     * @codeCoverageIgnore
     *
     * @param string $username User's Uber account username
     */
    public function setUsername($username)
    {
        $this->_uberUsername = $username;
    }

    /**
     * Setter method for the Uber Password attribute
     *
     * @codeCoverageIgnore
     *
     * @param string $password Plain text user's Uber account password
     */
    public function setPassword($password)
    {
        $this->_uberPassword = $password;
    }

    /**
     * Getter method for the Uber Trips URL attribute
     * This is the first URL after the Login page
     *
     * @codeCoverageIgnore
     *
     * @return string Trips URL
     */
    public function getTripsURL()
    {
        return $this->_uberTripsURL;
    }

    /**
     * Setter method for the Uber Trips URL attribute
     * 
     * @param string $url Uber Trips URL
     */
    public function setTripsURL($url)
    {
        if (empty($url)) {
            throw new GeneralException(
                'Trips URL cannot be empty!',
                'FATAL'
            );
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new GeneralException(
                'Invalid Trips URL configured. '.
                'Check your App.php config file.',
                'FATAL'
            );
        }

        $this->_uberTripsURL = $url;
    }

    /**
     * Getter method for the TripsCollection instance
     *
     * @return TripsCollection Contains all the parsed TripDetails
     */
    public function getTripsCollection()
    {
        return $this->_parser->getTripsCollection();
    }

    /**
     * Main method that kickstars the entire crawling and
     * parsing process. First the Login Form is retrieved, the CSRF token
     * is parsed, then the crawler executes the authentication and starts
     * scrapping the data
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
     * @return void
     */
    protected function setCurlOptions(
        $post = false,
        $postFields = '',
        $headers = [],
        $url = '',
        $autoref = true,
        $returnTrans = true
    ) {

        /*
        * If URL is specified use it, else
        * use the default login url
        */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_URL,
            empty($url) ? $this->_uberLoginURL : $url
        );
        /*
         * Request Timeout
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_TIMEOUT,
            App::$APP_SETTINGS['curl_timeout']
        );
        /*
         * Session Data Storage File
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_COOKIEJAR,
            App::$APP_SETTINGS['cookies_storage_file']
        );
        /*
         * Session Data Storage File
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_COOKIEFILE,
            App::$APP_SETTINGS['cookies_storage_file']
        );

        /*
         * TBD
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_AUTOREFERER,
            $autoref
        );

        /*
         * TBD
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_FOLLOWLOCATION,
            true
        );

        /*
         * 
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_RETURNTRANSFER,
            $returnTrans
        );

        /*
         * 
         */
        curl_setopt(
            $this->_curlHandle,
            CURLOPT_USERAGENT,
            App::$APP_SETTINGS['user_agent']
        );

        /*
         * POST Request
         */
        if ($post) {
            curl_setopt(
                $this->_curlHandle,
                CURLOPT_HTTPHEADER,
                $headers
            );

            curl_setopt(
                $this->_curlHandle,
                CURLOPT_POST,
                $post
            );

            curl_setopt(
                $this->_curlHandle,
                CURLOPT_POSTFIELDS,
                $postFields
            );
        }
    }

    /**
     * Makes an HTTP request to the LoginURL, retrieves the HTML
     * content and calls the parseCSRFToken method to parse the content
     * and retrieve the CSRF token
     *
     * @codeCoverageIgnore
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
            $this->_csrf_token = $this->parseCSRFToken($rawFormData);
            // Check that we have successfully retrieved the
            // token
            if (empty($this->_csrf_token)) {
                throw new GeneralException(
                    'Grabbing CSRF Token Failed - Empty',
                    'FATAL'
                );
            }
            Helper::printOut("CSRF TOKEN: {$this->_csrf_token}");
        } else {
            // Failed to retrieve CSRF Token
            $errorMessage = curl_error($this->_curlHandle);
            throw new GeneralException(
                'Grabbing CSRF Token Failed',
                'FATAL'
            );
        }

        // If we get here, the CSRF token
        // was retrieved
        return $this->_csrf_token;
    }

    /**
     * Parses the Login Page's HTML content and retrieves the
     * CSRF token
     *
     * @param string $formData HTML content of the login page
     *
     * @return string CSRF Token Value or an empty string
     */
    protected function parseCSRFToken($formData)
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
     * @codeCoverageIgnore
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
        $this->setCurlOptions(
            true,
            $loginString,
            $headers
        );

        // Execute the Request
        $postLoginRawData = curl_exec($this->_curlHandle);
        // Check if there were any errors in the process
        if (!curl_errno($this->_curlHandle)) {
            Helper::printOut('Retrieved Data');
            // Store the HTML content into a file
            // for caching purposes
            Helper::storeIntoFile($postLoginRawData, 1);
            // Parse the retrieved page
            $this->_parser->parsePage($postLoginRawData);
            // Parsed the next page if it's available
            $this->getNextPagesData();
        } else {
            // Failed to Login
            $errorMessage = curl_error($this->_curlHandle);
            throw new GeneralException(
                'Login Attempt Failed! '.
                $errorMessage,
                'FATAL'
            );
        }
    }

    /**
     * Parses the HTML content of the page, identifies the pagination
     * and checks whether there exists further pages to parse or not.
     * Once complete, the entire trips collection parsed from the data tables
     * is returned in a single TripsCollection instance
     *
     * @codeCoverageIgnore
     *
     * @return TripsCollection Returns the entire TripsCollection
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
            $this->setCurlOptions(
                false,
                '',
                [],
                $pageUrl
            );

            // Execute the Request
            $pageRawData = curl_exec($this->_curlHandle);
            // Check if there were any errors in the process
            if (!curl_errno($this->_curlHandle)) {
                Helper::printOut('Retrieved Data');
                // Store the HTML content into a file
                // for caching purposes
                Helper::storeIntoFile($pageRawData, $i);
                // Parse the retrieved page
                $this->_parser->parsePage($pageRawData);
            } else {
                // Failed to Retrieve all pages
                $errorMessage = curl_error($this->_curlHandle);
                throw new GeneralException(
                    'Failed to retrieve all pages! '.
                    $errorMessage,
                    'FATAL'
                );
            }
        }

        return $this->_parser->getTripsCollection();
    }

    /**
     * Generate URL-encoded query string
     *
     * @return string URL-encoded string
     */
    protected function getLoginString()
    {
        return http_build_query(['_csrf_token' => $this->_csrf_token,
                                'access_token' => '',
                                'email' => $this->_uberUsername,
                                'password' => $this->_uberPassword,
                                ]);
    }
}
