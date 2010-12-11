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

    protected $_plugins = array();
    
    /**
     * @var array Scale options
     */
    protected $_scaleOptions = array();

    
    public function addPlugin(VersionPlugin\Plugin $plugin)
    {
        $this->_plugins[] = $plugin;
    }
    
    
    public function getPlugins()
    {
        return $this->_plugins;
    }
    
    
    public function setPlugins(array $plugins = array())
    {
        foreach($plugins as $plugin)
        {
            $plugin = new $plugin['type']($plugin);
            $this->addPlugin($plugin);
        }

    }
    
    
    
    
    
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
        if($this->getFilelib()->file()->getType($file) != 'image') {
            throw new Exception('File must be an image');
        }
   
        // $img = new Imagick($this->getFilelib()->getStorage()->retrieve($file)->getPathname());
        $img = $this->_getImageMagick($file);
        
        $scaleOptions = $this->getScaleOptions();
        $scaleMethod = $scaleOptions['method'];
        unset($scaleOptions['method']);
        
        foreach($this->getPlugins() as $plugin) {
            $plugin->beforeSetOptions($img);    
        }
        
        foreach($this->getImageMagickOptions() as $key => $value) {
            $method = "set" . $key;
            $img->$method($value);
        }

        foreach($this->getPlugins() as $plugin) {
            $plugin->beforeScale($img);    
        }
                
        call_user_func_array(array($img, $scaleMethod), $scaleOptions);

        foreach($this->getPlugins() as $plugin) {
            $plugin->afterScale($img);    
        }
        
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

            $img = new Imagick($this->getFilelib()->getStorage()->retrieve($file)->getPathname());
            
            $imageMagicks[$file->getId()] = array(
                'obj' => $img,
                'last_access' => 0,
            );
            
            
        }

        $imageMagicks[$file->getId()]['last_access'] = $unixNow;
        
        
        return $imageMagicks[$file->getId()]['obj']->clone();
    }


}