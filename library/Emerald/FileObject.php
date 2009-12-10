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
		if(!$this->_mimeType) {
			if (version_compare(PHP_VERSION, '5.3.0') !== -1) {
				$fileinfo = new finfo(FILEINFO_MIME_TYPE);				
			} else {
				$fileinfo = new finfo(FILEINFO_MIME, $this->getFilelib()->getMagic());
			}
			return $fileinfo->file($this->getRealPath()); 
		}
	}
	
	
	
}
