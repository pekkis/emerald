<?php

namespace Emerald\Filelib\Storage\Filesystem\DirectoryIdCalculator;

interface DirectoryIdCalculator
{
    
    /**
     * Calculates directory id (path) for a file
     * 
     * @param \Emerald\Filelib\File\File $file
     * @return string
     */
    public function calculateDirectoryId(\Emerald\Filelib\File\File $file);
}