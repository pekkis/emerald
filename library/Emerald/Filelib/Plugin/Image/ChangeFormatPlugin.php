<?php

namespace Emerald\Filelib\Plugin\Image;

/**
 * Changes images' formats before uploading them.
 *
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class ChangeFormatPlugin extends \Emerald\Filelib\Plugin\AbstractPlugin
{
    /**
     * @var array Imagemagick options
     */
    protected $_imageMagickOptions = array();

    /**
     * @var string Target file extension
     */
    protected $_targetExtension;

    /**
     * Sets imagemagick options
     *
     * @param array $imageMagickOptions Options as array
     */
    public function setImageMagickOptions($imageMagickOptions)
    {
        $this->_imageMagickOptions = $imageMagickOptions;
    }

    /**
     * Returns imagemagick options
     *
     * @return array
     */
    public function getImageMagickOptions()
    {
        return $this->_imageMagickOptions;
    }

    /**
     * Sets target file's extension
     *
     * @param string $targetExtension
     */
    public function setTargetExtension($targetExtension)
    {
        $this->_targetExtension = $targetExtension;
    }

    /**
     * Returns target file extension
     *
     * @return string
     */
    public function getTargetExtension()
    {
        return $this->_targetExtension;
    }

    public function beforeUpload(\Emerald\Filelib\FileUpload $upload)
    {
        $oldUpload = $upload;

        $mimetype = $oldUpload->getMimeType();
        if(preg_match("/^image/", $mimetype)) {

            $tempnam = tempnam($this->getFilelib()->getTempDir(), 'filelib');
            	
            $img = new Imagick($oldUpload->getPathname());
            	
            foreach($this->getImageMagickOptions() as $key => $value) {
                $method = "set" . $key;
                $img->$method($value);
            }
            	
            $img->writeImage($tempnam);

            $pinfo = pathinfo($oldUpload);
            	
            $upload = $this->getFilelib()->getUpload($tempnam);
            	
            $upload->setOverrideFilename($pinfo['filename'] . '.' . $this->getTargetExtension());
            	
        }

        return $upload;
    }

}