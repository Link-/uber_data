<?php namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

/**
 * 
 */
class Crawler {
  /**
   * [$_curlHandle description]
   *
   * @var [type]
   */
  protected $_curlHandle;
  /**
   * [$_csrf_token description]
   *
   * @var string
   */
  protected $_csrf_token = '';


  /**
   * [__construct description]
   */
  public function __construct() {

    $this->_curlHandle = curl_init();

  }


  /**
   * [__destruct description]
   */
  public function __destruct() {

    curl_close($this->_curlHandle);

  }


  public function execute() {

    // Grab the Login form and CSRF token
    $this->grabLoginForm();
    // Attempt to Login now
    $this->getData();

  }


  /**
   * [curlSetOptions description]
   *
   * @return [type] [description]
   */
  protected function setCurlOptions($post = False,
                                    $postFields = '',
                                    $headers = [],
                                    $url = '', 
                                    $autoref = True, 
                                    $returnTrans = True) {

   /**
    * If URL is specified use it, else
    * use the default login url
    */
    curl_setopt($this->_curlHandle, 
                CURLOPT_URL, 
                empty($url) ? App::$APP_SETTINGS['uber_login_url'] : $url);

    /**
     * Request Timeout
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_TIMEOUT, 
                App::$APP_SETTINGS['curl_timeout']);
    /**
     * Session Data Storage File
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_COOKIEJAR, 
                App::$APP_SETTINGS['cookies_storage_file']);
    /**
     * Session Data Storage File
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_COOKIEFILE, 
                App::$APP_SETTINGS['cookies_storage_file']);

    /**
     * TBD
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_AUTOREFERER, 
                $autoref);

    /**
     * TBD
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_FOLLOWLOCATION,
                True);

    /**
     * 
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_RETURNTRANSFER, 
                $returnTrans);

    /**
     * 
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_USERAGENT, 
                App::$APP_SETTINGS['user_agent']);

    /**
     * 
     */
    curl_setopt($this->_curlHandle, 
                CURLOPT_HTTPHEADER, 
                $headers);

    /**
     * POST Request
     */
    if ($post) {
      curl_setopt($this->_curlHandle,
                  CURLOPT_POST, 
                  $post);

      curl_setopt($this->_curlHandle, 
                  CURLOPT_POSTFIELDS, 
                  $postFields);
    }

  }


  /**
   * [grabLoginForm description]
   *
   * @return [type] [description]
   */
  protected function grabLoginForm() {

    // Set the Request Options
    $this->setCurlOptions();
    // Retrieve the form data
    $rawFormData = curl_exec($this->_curlHandle);

    if (!curl_errno($this->_curlHandle)) {

      // Printout informative messages
      $this->printOut("Retrieving CSRF Token");
      // Retrieve the csrf token
      $this->_csrf_token = $this->getCSRFToken($rawFormData);
      // Check that we have successfully retrieved the
      // token
      if (empty($this->_csrf_token)) {
        throw new GeneralException("Grabbing CSRF Token Failed", 
                                   "FATAL");
      }
      $this->printOut("CSRF TOKEN: {$this->_csrf_token}");

    } else {

      // Failed to retrieve CSRF Token
      $errorMessage = curl_error($this->_curlHandle);
      throw new GeneralException("Grabbing CSRF Token Failed", 
                                 "FATAL");

    }

  }


  /**
   * [getCSRFToken description]
   *
   * @param [type] $formData [description]
   *
   * @return [type] [description]
   */
  protected function getCSRFToken($formData) {

    $dom = new \DOMDocument;
    $dom->loadHTML($formData);
    $inputElements = $dom->getElementsByTagName('input');

    foreach ($inputElements as $elem) {
      if ($elem->hasAttributes()) {
        $attrName = $elem->getAttribute('name');
        $value = $elem->getAttribute('value');

        if ($attrName == '_csrf_token')
          return $value;
      }
    }

    return '';

  }


  /**
   * [getData description]
   *
   * @return [type] [description]
   */
  protected function getData() {

    // Login String
    $loginString = $this->getLoginString();
    // Set Header information (Form POST Request)
    $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
    // Set cURL Options
    $this->setCurlOptions(True, 
                          $loginString,
                          $headers);
    // Execute the Request
    $postLoginRawData = curl_exec($this->_curlHandle);
    // Check if there were any errors in the process
    if (!curl_errno($this->_curlHandle)) {

      $this->printOut("Retrieved Data");
      $this->storeIntoFile($postLoginRawData, 1);

    } else {

      // Failed to Login
      $errorMessage = curl_error($this->_curlHandle);
      throw new GeneralException("Login Attempt Failed!", 
                                 "FATAL");

    }

  }


  /**
   * [storeIntoFile description]
   *
   * @param [type]  $data     [description]
   * @param integer $pageNumb [description]
   *
   * @return [type]  [description]
   */
  protected function storeIntoFile($data, 
                                   $pageNumb = 1) {

    // Get the appropriate filename
    $fileName = $this->buildStorageFilePath($pageNumb);
    // Build the necessary dirs if they
    // don't exist already
    $this->makedirs(App::$APP_SETTINGS['data_storage_dir']);
    // Store data into a file
    file_put_contents($fileName, $data);

  }


  /**
   * [makedirs description]
   *
   * @param [type]  $dirpath [description]
   * @param integer $mode    [description]
   *
   * @return [type]  [description]
   */
  protected function makedirs($dirpath, 
                              $mode=0777) {

    return is_dir($dirpath) || mkdir($dirpath, $mode, true);

  }


  /**
   * [buildStorageFilePath description]
   *
   * @param [type] $pageNumb [description]
   *
   * @return [type] [description]
   */
  protected function buildStorageFilePath($pageNumb) {

    // Build the filename
    $fileName = "data-page_{$pageNumb}.html";
    return join("/", [App::$APP_SETTINGS['data_storage_dir'],
               $fileName
       ]);

  }


  /**
   * [getLoginString description]
   *
   * @return [type] [description]
   */
  protected function getLoginString() {

    return http_build_query(['_csrf_token'  => $this->_csrf_token,
                             'access_token' => '',
                             'email'        => App::$APP_SETTINGS['username'],
                             'password'     => App::$APP_SETTINGS['password']
                            ]);

  }


  /**
   * [printOut description]
   *
   * @param [type] $message [description]
   * @param string $type    [description]
   *
   * @return [type] [description]
   */
  protected function printOut($message, $type = 'INFO') {

    echo "{$type}::: {$message} \n";

  }

}