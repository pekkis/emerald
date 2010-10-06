<?php

namespace Emerald\Filelib\Publisher;

use Emerald\Base\Options;

/**
 * Abstract convenience publisher base class implementing common methods
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class AbstractPublisher implements PublisherInterface
{
    /**
     * @var \Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;
    
    public function __construct($options = array())
    {
        Options::setConstructorOptions($this, $options);
    }
    
    /**
     * Sets filelib
     *
     * @param \Emerald_Filelib $filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    
    
    
}