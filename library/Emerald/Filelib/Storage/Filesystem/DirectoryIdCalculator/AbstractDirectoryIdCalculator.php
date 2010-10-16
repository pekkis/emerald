<?php

namespace Emerald\Filelib\Storage\Filesystem\DirectoryIdCalculator;

abstract class AbstractDirectoryIdCalculator implements DirectoryIdCalculator
{
    
    
    public function __construct($options)
    {
        \Emerald\Base\Options::setConstructorOptions($this, $options);
    }
    
    
}