<?php
/**
 * Default beautifurler
 * 
 * @author pekkis
 * @package Emerald_Base_Beautifurl
 *
 */
class Emerald_Base_Beautifurl_Default extends Emerald_Base_Beautifurl_BeautifurlAbstract
{
    
    /**
     * @var string
     */
    private $_skipReplaces = false;
    
    /**
     * Sets whether to skip all character replacings
     * 
     * @param boolean $skipReplaces
     */
    public function setSkipReplaces($skipReplaces)
    {
        $this->_skipReplaces = $skipReplaces;
    }

    
    /**
     * Returns whether to skip all character replacings
     * 
     * @return boolean
     */
    public function getSkipReplaces()
    {
        return $this->_skipReplaces;
    }
       
    
    public function beautify($fugly)
    {
        if(is_array($fugly)) {
            return $this->_fromArray($fugly);
        }
        
        $fugly = explode($this->getSeparator(), $fugly);

        if (sizeof($fugly) == 1) {
            return $this->_fromString($fugly[0]);
        }
        
        return $this->_fromArray($fugly);
    }
    
    

    /**
     * Makes and returns beautifurl from a string
     *
     * @param string $str Source string
     * @param string $beautifier Spacify with
     * @return string
     */
    private function _fromString($str)
    {
        $beautifier = $this->getSpaceBeautifier();        
        
        if ($this->getSkipReplaces()) {
            return $str;
        }

        $beautifurl = htmlentities(mb_strtolower($str, 'utf8'), ENT_COMPAT, 'utf-8');
        $beautifurl = preg_replace('/&(.)(acute|cedil|circ|ring|tilde|uml);/', "$1", $beautifurl);
        $beautifurl = preg_replace('/([^a-z0-9]+)/', $beautifier, html_entity_decode($beautifurl));
        $beautifurl = trim($beautifurl, $beautifier);

        return $beautifurl;
    }


    /**
     * Makes and returns a beautifurl from an array
     * 
     * @param array $fragments Fugly fragments
     * @param string $prepend Optionally prepend with
     * @param string $beautifier Spacify with
     * @return string
     */
    private function _fromArray(array $fragments)
    {
        $beautifier = $this->getSpaceBeautifier();
        
        $beautifulFragments = array();
        foreach ($fragments as $fragment) {
            $beautifulFragments[] = $this->_fromString($fragment, $beautifier);
        }

        $beautifurl = implode($this->getSeparator(), $beautifulFragments);
        return $beautifurl;

    }




}