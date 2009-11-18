<?php
class Emerald_FileObject extends SplFileObject 
{
	private $_mimeType;
		
	private $_fileName;
	
	private $_overrideFilename;
	
	/**
	 * @var Emerald_Filelib_Filelib
	 */
	private $_filelib;

	
	
	public function setFilelib($filelib)
	{
		$this->_filelib = $filelib;
	}
	
	
	
	/**
	 * @return Emerald_Filelib_Filelib
	 */
	public function getFilelib()
	{
		return $this->_filelib;
	}
	
	
	
	public function setOverrideFilename($filename)
	{
		$this->_overrideFilename = $filename;
	}
	
	
	public function getOverrideFilename()
	{
		return ($this->_overrideFilename) ? $this->_overrideFilename : $this->getFilename();
	}
	
	
	public function getMimeType()
	{
		if(!$this->_mimeType) {
			if (version_compare(PHP_VERSION, '5.3.0') !== -1) {
				$fileinfo = new finfo(FILEINFO_MIME_TYPE);				
			} else {
				$fileinfo = new finfo(FILEINFO_MIME, $this->getFilelib()->getMagic());
			}
			return $fileinfo->file($this->getRealPath()); 
		}
	}
	
	
	public function canUpload()
	{
		return true;
		
		/*
		$mimeTbl = Emerald_Model::get('Filelib_MimeType');
		return ($mimeTbl->fetchRow($mimeTbl->getAdapter()->quoteInto('mimetype = ?', $this->getMimeType()))) ? true : false;
		*/
		
	}
	
}
