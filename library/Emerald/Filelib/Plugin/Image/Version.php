<?php
/**
 * Versions an image
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Plugin_Image_Version extends Emerald_Filelib_Plugin_VersionProvider_Abstract
{
	
	/**
	 * @var array Scale options
	 */
	protected $_scaleOptions = array();
	
	
	/**
	 * Sets ImageMagick options
	 * 
	 * @param array $imageMagickOptions
	 */
	public function setImageMagickOptions($imageMagickOptions)
	{
		$this->_imageMagickOptions = $imageMagickOptions;
	}
	
	
	/**
	 * Return ImageMagick options
	 * 
	 * @return array
	 */
	public function getImageMagickOptions()
	{
		return $this->_imageMagickOptions;
	}
		
	
	
		
	
	/**
	 * Sets scale options
	 * 
	 * @param array $scaleOptions
	 */
	public function setScaleOptions($scaleOptions)
	{
		$this->_scaleOptions = $scaleOptions;
	}

	
	/**
	 * Returns scale options
	 * 
	 * @return array
	 */
	public function getScaleOptions()
	{
		return $this->_scaleOptions;
	}
	
	
	public function init()
	{
		// Register a version for images with identifier and self as provider.
		$this->getFilelib()->addFileVersion('image', $this->getIdentifier(), $this);
	}
	
	
	/**
	 * Creates version. Potentially overwrites old one.
	 * 
	 * @param Emerald_FileItem $file
	 */
	public function createVersion(Emerald_Filelib_FileItem $file)
	{
		if($file->getType() != 'image') {
			throw new Exception('File must be an image');
		}
				
		$img = new Imagick($file->getPathname()); 
		$scaleOptions = $this->getScaleOptions();
		$scaleMethod = $scaleOptions['method'];
		unset($scaleOptions['method']);
		call_user_func_array(array($img, $scaleMethod), $scaleOptions);
		$path = $file->getPath() . '/' . $this->getIdentifier();
		if(!is_dir($path)) {
			mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
		}
		$img->writeImage($path . '/' . $file->id);
	}
	
	
	public function afterUpload(Emerald_Filelib_FileItem $file)
	{
		if($file->getType() == 'image') {
			$this->createVersion($file);
		}
	}
	
	
	public function createSymlink(Emerald_Filelib_FileItem $file)
	{
		if($file->getType() == 'image') {
			$fl = $this->getFilelib();
			$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier();

			if(isset($pinfo['extension'])) {
				$link .= '.' . $pinfo['extension'];	
			}
						
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
		$fl = $this->getFilelib();
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
		$pinfo = pathinfo($link);
		$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier();

		if(isset($pinfo['extension'])) {
			$link .= '.' . $pinfo['extension'];	
		}
				
		if(is_link($link)) {
			unlink($link);			
		}
		
	}
	
	
	
	public function getRenderPath(Emerald_Filelib_FileItem $file)
	{
		if($file->isAnonymous()) {

			$fl = $this->getFilelib();
			$link = $fl->getPublicDirectoryPrefix() . '/' . $file->iisiurl;
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier() . '.' . $pinfo['extension'];

			return $link;
			
		} else {
			$path = $file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id;	
		}
				
		return $path;		
	}
	
	
	
}