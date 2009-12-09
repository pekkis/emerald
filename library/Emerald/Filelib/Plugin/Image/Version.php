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
	protected $_providesFor = array('image');	
		
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
	
	
	
	
	
	
}