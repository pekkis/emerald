<?php
/**
 * Prototype test plugin for video versioning
 * 
 * @package Emerald_Filelib
 * @author pekkis
 * @todo Abstract to generic ffmpeg plugin
 *
 */
class Emerald_Filelib_Plugin_Video_Flashify
extends Emerald_Filelib_Plugin_VersionProvider_Abstract
{
	protected $_providesFor = array('video');	
	
	
	/**
	 * Creates version. Potentially overwrites old one.
	 * 
	 * @param Emerald_FileItem $file
	 */
	public function createVersion(Emerald_Filelib_FileItem $file)
	{
		if($file->getType() != 'video') {
			throw new Exception('File must be an video');
		}

		$path = $file->getPath() . '/' . $this->getIdentifier();
		
		if(!is_dir($path)) {
			mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
		}
											
   		$exec_string = "/usr/bin/ffmpeg -i {$file->getPathname()} -f flv {$path}/{$file->id}";
   		
   		exec($exec_string); //where exxc is the command used to execute shell comma
		
	}
	
	
}
?>