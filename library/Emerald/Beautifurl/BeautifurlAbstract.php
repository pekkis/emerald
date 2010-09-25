<?php
/**
 * Abstract convenience base class for beautifurlers
 * 
 * @author pekkis
 * @package Emerald_Beautifurl
 *
 */
class Emerald_Beautifurl_BeautifurlAbstract
{
    
    /**
     * @var string White space beautifier
     */
    private $_spaceBeautifier = '-';
    
    /**
     * @var string Separator
     */
    private $_separator = '/';

    public function __construct($options = array()) {
        Emerald_Options::setConstructorOptions($this, $options);
    }
    
    /**
     * Sets separator
     * 
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->_separator = $separator;
    }
    
    /**
     * Returns separator
     * 
     * @return string
     */
    public function getSeparator()
    {
        return $this->_separator;
    }
    
    /**
     * Sets white space beautifier
     * 
     * @param string $spaceBeautifier
     */
    public function setSpaceBeautifier($spaceBeautifier)
    {
        $this->_spaceBeautifier = $spaceBeautifier;
    }

    
    /**
     * Returns white space beautifier
     * 
     * @return string
     */
    public function getSpaceBeautifier()
    {
        return $this->_spaceBeautifier;
    }
    
    
}