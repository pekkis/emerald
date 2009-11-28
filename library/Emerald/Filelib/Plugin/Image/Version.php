<?php
class Emerald_Filelib_Plugin_Image_Version extends Emerald_Filelib_Plugin_Abstract
{
	protected $_identifier;	
	
	protected $_imageMagickOptions = array();
	
	protected $_scaleOptions = array();
	
	
	public function setImageMagickOptions($imageMagickOptions)
	{
		$this->_imageMagickOptions = $imageMagickOptions;
	}
		
	
	public function getImageMagickOptions()
	{
		return $this->_imageMagickOptions;
	}
		
	
	public function setIdentifier($identifier)
	{
		$this->_identifier = $identifier;
	}

	
	public function getIdentifier()
	{
		return $this->_identifier;
	}
		
	
	public function setScaleOptions($scaleOptions)
	{
		$this->_scaleOptions = $scaleOptions;
	}

	
	public function getScaleOptions()
	{
		return $this->_scaleOptions;
	}
	
	
	public function init()
	{
		$this->getFilelib()->addFileVersion('image', $this->getIdentifier(), $this);
	}
	
	
	
	public function afterUpload(Emerald_Filelib_FileItem $file)
	{
		
		if(substr($file->mimetype, 0, 6) == 'image/') {
						
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
	
	
	public function createSymlink(Emerald_Filelib_FileItem $file)
	{
		if(substr($file->mimetype, 0, 6) == 'image/') {
			$fl = $this->getFilelib();
			$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier() . '.' . $pinfo['extension'];
			if(!is_link($link)) {
				$path = dirname($link);
				if(!is_dir($path)) {
					mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
				}
				symlink($file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id, $link);
			}
		}
	}
	
	public function deleteSymlink(Emerald_Filelib_FileItem $file)
	{
		$fl = $this->getFilelib();
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
		$pinfo = pathinfo($link);
		$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier() . '.' . $pinfo['extension'];
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