<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2014 Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Helper Class which makes various tools and helper available
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage Classes\Utility
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_Utility_Geocoding
{
	/** @var string  URL to Google's Geocoding Service. */
	static protected $apiUrl = 'http://maps.googleapis.com/maps/api/geocode/';

	/** @var string  Desired format for internal handling (json|xml}. */
	static protected $apiFormat = 'json';

	/** @var int  Timeout in seconds for cURL operations. */
	static protected $timeout = 10;


	/**
	 * Returns the URL to Google's Geocoding Service.
	 *
	 * @return string The Url to Google's Geocoding Service.
	 */
	protected static function getApiUrl()
	{
		return self::$apiUrl;
	}

	/**
	 * Returns the defined format for API calls.
	 *
	 * @return string The format for API calls.
	 */
	protected static function getApiFormat()
	{
		return self::$apiFormat;
	}

	/**
	 * Returns the complete base url to Google's Geocoding Service.
	 *
	 * @return string The complete base url to Google's Geocoding Service.
	 */
	protected static function getGoogleMapsGeoApiCall()
	{
		return self::getApiUrl() . self::getApiFormat();
	}

	/**
	 * Tries to geolocate an address.
	 *
	 * Status will hold one of the following results (string):
	 * - OK: Self-explanatory.
	 * - ZERO_RESULTS: Self-explanatory.
	 * - OVER_QUERY_LIMIT: Self-explanatory.
	 * - REQUEST_DENIED: Param sensor missing.
	 * - INVALID_REQUEST: Param address or latlng missing.
	 *
	 * @param string $address  The address to geolocate
	 * @param bool $sensor
	 * @return object
	 */
	public static function getGeoCoordinates($address, $sensor = false)
	{
		$url = self::getGeoCodingUrl($address, $sensor);
		$res = self::getJsonFromUrl($url);
		return $res;
	}

	/**
	 * Returns the URL for Geocoding, respecting
	 * - Address
	 * - GPS sensor usage
	 *
	 * @param string $address Address to geocode.
	 * @param bool $sensor Defines whether a GPS sensor should be used. Defaults to false.
	 * @return string The final URL to call.
	 */
	public static function getGeoCodingUrl($address, $sensor = false)
	{
		$url = self::getGoogleMapsGeoApiCall() . '?'
			. 'sensor=' . (($sensor) ? 'true' : 'false') . '&'
			. 'address=' . urlencode($address);
		return $url;
	}

	/**
	 * Calls a url, converts the output to JSON and returns it.
	 *
	 * @param string $url The url to call.
	 * @return string JSON result of the url return.
	 * @throws Exception In case the given url is NOT a url OR cURL is not available.
	 */
	protected static function getJsonFromUrl($url)
	{
		if (!function_exists('curl_init')) {
			throw new Exception('Error while geo coding: curl not available!');
		}
		if (!Tx_Helperkit_Utility_Div::isUrl($url)) {
			throw new Exception('Error while geo coding: error in given url: ' . $url);
		}
		$ch = curl_init($url);
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
		);
		curl_setopt_array($ch, $options);

		$result = curl_exec($ch);
		return json_decode($result);
	}

}