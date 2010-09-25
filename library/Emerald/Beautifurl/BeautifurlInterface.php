<?php
/**
 * Interface for beautifurlers
 * 
 * @author pekkis
 * @package Emerald_Beautifurl
 *
 */
interface Emerald_Beautifurl_BeautifurlInterface
{

    /**
     * Beautifurls fugliness from urls
     * 
     * @param mixed $fugly Fugly string or an array of fugly fragments
     * @return string Beautified url
     */
    public function beautify($fugly);

}

