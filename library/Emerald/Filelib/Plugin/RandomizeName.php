<?php
/**
 * Randomizes uploads' file names before uploading
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Plugin_RandomizeName extends Emerald_Filelib_Plugin_Abstract
{
	
	/**
	 * @var string Prefix for uniqid()
	 */
	protected $_prefix = '';
	
	
	/**
	 * Sets prefix
	 * 
	 * @param $prefix
	 */
	public function setPrefix($prefix)
	{
		$this->_prefix = $prefix;
	}
	
	
	/**
	 * Returns prefix
	 * 
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->_prefix;
	}
		
	
	public function beforeUpload(Emerald_FileObject $upload)
	{
		$pinfo = pathinfo($upload->getOverrideFilename());
		$newname = uniqid($this->getPrefix(), false);

		if(isset($pinfo['extension'])) {
			$newname .= '.' . $pinfo['extension'];			
		}				
		
		$upload->setOverrideFilename($newname);	
		return $upload;
	}
	
}



