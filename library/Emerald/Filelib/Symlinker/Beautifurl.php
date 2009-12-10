<?php
/**
 * This symlinker creates magnificent beautifurls from the directory names
 * and structure.
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Symlinker_Beautifurl
extends Emerald_Filelib_Symlinker_Abstract
implements Emerald_Filelib_Symlinker_Interface
{

	
	
	public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $prefix = true)
	{
		$link = $this->getLink($file, $prefix);
		$pinfo = pathinfo($link);
		$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $version->getIdentifier();
		$link .= '.' . $version->getExtension();	
		return $link;
	}
	
	
	public function getLink(Emerald_Filelib_FileItem $file, $prefix = true)
	{
		$folders = array();
		$folders[] = $folder = $file->findFolder();
		while($parent = $folder->findParent()) {
			array_unshift($folders, $parent);
		} 

		$beautifurl = array();
		
		foreach($folders as $folder) {
			$beautifurl[] = $folder->name;
		}
		
		$beautifurl[] = $file->name;

		$beautifurl = implode(DIRECTORY_SEPARATOR, $beautifurl);
		
		if($prefix) {
			$beautifurl = $this->getFilelib()->getPublicRoot() . '/' . $beautifurl; 
		}
		
		return $beautifurl;
		
	}
	
	
	
}
