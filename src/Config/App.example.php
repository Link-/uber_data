<?php

namespace UberCrawler\Config;

class App
{
  
    public static $APP_SETTINGS = [

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
         * This is the number of trips list per page
         * by default it is 20, but if more is listed
         * make sure to change this number of your will
         * get incomplete results
         */
        'trips_per_page' => 20,

        /**
         * Storage Location for Session Data
         */
        'cookies_storage_file' => '/tmp/uber-cookies',

        /**
         * Storage Location of Crawled Pages Data
         * If the folder doesn't exist it will
         * be created. It's best to store this information
         * in your /tmp folder as it will be removed when
         * the OS reboots
         */
        'data_storage_dir' => '/tmp/uber-data',

        /**
         * Parsed Data Storage Location
         * --
         * If the folder doesn't exist it will
         * be created. It's best to store this information
         * in your /tmp folder as it will be removed when
         * the OS reboots
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
         * Timezone -- It's very important for the date
         * handling and if set incorrectly, it might lead
         * to wrong information generation
         */
        'timezone' => 'Asia/Beirut'

    ];
}
