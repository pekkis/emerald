<?php

namespace Emerald\Filelib\Plugin\Image;

use \Imagick;

/**
 * Versions an image
 *
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class VersionPlugin extends \Emerald\Filelib\Plugin\VersionProvider\AbstractVersionProvider
{
    const IMAGEMAGICK_LIFETIME = 5;
    
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
    public function createVersion(\Emerald\Filelib\File\File $file)
    {
        if($file->getType() != 'image') {
            throw new Exception('File must be an image');
        }
   
        // $img = new Imagick($this->getFilelib()->getStorage()->retrieve($file)->getPathname());
        $img = $this->_getImageMagick($file);
        
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

        return $tmp;
        
    }
    
    
    private function _getImageMagick(\Emerald\Filelib\File\File $file)
    {
        static $imageMagicks = array();

        $unixNow = time();
        
        $deletions = array();
        foreach($imageMagicks as $key => $im) {
            if($im['last_access'] < ($unixNow - self::IMAGEMAGICK_LIFETIME)) {
                $deletions[] = $key;
            }
        }
        
        foreach($deletions as $deletion) {
            // \Zend_Debug::dump('deleting poo poo');
            unset($imageMagicks[$key]);
        }
        
        
        if(!isset($imageMagicks[$file->getId()])) {
            $imageMagicks[$file->getId()] = array(
                'obj' => new Imagick($this->getFilelib()->getStorage()->retrieve($file)->getPathname()),
                'last_access' => 0,
            );
        }

        $imageMagicks[$file->getId()]['last_access'] = $unixNow;
        
        
        return $imageMagicks[$file->getId()]['obj']->clone();
    }
    

}