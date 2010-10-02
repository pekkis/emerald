<?php
/**
 * Interface for beautifurlers
 * 
 * @author pekkis
 * @package Emerald_Common_Beautifurl
 *
 */
interface Emerald_Common_Beautifurl_BeautifurlInterface
{

    /**
     * Beautifurls fugliness from urls
     * 
     * @param mixed $fugly Fugly string or an array of fugly fragments
     * @return string Beautified url
     */
    public function beautify($fugly);

}

