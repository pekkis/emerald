<?php

namespace Emerald\Filelib\Plugin\Image\VersionPlugin;

use \Imagick;

/**
 * Interface for imagemagick version plugin plugins
 * 
 * @author pekkis
 *
 */
interface Plugin
{
    /**
     * Pre-scale processing
     * 
     * @param Imagick $img
     */
    public function beforeScale(Imagick $img);
    
    /**
     * Post-scale processing
     * 
     * @param Imagick $img
     */
    public function afterScale(Imagick $img);
    
    /**
     * Post-set options processing
     * 
     * @param Imagick $img
     */
    public function beforeSetOptions(Imagick $img);
    
}