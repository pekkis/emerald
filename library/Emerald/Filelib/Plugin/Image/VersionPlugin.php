<?php

namespace Emerald\Filelib\Plugin\Image;

/**
 * Versions an image
 *
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class VersionPlugin extends \Emerald\Filelib\Plugin\VersionProvider\AbstractVersionProvider
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
     * Creates and stores version
     *
     * @param \Emerald_FileItem $file
     */
    public function createVersion(\Emerald\Filelib\FileItem $file)
    {
        if($file->getType() != 'image') {
            throw new Exception('File must be an image');
        }
   
        $img = new Imagick($this->getFilelib()->getStorage()->retrieve($file)->getPathname());
        
        $scaleOptions = $this->getScaleOptions();
        $scaleMethod = $scaleOptions['method'];
        unset($scaleOptions['method']);

        foreach($this->getImageMagickOptions() as $key => $value) {
            $method = "set" . $key;
            $img->$method($value);
        }

        call_user_func_array(array($img, $scaleMethod), $scaleOptions);
        
        $tmp = $this->getFilelib()->getTempDir() . '/' . tmpfile();
        $img->writeImage($tmp);
        
        $this->getFilelib()->getStorage()->storeVersion($file, $this, $tmp);        
        
    }

}