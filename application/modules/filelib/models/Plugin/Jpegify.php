<?php
class Filelib_Model_Plugin_Jpegify extends Filelib_Model_Plugin_Abstract
{
	
	
	public function beforeUpload(Filelib_Model_Upload $upload)
	{
		$oldUpload = $upload;
		
		$mimetype = $oldUpload->getMimeType();
		if(preg_match("/^image/", $mimetype) && $mimetype != 'image/jpeg') {

			$tempnam = tempnam(sys_get_temp_dir(), 'filelib');
			
			$img = new Imagick($oldUpload->getPathname()); 
			$img->setImageFormat('jpeg');
			$img->setCompressionQuality(70);			
			
			$img->writeImage($tempnam);			
						
			$pinfo = pathinfo($oldUpload);
			
			$upload = $this->getFilelib()->getUpload($tempnam);
			
			// $upload = new Filelib_Model_Upload($tempnam);
			$upload->setOverrideFilename($pinfo['filename'] . '.jpg');
						
			return $upload;
			
		}
		
		
	}
	
	
	
	
	
}