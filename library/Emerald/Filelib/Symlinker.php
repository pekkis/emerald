<?php
/**
 * Emerald filelib symlinker
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Symlinker
{

	/**
	 * @var Emerald_Filelib Filelib
	 */
	private $_filelib;
		
	/**
	 * @param Emerald_Filelib $filelib
	 */
	public function __construct(Emerald_Filelib $filelib)
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
	
	
	
	/**
	 * Creates symlink(s) for a file
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 */
	public function createSymlink(Emerald_Filelib_FileItem $file)
	{
		$fl = $this->getFilelib();
		
		$link = $this->getLink($file);
								
		if(!is_link($link)) {
			
			$path = dirname($link);
			
			if(!is_dir($path)) {
				mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
			}
			
			if($fl->getRelativePathToRoot()) {
				
				// Relative linking requires some movin'n groovin.
				$oldCwd = getcwd();
				chdir($path);
				symlink($this->getLinkSource($file, 1), $link);
				chdir($oldCwd);
				
			} else {
				symlink($file->getPathname(), $link);
			}
			
			
			
		}
		
		// Forward to all plugins
		foreach($this->getFilelib()->getPlugins() as $plugin) {
			$plugin->createSymlink($file);
		}
		
	}
	
	
	/**
	 * Deletes symlink(s) for a file
	 * 
	 * @param Emerald_Filelib_FileItem $file File item
	 */
	public function deleteSymlink($file)
	{
		$fl = $this->getFilelib();
		
		$link = $this->getLink($file);
		
		if(is_link($link)) {
			unlink($link);
		}
		
		// Forward to all plugins 
		foreach($this->getFilelib()->getPlugins() as $plugin) {
			$plugin->deleteSymlink($file);
		}
		
		
	}
	
	
	
	/**
	 * Returns relative link from 
	 * 
	 * @param Emerald_Filelib_File $file File item
	 * @param $levelsDown How many levels down from root
	 * @return string
	 */
	public function getLinkSource(Emerald_Filelib_FileItem $file, $levelsDown = 0)
	{
		$fl = $this->getFilelib();
		
		$sltr = $fl->getRelativePathToRoot();

		if(!$sltr) {
			throw new Emerald_Filelib_Exception('Relative path must be set!');
		}
		
		$sltr = str_repeat("../", $levelsDown) . $sltr;
				
		$path = $file->getPathname();
		$path = substr($path, strlen($fl->getRoot()));
		
		$sltr = $sltr . $path;
		
		return $sltr;		
		
	}
	
	
	
}
