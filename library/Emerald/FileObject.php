<?php
/**
 * Extends SplFileObject to offer mime type detection via Fileinfo extension.
 * 
 * @package Emerald_FileObject
 * @author pekkis
 *
 */
class Emerald_FileObject extends SplFileObject 
{
	/**
	 * @var string Mimetype is cached here
	 */
	private $_mimeType;
	
	/**
	 * Returns file's mime type.
	 * 
	 * @return string
	 */
	public function getMimeType()
	{
		if (!$this->_mimeType) {
			$fileinfo = new finfo(FILEINFO_MIME_TYPE);				
			$this->_mimeType = $fileinfo->file($this->getRealPath()); 
		}
		return $this->_mimeType;
	}
}
