<?php
class Emerald_Filelib_FileItem extends Emerald_Model_AbstractItem
{
	protected $_versions;	
	
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
	
	
	
	public function render(Zend_Controller_Response_Http $response, $opts = array())
	{
		return $this->getFilelib()->render($this, $response, $opts);
	}
	
	
	public function isAnonymous()
	{
		return $this->getFilelib()->fileIsAnonymous($this);
	}
	
	
	
}
