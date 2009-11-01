<?php
/**
 * Emerald exception base class. Extend in da future?
 *
 */
class Emerald_Exception extends Exception
{

	private $_httpResponseCode = 500;
	
	
	public function __construct($message, $httpResponseCode = 500)
	{
		$this->_httpResponseCode = $httpResponseCode;
		parent::__construct($message, $httpResponseCode);
	}
	
	
	
	public function getHttpResponseCode()
	{
		return $this->_httpResponseCode;	
	}
	
	
}

