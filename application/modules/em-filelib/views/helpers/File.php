<?php
/**
 * File(lib) view helper
 * 
 * @author pekkis
 * @todo Exception handling, etc
 *
 */
class Filelib_View_Helper_File extends Zend_View_Helper_Abstract
{
	
	
	private $_onNotFoundGoto = array('module' => 'default', 'controller' => 'error', 'action' => 'not-found');
	
	private $_onDeniedGoto = array('module' => 'default', 'controller' => 'error', 'action' => 'forbidden');
	
	
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
	
	
	
	public function setOnNotFoundGoto(array $onNotFoundGoto)
	{
		$this->_onNotFoundGoto = $onNotFoundGoto;	
	}
	
	public function setonDeniedGoto(array $onNotFoundGoto)
	{
		$this->_onDeniedGoto = $onNotFoundGoto;
	}
	
	
	public function url($file, $options = array())
	{
		$filelib = $this->getFilelib();		
		
		if(!$file instanceof Emerald_Filelib_FileItem) {
			$file = $filelib->file()->find($file);
		}
		
		if(!$file) {
			$goto = $this->_onNotFoundGoto;
			$url = $this->view->url($goto, 'default', true);
			return $url;
		}
		
		
		if($file->isAnonymous()) {
			return $file->renderPath($options);
		} else {
			return $this->view->url(array('module' => 'filelib', 'controller' => 'file', 'action' => 'render', 'id' => $file->id));	
		}
		
	}
	
	
}
