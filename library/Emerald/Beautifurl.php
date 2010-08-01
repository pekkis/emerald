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

    static public function factory($beautifurlClass) {

        if(!isset(self::$_beautifurlers[$beautifurlClass])) {

            $split = explode(";", $beautifurlClass);
            (isset($split[1])) ? parse_str($split[1], $options) : $options = array();
            	
            $className = "Emerald_Beautifurl_" . $split[0];
            self::$_beautifurlers[$beautifurlClass] = new $className($options);
        }

        return self::$_beautifurlers[$beautifurlClass];

    }

}
