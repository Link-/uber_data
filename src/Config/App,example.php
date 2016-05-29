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
		 * Storage Location for Session Data
		 */
		'cookies_storage_file' => '/tmp/uber-cookies',
		
		/**
		 * Storage Location of Crawled Pages
		 * Data
		 */
		'data_storage_dir' => '/tmp/ubder-data',
		
		/**
		 * User Agent
		 */
		'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) '. 
									'AppleWebKit/601.5.17 (KHTML, like Gecko) Version/9.1 '.
									'Safari/601.5.17',

		/**
		 * Curl Request Timeout in seconds
		 */
		'curl_timeout' => 15

	];

}