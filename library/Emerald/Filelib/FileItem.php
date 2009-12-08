<?php
/**
 * Emerald filelib file item
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FileItem extends Emerald_Model_AbstractItem
{
	private $_filelib;

	
	public function setFilelib(Emerald_Filelib $filelib)
	{
		$this->_filelib = $filelib;
	}
	
	
	public function getFilelib()
	{
		return $this->_filelib; 
	}
	
	
	public function findFiles()
	{
		return $this->getFilelib()->findFilesIn($this);
	}
	
	
	public function getType()
	{
		return $this->getFilelib()->getFileType($this);
	}
	

	
	public function getPath()
	{
		$fl = $this->getFilelib();
		return $fl->getRoot() . '/' .  $fl->getDirectoryId($this->id);
	}
	
	
	public function getPathname()
	{
		$fl = $this->getFilelib();
		return $this->getPath() . '/' . $this->id;
	}
	
	
	public function getRenderPath()
	{
		if($this->isAnonymous()) {
			return $this->getFilelib()->getPublicDirectoryPrefix() . '/' . $this->iisiurl;
		} else {
			return $this->getPathname();
		}
	}
		
	
	public function renderPath($opts = array())
	{
		return $this->getFilelib()->renderPath($this, $opts);
	}
		
	
	public function render(Zend_Controller_Response_Http $response, $opts = array())
	{
		return $this->getFilelib()->render($this, $response, $opts);
	}

	
	public function isAnonymous()
	{
		return $this->getFilelib()->fileIsAnonymous($this);
	}
	
	
	public function hasVersion($version)
	{
		return $this->getFilelib()->fileHasVersion($this, $version);
	}
	
}
