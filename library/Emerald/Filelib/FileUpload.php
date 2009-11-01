<?php
class Emerald_Filelib_FileUpload extends SplFileObject 
{
	private $_mimeType;
		
	private $_fileName;
	
	
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
			
			echo PHP_VERSION;
			
			if (version_compare(PHP_VERSION, '5.3.0') !== -1) {
				$fileinfo = new finfo(FILEINFO_MIME_TYPE);				
			} else {
				$fileinfo = new finfo(FILEINFO_MIME, Emerald_Server::getInstance()->getConfig()->magic);
			}
			return $fileinfo->file($this->getRealPath()); 
		}
		
		
		
		
		
		
	}
	
	
	public function isUploadable()
	{
		
		$mimeTbl = Emerald_Model::get('Filelib_MimeType');
		return ($mimeTbl->fetchRow($mimeTbl->getAdapter()->quoteInto('mimetype = ?', $this->getMimeType()))) ? true : false;
		
	}
	
}
