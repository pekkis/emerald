<?php

namespace Emerald\Filelib\Plugin\Image;

use \Imagick;

class WatermarkedVersionPlugin extends VersionPlugin
{
    
    protected $_watermarkImage = null;
    
    protected $_watermarkPosition = 'sw';
    
    protected $_watermarkPadding = 0;
    
    
    public function setWatermarkImage($image)
    {
        $this->_watermarkImage = $image;
    }
    
    public function getWatermarkImage()
    {
        return $this->_watermarkImage;
    }
        
    public function setWatermarkPosition($position)
    {
        $this->_watermarkPosition = $position;
    }
    
    
    public function getWatermarkPosition()
    {
        return $this->_watermarkPosition;
    }
    
    
    public function setWatermarkPadding($padding)
    {
        $this->_watermarkPadding = $padding;
    }
    
    public function getWatermarkPadding()
    {
        return $this->_watermarkPadding;
    }
    
    
    public function afterScale(Imagick $img)
    {
        if(!$this->getWatermarkImage()) {
            return;
        }
        
        $watermark = new Imagick($this->getWatermarkImage());
        
        $imageWidth 		= $img->getImageWidth();
	    $imageHeight 		= $img->getImageHeight();

	    $wWidth = $watermark->getImageWidth();
	    $wHeight = $watermark->getImageHeight();

	    
	    switch($this->getWatermarkPosition()) {
	        
	        case 'sw':
                $x = 0 + $this->getWatermarkPadding();
                $y = $imageHeight - $wHeight - $this->getWatermarkPadding();
                break;
                
	        case 'nw':
                $x = 0 + $this->getWatermarkPadding();
                $y = 0 + $this->getWatermarkPadding();
	            break;
	            
	        case 'ne':
                $x = $imageWidth - $wWidth - $this->getWatermarkPadding();
                $y = 0 + $this->getWatermarkPadding();
	            break;
	            
	        case 'se':
	            $y = $imageHeight - $wHeight - $this->getWatermarkPadding();
	            $x = $imageWidth - $wWidth - $this->getWatermarkPadding();
	            break;
	        
	    }
	    
	    
        $img->compositeImage(
		    $watermark,
		    Imagick::COMPOSITE_OVER,
		    $x,
		    $y
		);

		return;
    }
    
    
    
    
}