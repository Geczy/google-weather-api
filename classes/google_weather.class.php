<?php

/**
 * Google Weather API.
 *
 * LICENSE:
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * You may not use this work except in compliance with the License.
 * You may obtain a copy of the License in the LICENSE file, or at:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * @author       Matthew Gates <info@mgates.me>
 * @copyright    Copyright (c) 2012 Matthew Gates.
 * @license      http://www.apache.org/licenses/LICENSE-2.0
 * @link         https://github.com/Geczy/google-weather-api
 */

class Google_Weather  {

	/**
	 * Default settings.
	 */
	public $defaults = array(
		'location' => 'Los Angeles',
		'degree'   => 'f',
		'language' => 'en',
		'icons'    => 'Dotvoid'
	);

	/**
	 * Location of where to check the weather. Written by setLocation().
	 */
	private $location;

	/**
	 * Whether there was an issue retrieving the weather.
	 */
	private $error = false;

	/**
	 * Google Weather constructor.
	 *
	 * @param     array    $defaults    Override the default options by providing your own.
	 */
	function __construct( $defaults = array() ) {

		if ( empty($_SESSION) ) session_start();

		$this->defaultSettings($defaults);
		$this->formRequests();

		$this->displayError($this->error);

	}

	/**
	 *
	 * Default settings.
	 *
	 * @param     array    $defaults    Default options overridden by __construct()
	 */
	private function defaultSettings($defaults) {

		if ( !empty($defaults) ) $this->defaults = array_merge($this->defaults, $defaults);

		$this->setIcons($this->defaults['icons']);
		$this->setLanguage($this->defaults['language']);
		$this->setLocation($this->defaults['location']);

		$degree = !empty($_SESSION['degree']) ? $_SESSION['degree'] : $this->defaults['degree'];
		$this->setDegree($degree);

	}

	/**
	 * Swap the defaults with those of a form request.
	 */
	private function formRequests() {

		/* GET request to display Celsius or Fahrenheit. */
		if ( !empty($_GET['degree']) ) $this->setDegree( $_GET['degree'] );

		/* Form location submission. */
		if ( !empty($_POST['location']) ) $this->getWeather( $_POST['location'] );

	}

	/**
	 * Location of where to check the weather.
	 *
	 * @param    string    $location    This can be either zip, city, coordinates, etc.
	 */
	public function setLocation($location) {

		$this->location = $location;

	}

	/**
	 * Location of where to check the weather.
	 *
	 * @param    string    $language    For example: en, fr, pl, zn-CH.
	 */
	public function setLanguage($language) {

		$this->language = $language;

	}

	/**
	 * Icons for displaying weather images.
	 *
	 * Possible icon sets include:
	 *  Google
	 *  Dotvoid
	 *  KWeather
	 *  NYTimes
	 *  Yahoo
	 *
	 * @param    string    $iconSet    The folder name containing your icons in `/assets/img/*`.
	 */
	public function setIcons($iconSet) {

		$this->iconPath =
			(!empty($_SERVER['https']) ? 'https://' : 'http://')
			. $_SERVER['HTTP_HOST']
			. dirname(str_replace('classes', '', $_SERVER['SCRIPT_NAME']))
			. '/assets/img/'
			. $iconSet;

	}

	/**
	 * Default degree to display weather in.
	 *
	 * @param    string    $degree    Only `f` (fahrenheit) or `c` (celsius) are accepted.
	 */
	public function setDegree($degree = 'f') {

		switch ( $degree ) :

			case 'f':
			case 'c':
				$_SESSION['degree'] = $degree;
				$this->defaults['degree'] = $degree;
				break;

			default :
				$_SESSION['degree'] = 'f';
				$this->defaults['degree'] = 'f';
				break;

			endswitch;

	}

	/**
	 * Process and retrieve weather information.
	 *
	 * Entering a location parameter will override the default location.
	 * Leaving it empty will retrieve the default location.
	 *
	 * @param    string     $location    This can be either zip, city, coordinates, etc. (Optional)
	 */
	public function getWeather($location = '') {

		if ( !empty($location) ) $this->setLocation($location);

		/* Validate submission data. */
		$this->validate();

		$query = $this->buildRequest();
		$result = $this->sendRequest($query);

		$validated = $this->validateResponse($result);
		$processed = $this->processResponse($validated);

		if ( !empty($this->error) ) return false;

		return $processed;

	}

	/**
	 * Confirms there is a location set.
	 */
	private function validate() {

		if ( empty( $this->location ) )
			$this->error = 'You didn\'t even enter anything! &gt;_&gt;';

	}

	/**
	 * Build the Google HTTP query.
	 *
	 * An HTTP query should follow the format:
	 * http://www.google.com/ig/api?weather=Los%2BAngeles&hl=en&ie=utf-8&oe=utf-8
	 *
	 * @return    string    A complete HTTP query containing location to retrieve.
	 */
	private function buildRequest() {

		$url = 'http://www.google.com/ig/api?';

		$args = array(
			'weather' => urlencode(trim($this->location)),
			'hl'      => $this->language,
			'ie'      => 'utf-8',
			'oe'      => 'utf-8',
		);

		$query = $url . http_build_query($args);

		return $query;

	}

	/**
	 * Load the HTTP query using SimpleXML.
	 *
	 * @param     string    $query    URL to retrieve weather from. See buildRequest().
	 * @return    object    The XML response from Google.
	 */
	private function sendRequest($query) {

		$xml = simplexml_load_file($query);

		return $xml;

	}

	/**
	 * Check whether the location is valid, and if a response is given.
	 *
	 * @param     object    $response    XML response from Google. See sendRequest().
	 * @return    array     Location info, current weather, and future forecast in the form of an object.
	 */
	private function validateResponse($response) {

		/* Save the bits that we actually use from the response. */
		$response = array(
			'info'     => $response->xpath("/xml_api_reply/weather/forecast_information"),
			'current'  => $response->xpath("/xml_api_reply/weather/current_conditions"),
			'forecast' => $response->xpath("/xml_api_reply/weather/forecast_conditions")
		);

		/* Remove empty results */
		$response = array_filter( $response );

		if ( empty($response) ) {

			/* Set the location back to default. */
			$this->setLocation( $this->defaults['location'] );
			$this->error = 'Location could not be determined.';

		}

		return $response;

	}

	/**
	 * Format the response from Google into a nice array.
	 *
	 * See README.md for an example of what this function returns.
	 *
	 * @param     array    $response    XML response, broken into an array of objects. See validateResponse().
	 * @return    array    Weather in a pretty array format.
	 */
	private function processResponse($response) {

		if ( !empty($this->error) ) return false;

		/* City information. */
		$info = array(
			'city' => (string) $response['info'][0]->city['data'],
			'zip'  => (string) $response['info'][0]->postal_code['data'],
			'unit' => (string) $response['info'][0]->unit_system['data'],
		);

		/* Current weather. */
		$current = array(
			'condition'      => (string) $response['current'][0]->condition['data'],
			'temp_f'         => $this->convertDegree((string) $response['current'][0]->temp_f['data']),
			'humidity'       => (string) $response['current'][0]->humidity['data'],
			'icon'           => $this->iconPath . str_replace('ig/images/weather/', '', (string) $response['current'][0]->icon['data']),
			'wind_condition' => (string) $response['current'][0]->wind_condition['data'],
		);

		/* Future weather. */
		$forecasts = array();
		foreach ( $response['forecast'] as $forecast ) {

			$forecasts[ (string) $forecast->day_of_week['data'] ] = array(
				'low'       => $this->convertDegree((string) $forecast->low['data'], $info['unit']),
				'high'      => $this->convertDegree((string) $forecast->high['data'], $info['unit']),
				'icon'      => $this->iconPath .  str_replace('ig/images/weather/', '', (string) $forecast->icon['data']),
				'condition' => (string) $forecast->condition['data'],
			);

		}

		$weather = array(
			'info'     => $info,
			'current'  => $current,
			'forecast' => $forecasts
		);

		return $weather;

	}

	/**
	 * Convert from Celsius to Fahrenheit or visa versa.
	 *
	 * @param     int       $degree    Temperature in degrees.
	 * @param     string    $degree    Either `US` or `SI`. Used to determine whether $degree is in Celsius or Fahrenheit.
	 * @return    int       Converted degree.
	 */
	private function convertDegree($degree, $unit = 'US') {

		switch ( $this->defaults['degree'] ) :

			case 'c' :
				if ( $unit == 'US' )
					$degree = round((5/9) * ($degree - 32));
				break;

			case 'f' :
				if ( $unit != 'US' )
					$degree = round($degree * 9/5 + 32);
				break;

		endswitch;

		return $degree;

	}

	/**
	 * Echo a message.
	 *
	 * @param    string    $message    Message to display to the user.
	 */
	private function displayError($message) {

		if ( empty($message) ) return false;

		echo sprintf('<span class="%s">%s</span>', 'error', $message);

	}

}