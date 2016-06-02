<?php namespace UberCrawler\Config;

class App {
  
  public static $settings = [
    
    /**
     * Uber Account Username
     */
    'username' => '',
    
    /**
     * Uber Account Password
     */
    'password' => '',
    
    /**
     * Uber Login URL
     */
    'uber_login_url' => 'https://login.uber.com/login',

    /**
     * Trips URL
     */
    'uber_trips_url' => 'https://riders.uber.com/trips',
    
    /**
     * Storage Location for Session Data
     */
    'cookies_storage_file' => '/tmp/uber-cookies',
    
    /**
     * Storage Location of Crawled Pages
     * Data
     * -- 
     * If the folder doesn't exist it will
     * be created
     */
    'data_storage_dir' => '/tmp/uber-data',

    /**
     * Parsed Data Storage Location
     * --
     * If the folder doesn't exist it will
     * be created
     */
    'parsed_data_dir' => '/tmp/uber-parsed',
    
    /**
     * User Agent
     */
    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) '. 
                  'AppleWebKit/601.5.17 (KHTML, like Gecko) Version/9.1 '.
                  'Safari/601.5.17',

    /**
     * Curl Request Timeout in seconds
     *
     * Increase the time for slower internet connections
     */
    'curl_timeout' => 120,

    /**
     * !! Important !!
     * 
     * Timezone -- It's very important for the date
     * handling and if set incorrectly, it might lead 
     * to wrong information generation
     */
    'timezone' => 'Asia/Beirut'

  ];

}