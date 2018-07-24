<?php

/**
 * Sooprema API class
 *
 * @package Sooprema
 * @subpackage API-SDK
 */
class SoopremaAPI {



	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Agency slug
	 */
	private $agency;



	/**
	 * Public and Secret keys credentials
	 */
	private $publicKey;
	private $secretKey;



	/**
	 * API Base URL
	 */
	private $url;



	/**
	 * Default language
	 */
	private $language;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Constructor
	 */
	public function __construct($args) {

		// Mandatory
		$expected = [
			'agency' 	=> 'Missing `agency` slug param',
			'publicKey'	=> 'Missing `publicKey` credential param',
			'secretKey'	=> 'Missing `secretKey` credential param',
			'url'		=> 'Missing `url` endpoint param',
		];

		// Enum params
		foreach ($expected as $param => $message) {

			// Validate
			if (empty($args[$param])) {
				trigger_error($message, E_USER_ERROR);
				return;
			}

			// Set property
			$this->{$param} = $args[$param];
		}

		// Check language
		if (!empty($args['language']))
			$this->language = $args['language'];
	}



	// Methods
	// ---------------------------------------------------------------------------------------------------



	/**
	 * API endpoint request
	 */
	public function endpoint($endpoint, $args = []) {

		// Prepare URL
		$url = rtrim($this->url, '/').'/'.ltrim($endpoint, '/');

		// Current timestamp
		$timestamp = time();

		// Hash timestamp using the secret key
		$hash = hash_hmac('sha256', $timestamp, $this->secretKey);

		// Prepare headers
		$headerAuth  = 'Basic '.$this->publicKey.':'.$hash;
		$headerXTimestamp = $timestamp;

		// Set default args
		$this->default($args);

		// Prepare params
		$params = $this->params($args);

		// Open connection
		$ch = curl_init();

		// Disable SSL verification to avoid conflicts
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// Attempt to return the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Target URL
		curl_setopt($ch, CURLOPT_URL, $url);

		// Set headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: '.$headerAuth,
			'X-Timestamp: '.$headerXTimestamp,
		]);

		// Check params
		if (!empty($params)) {
			curl_setopt($ch, CURLOPT_POST, count($args));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

		// Execute request
		$json = curl_exec($ch);

		// Close connection
		curl_close($ch);

		// Decode JSON
		$data = @json_decode($json, true);

		// Done
		return $data;
	}



	/**
	 * Check endpoint response looking for errors
	 */
	public function isError(&$data) {

		// Check malformed response
		if (empty($data) || !is_array($data) || empty($data['response']) || empty($data['response']['status'])) {

			// Resulting value
			$value = print_r($data, true);

			// Overwrite
			$data = [
				'request' => (isset($data['request']) && is_array($data['request']))? $data['request'] : [],
				'response' => [
					'status' => 'error',
					'reason' => 'Malformed response: '.$value,
				],
			];

			// Error
			return true;

		// Valid response but server error
		} elseif ('ok' != $data['response']['status']) {

			// Check response reason
			if (empty($data['response']['reason'])) {
				$data['response']['reason'] = 'Unknown reason';
			}

			// Error
			return true;
		}

		// Valid
		return false;
	}



	// Internal
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Prepare default arguments
	 */
	private function default(&$args) {

		// Check args
		if (empty($args) || !is_array($args))
			$args = [];

		// Set agency slug
		$args['agency'] = $this->agency;

		// Check language
		if (!isset($args['language']) && isset($this->language))
			$args['language'] = $this->language;
	}



	/**
	 * Cast args to URL params
	 */
	private function params(&$args) {

		// Check values
		if (empty($args))
			return false;

		// Init
		$pairs = [];
		$params = '';

		// Collect pairs
		foreach($args as $name => $value) {
			$pairs[] = $name.'='.urlencode($value);
		}

		// Join
		$params = implode('&', $pairs);

		// Done
		return $params;
	}



}