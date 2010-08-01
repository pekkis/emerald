<?php
/**
 * Default beautifurler
 * 
 * @author pekkis
 * @package Emerald_Beautifurl
 *
 */
class Emerald_Beautifurl_Default
{
    private $_options = array();

    public function __construct($options = array()) {
        $this->_options = $options;
    }


    /**
     * Makes and returns beautifurl from a string
     *
     * @param string $str Source string
     * @param string $beautifier Spacify with
     * @return string
     */
    public function fromString($str, $beautifier = '-')
    {

        if(isset($this->_options['skip']) && $this->_options['skip'] == 1) {
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
    public function fromArray(array $fragments, $prepend = null, $beautifier = '-')
    {
        $beautifulFragments = array();
        foreach($fragments as $fragment) {
            $beautifulFragments[] = $this->fromString($fragment, $beautifier);
        }

        if($prepend) {
            array_unshift($beautifulFragments, $prepend);
        }

        $beautifurl = implode('/', $beautifulFragments);
        return $beautifurl;

    }




}