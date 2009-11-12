<?php
class Filelib_Model_Plugin_Scaler extends Filelib_Model_Plugin_Abstract
{
	
	public function afterUpload()
	{
		$file = $this->getFile();
		$mimetype = $file->mimetype;
		
		if(preg_match("/^image/", $mimetype)) {
						
			$img = new Imagick($file->getPathname()); 
			$img->scaleImage(640, 480, true);
			
			$path = $file->getPath() . '/thumbster';
			
			if(!is_dir($path)) {
				mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
			}
						
			$img->writeImage($path . '/' . $file->id);
			
		}
		
	}
	
	
	public function createSymlink()
	{
		$file = $this->getFile();
		$fl = $this->getFilelib();
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
		$pinfo = pathinfo($link);
		$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-thumbster' . '.' . $pinfo['extension'];
		if(!is_link($link)) {
			$path = dirname($link);
			if(!is_dir($path)) {
				mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
			}
			symlink($file->getPath() . '/thumbster/' . $file->id, $link);
		}
		
	}
	
	public function deleteSymlink()
	{
		$file = $this->getFile();
		$fl = $this->getFilelib();
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
		$pinfo = pathinfo($link);
		$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-thumbster' . '.' . $pinfo['extension'];
		if(is_link($link)) {
			unlink($link);			
		}
		
	}
	
	
}