<?php
class Filelib_Model_Plugin_Image_ChangeFormat extends Filelib_Model_Plugin_Abstract
{
		
	protected $_imageMagickOptions = array();

	protected $_targetExtension;
	
	public function setImageMagickOptions($imageMagickOptions)
	{
		$this->_imageMagickOptions = $imageMagickOptions;
	}
		
	
	public function getImageMagickOptions()
	{
		return $this->_imageMagickOptions;
	}
	
	
	public function setTargetExtension($targetExtension)
	{
		$this->_targetExtension = $targetExtension;
	}
	
	
	public function getTargetExtension()
	{
		return $this->_targetExtension;
	}
				
	
	
	public function beforeUpload(Filelib_Model_Upload $upload)
	{
		$oldUpload = $upload;
		
		$mimetype = $oldUpload->getMimeType();
		if(preg_match("/^image/", $mimetype)) {

			$tempnam = tempnam(sys_get_temp_dir(), 'filelib');
			
			$img = new Imagick($oldUpload->getPathname()); 
			
			foreach($this->getImageMagickOptions() as $key => $value) {
				$method = "set" . $key;
				$img->$method($value);
			}
			
			$img->writeImage($tempnam);			
						
			$pinfo = pathinfo($oldUpload);
			
			$upload = $this->getFilelib()->getUpload($tempnam);
			
			// $upload = new Filelib_Model_Upload($tempnam);
			$upload->setOverrideFilename($pinfo['filename'] . '.' . $this->getTargetExtension());
						
			return $upload;
			
		}
		
		
	}
	
	
}