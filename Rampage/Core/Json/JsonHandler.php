<?php

namespace Core\Json;

/**
 * JsonHandler
 * A class for decoding and encoding json with proper exception throws in case
 *  of errors, during the encoding or decoding process.
 *
 * @author Martin Ricken <kreean@lorique.net>
 * @version 1.0.0
 * @package Core
 * @subpackage Json
 */
class JsonHandler
{
	/**
	 * Class constructor
	 */
	public function __construct()
	{
		if (!function_exists ('json_encode')) {
			die('You need to install the php-json library in order to use this handler.');
		}
	}
	
	/**
	 * Encode
	 * A function for encoding an object or array to json.
	 *
	 * @param array|object $data an array or object to be encoded.
	 *
	 * @exception JsonEncodeException thrown if encoding fails.
	 * @return string
	 */
	public function encode($data)
	{
		$data = json_encode($data);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$error = $this->interpretResponseCode(json_last_error());
			throw new JsonEncodeException('Json encoding failed: ' . $error);
		}
		
		return $data;
	}
	
	/**
	 * Decode
	 * A function for decoding a json string to its relative object or array.
	 *
	 * @param string $data
	 * @param bool $assoc
	 *
	 * @exception JsonDecodeException thrown if decoding fails.
	 * @return array
	 */
	public function decode($data)
	{
		$data = json_decode($data, TRUE);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$error = $this->interpretResponseCode(json_last_error());
			throw new JsonDecodeException('Json decoding failed: ' . $error);
		}
		return $data;
	}
	
	/**
	 * Interpret Response Code
	 * A function for interpreting a response code to a viable error message used
	 *  to throw a proper exception.
	 *
	 * @param integer $response_code
	 *
	 * @return string 
	 */
	protected function interpretResponseCode($response_code)
	{
		switch ($response_code) {
			case JSON_ERROR_DEPTH:
				$error_message = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error_message = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error_message = 'Control character error, possibly incorrectly encoded JSON.';
				break;
			case JSON_ERROR_SYNTAX:
				$error_message = 'Syntax error found, possibly incorrectly encoded JSON.';
				break;
			case JSON_ERROR_UTF8:
				$error_message = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			default:
				$error_message = 'Unknown response code.';
		}
		
		return $error_message;
	}
}

/**
 * Json Encode Exception
 * An exception class to be thrown when json encoding fails.
 *
 * @author Martin Ricken <kreean@lorique.net>
 * @version 1.0.0
 * @package Core
 * @subpackage Json
 */
class JsonEncodeException extends \Exception {}

/**
 * Json Decode Exception
 * An exception class to be thrown when json decoding fails.
 *
 * @author Martin Ricken <kreean@lorique.net>
 * @version 1.0.0
 * @package Core
 * @subpackage Json
 */
class JsonDecodeException extends \Exception {}
