<?php
/**
 * Beautifurler factory
 * 
 * @author pekkis
 * @package Emerald_Beautifurl
 *
 */
class Emerald_Beautifurl
{

    static private $_beautifurlers = array();

    static public function factory($beautifurlIdentifier = 'Default') {

        if(!$beautifurlIdentifier) {
            $beautifurlIdentifier = 'Default';
        }
                
        if(!isset(self::$_beautifurlers[$beautifurlIdentifier])) {

            $split = explode(";", $beautifurlIdentifier);
            (isset($split[1])) ? parse_str($split[1], $options) : $options = array();
            	
            $className = "Emerald_Beautifurl_" . $split[0];
            self::$_beautifurlers[$beautifurlIdentifier] = new $className($options);
        }

        return self::$_beautifurlers[$beautifurlIdentifier];

    }

}
