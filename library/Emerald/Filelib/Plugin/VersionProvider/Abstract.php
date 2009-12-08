<?php
abstract class Emerald_Filelib_Plugin_VersionProvider_Abstract
extends Emerald_Filelib_Plugin_Abstract
implements Emerald_Filelib_Plugin_VersionProvider_Interface 
{
	/**
	 * @var string Version identifier
	 */
	protected $_identifier;	
		
	
	/**
	 * Sets identifier
	 * 
	 * @param string $identifier
	 */
	public function setIdentifier($identifier)
	{
		$this->_identifier = $identifier;
	}

	
	/**
	 * Returns identifier
	 * 
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
	
	
}
