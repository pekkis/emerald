<?php

namespace Emerald\Filelib\Plugin\Image\VersionPlugin;

use \Imagick;

/**
 * Abstract convenience class for versionplugin plugins
 * 
 * @author pekkis
 *
 */
abstract class AbstractPlugin implements Plugin
{
    
    public function __construct($options = array())
    {
        \Emerald\Base\Options::setConstructorOptions($this, $options);
    }
    
    public function beforeSetOptions(Imagick $img)
    {
        
    }
    
    public function beforeScale(Imagick $img)
    {
        
    }
    
    
    public function afterScale(Imagick $img)
    {
        
    }
    
    
}