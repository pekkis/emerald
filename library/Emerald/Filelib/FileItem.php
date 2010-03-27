<?php
/**
 * File item
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FileItem extends Emerald_Model_AbstractItem
{
	/**
	 * @var Emerald_Filelib Filelib
	 */
	private $_filelib;
	
	private $_profileObj;
	
	
	public function setProfileObject(Emerald_Filelib_FileProfile $profileObj)
	{
		$this->_profileObj = $profileObj;
	}
	
	
	public function getProfileObject()
	{
		return $this->_profileObj;
	}
	
	
	
	/**
	 * Sets filelib
	 * 
	 * @param Emerald_Filelib $filelib
	 */
	public function setFilelib(Emerald_Filelib $filelib)
	{
		$this->_filelib = $filelib;
	}
	
	/**
	 * Returns filelib
	 * 
	 * @return Emerald_Filelib
	 */
	public function getFilelib()
	{
		return $this->_filelib;
	}
		
	
	/**
	 * Finds and returns folder
	 * 
	 * @return Emerald_Filelib_FolderItem|false
	 */
	public function findFolder()
	{
		return $this->getFilelib()->folder()->find($this->folder_id);
	}
	
	
	/**
	 * Returns file type
	 * 
	 * @return string
	 */
	public function getType()
	{
		return $this->getFilelib()->file()->getType($this);
	}
	

	
	/**
	 * Returns file's directory
	 * 
	 * @return string
	 */
	public function getPath()
	{
		$fl = $this->getFilelib();
		return $fl->getRoot() . '/' .  $fl->getDirectoryId($this->id);
	}
	
	
	/**
	 * Returns path to file.
	 * 
	 * @return string
	 */
	public function getPathname()
	{
		return $this->getPath() . '/' . $this->id;
	}
	
	
	
	/**
	 * Returns file's render path. Url if anonymous, filesystem path otherwise.
	 * 
	 * @return string
	 */
	public function getRenderPath()
	{
		if($this->isAnonymous()) {
			return $this->getFilelib()->getPublicDirectoryPrefix() . '/' . $this->getProfileObject()->getSymlinker()->getLink($this, false);
		} else {
			return $this->getPathname();
		}
	}
		
	
	/**
	 * Renders file's path.
	 * 
	 * @param $opts array Render options
	 * @return string Render path
	 */
	public function renderPath($opts = array())
	{
		return $this->getFilelib()->file()->renderPath($this, $opts);
	}
		
	
	/**
	 * Renders file to HTTP response
	 * 
	 * @param Zend_Controller_Response_Http $response Response  
	 * @param array $opts Options
	 */
	public function render(Zend_Controller_Response_Http $response, $opts = array())
	{
		return $this->getFilelib()->file()->render($this, $response, $opts);
	}

	
	/**
	 * Returns whether the file is readable by anonymous.
	 * 
	 * @return boolean
	 */
	public function isAnonymous()
	{
		return $this->getFilelib()->file()->isAnonymous($this);
	}
	
	
	/**
	 * Returns whether the file has a certain version
	 * 
	 * @param string $version Version identifier
	 * @return boolean
	 */
	public function hasVersion($version)
	{
		return $this->getFilelib()->file()->hasVersion($this, $version);
	}
	
	/**
	 * Delete this file
	 * 
	 * @return true
	 */
	public function delete()
	{
		return $this->getFilelib()->file()->delete($this);
	}
	
	
	
	
}
