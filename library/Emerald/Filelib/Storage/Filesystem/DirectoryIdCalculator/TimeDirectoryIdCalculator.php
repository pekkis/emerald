<?php

namespace Emerald\Filelib\Storage\Filesystem\DirectoryIdCalculator;

class TimeDirectoryIdCalculator extends AbstractDirectoryIdCalculator
{
    /**
     * @var string
     */
    private $_format = 'Y/m/d';
    
    
    /**
     * Sets directory creation format
     * 
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->_format = $format;
    }
        
    /**
     * Returns directory creation format
     * 
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }
    
    public function calculateDirectoryId(\Emerald\Filelib\File $file)
    {

        $dt = $file->getDateUploaded();
        $path = $dt->format($this->getFormat());

        return $path;
    }
    
    
    
}
