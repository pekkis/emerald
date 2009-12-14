<?php
/**
 * File(lib) view helper
 * 
 * @package Emerald_View
 * @author pekkis
 * @todo Exception handling, etc
 *
 */
class Emerald_View_Helper_File extends Zend_View_Helper_Abstract
{
	
	/**
	 * Filelib
	 * 
	 * @var Emerald_Filelib
	 */
	private $_filelib;
	
	/**
	 * Returns filelib
	 * 
	 * @return Emerald_Filelib
	 */
	public function getFilelib()
	{
		if(!$this->_filelib) {
			$this->_filelib = Zend_Registry::get('Emerald_Filelib');
		}
		return $this->_filelib;	
	}
	
	
	/**
	 * Returns self
	 * 
	 * @return Emerald_View_Helper_File
	 */
	public function file()
	{
		return $this;
	}
		

	
	public function url($file, $options = array())
	{
		$filelib = $this->getFilelib();		
		
		if(!$file instanceof Emerald_Filelib_FileItem) {
			$file = $filelib->findFile($file);
		}
		
		if($file->isAnonymous()) {
			return $file->renderPath($options);
		} else {
			return $this->view->url(array('module' => 'filelib', 'controller' => 'file', 'action' => 'render', 'id' => $file->id));	
		}
		
	}
	
	
}