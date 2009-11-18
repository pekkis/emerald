<?php
abstract class Emerald_Filelib_Plugin_Abstract implements Emerald_Filelib_Plugin_Interface
{

	protected $_filelib;
	
	protected $_file;
	
	
	public function __construct($options = array())
	{
		Emerald_Options::setConstructorOptions($this, $options);
	}
		
	
	public function setFilelib($filelib)
	{
		$this->_filelib = $filelib;
	}
	
	
	public function getFilelib()
	{
		return $this->_filelib;
	}
	
	
	public function getFile()
	{
		return $this->_file;
	}
	
	
	public function setFile($file)
	{
		$this->_file = $file;
	}
			
	
	public function beforeUpload(Emerald_FileObject $upload)
	{

		return $upload;
	}
	
	
	public function afterUpload()
	{
		
	}
	
	public function beforeDelete()
	{
		
	}
	
	public function afterDelete()
	{
		
	}
			
	
	public function deleteSymlink()
	{
		
	}
	
	
	public function createSymlink()
	{
		
	}
	
	
	
}
