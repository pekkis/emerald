<?php
/**
 * Randomizes upload's name
 * 
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
	
		
	
	/* (non-PHPdoc)
	 * @see Emerald/Filelib/Plugin/Emerald_Filelib_Plugin_Abstract#beforeUpload()
	 */
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



