<?php
/**
 * Beautifurl factory
 * 
 * @author pekkis
 * @package Emerald_Common_Beautifurl
 *
 */
class Emerald_Common_Beautifurl
{

    static private $_beautifurlers = array();

    /**
     * Factors a beautifurler from an identifier string
     * 
     * @param string $beautifurlIdentifier Identifier string: ClassName ; Options as a query string 
     * @return Emerald_Common_Beautifurl_BeautifurlInterface
     */
    static public function factory($beautifurlIdentifier = 'Default') {

        if(!$beautifurlIdentifier) {
            $beautifurlIdentifier = 'Default';
        }
                
        if(!isset(self::$_beautifurlers[$beautifurlIdentifier])) {
            
            // Split id string, parse options, instantiate beautifurler
            
            $split = explode(";", $beautifurlIdentifier);
            (isset($split[1])) ? parse_str($split[1], $options) : $options = array();
            	
            $className = "Emerald_Common_Beautifurl_" . $split[0];
            self::$_beautifurlers[$beautifurlIdentifier] = new $className($options);
        }

        return self::$_beautifurlers[$beautifurlIdentifier];

    }

}
