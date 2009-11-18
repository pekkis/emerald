<?php
class Emerald_Filelib_Symlinker
{

	private $_filelib;
	
	public function __construct($filelib)
	{
		$this->_filelib = $filelib;
	}
	
	
	public function getFilelib()
	{
		return $this->_filelib;
	}
		
	
	public function createSymlink($file)
	{
		$fl = $this->getFilelib();
		
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
						
		if(!is_link($link)) {

			$path = dirname($link);
			
			if(!is_dir($path))
				mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);

							
			symlink($file->getPathname(), $link);
			
		}
		
		foreach($this->getFilelib()->getPlugins() as $plugin) {
			$plugin->setFile($file);
			$plugin->createSymlink();
		}
		
		
	}
	
	
	public function deleteSymlink($file)
	{
		$fl = $this->getFilelib();
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
		if(is_link($link)) {
			unlink($link);
		}
		
		
		foreach($this->getFilelib()->getPlugins() as $plugin) {
			$plugin->setFile($file);
			$plugin->deleteSymlink();
		}
		
		
	}
	
	
	
	
}
