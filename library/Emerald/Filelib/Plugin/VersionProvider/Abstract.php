<?php
/**
 * Abstract convenience class for version provider plugins
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Plugin_VersionProvider_Abstract
extends Emerald_Filelib_Plugin_Abstract
implements Emerald_Filelib_Plugin_VersionProvider_Interface 
{
	/**
	 * @var string Version identifier
	 */
	protected $_identifier;	
	
	
	/**
	 * @var array Array of file types for which the plugin provides a version
	 */
	protected $_providesFor = array();
	
	
	/**
	 * @var File extension for the version
	 */
	protected $_extension;
	
	
	public function afterUpload(Emerald_Filelib_FileItem $file)
	{
		if($this->providesFor($file)) {
			$this->createVersion($file);
		}
	}
	
	
	
	public function createSymlink(Emerald_Filelib_FileItem $file)
	{
		if($this->providesFor($file)) {
			$fl = $this->getFilelib();
			$link = $fl->getSymlinker()->getLink($file);
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier();

			
			$link .= '.' . $this->getExtension();	
									
			if(!is_link($link)) {

				$path = dirname($link);
				if(!is_dir($path)) {
					mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
				}
								
				if($fl->getRelativePathToRoot()) {
										
					// Relative linking requires some movin'n groovin.
					$oldCwd = getcwd();
					chdir($path);
					
					$fp = dirname($this->getFilelib()->getSymlinker()->getLinkSource($file, 1));
					$fp .= '/' . $this->getIdentifier() . '/' . $file->id;
					
					symlink($fp, $link);
					
					chdir($oldCwd);
				
				} else {
					symlink($file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id, $link);
				}
				
			}
		}
	}
	
	public function deleteSymlink(Emerald_Filelib_FileItem $file)
	{
		if($this->providesFor($file)) {
			$fl = $this->getFilelib();
			$link = $fl->getSymlinker()->getLink($file);
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier();
	
			
			$link .= '.' . $this->getExtension();	
							
			if(is_link($link)) {
				unlink($link);			
			}
		}		
	}
	
	
	
	public function getRenderPath(Emerald_Filelib_FileItem $file)
	{
		if($file->isAnonymous()) {

			$fl = $this->getFilelib();
			$link = $fl->getPublicDirectoryPrefix() . '/' . $fl->getSymlinker()->getLink($file, false);
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier() . '.' . $this->getExtension();

			return $link;
			
		} else {
			$path = $file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id;	
		}
				
		return $path;		
	}
	
	
	/**
	 * Sets identifier
	 * 
	 * @param string $identifier
	 */
	public function setIdentifier($identifier)
	{
		$this->_identifier = $identifier;
	}

	
	/**
	 * Returns identifier
	 * 
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
	
	
	/**
	 * Sets file types for this version plugin.
	 * 
	 * @param array $providesFor Array of file types
	 */
	public function setProvidesFor(array $providesFor)
	{
		$this->_providesFor = $providesFor;		
	}
	
	
	/**
	 * Returns file types which the version plugin provides version for.
	 * 
	 * @return array
	 */
	public function getProvidesFor()
	{
		return $this->_providesFor;
	}
	
	
	/**
	 * Returns whether the plugin provides a version for a file.
	 * 
	 * @param Emerald_Filelib_FileItem $file File item
	 * @return boolean
	 */
	public function providesFor(Emerald_Filelib_FileItem $file)
	{
		return (in_array($file->getType(), $this->getProvidesFor()));
	}
	
	
	/**
	 * Sets versions file extension
	 * 
	 * @param string $extension Extension without the prefixing dot
	 */
	public function setExtension($extension)
	{
		$extension = str_replace('.', '', $extension);
		$this->_extension = $extension;
	}
	
	
	/**
	 * Returns the plugins file extension without the prefixing dot.
	 * 
	 * @return string
	 */
	public function getExtension()
	{
		return $this->_extension;
	}

	
	/* 
	 * Init registers version with identifier
	 */
	public function init()
	{
		if(!$this->getIdentifier()) {
			throw new Emerald_Filelib_Exception('Version plugin must have an identifier');
		}
		
		if(!$this->getExtension()) {
			throw new Emerald_Filelib_Exception('Version plugin must have a file extension');
		}
		

		foreach($this->getProvidesFor() as $fileType) {
			$this->getFilelib()->addFileVersion($fileType, $this->getIdentifier(), $this);
		}
		
	}
	
	
}
