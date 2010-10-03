<?php

namespace Emerald\Base\Beautifurl;

/**
 * Interface for beautifurlers
 * 
 * @author pekkis
 *
 */
interface BeautifurlInterface
{

    /**
     * Beautifurls fugliness from urls
     * 
     * @param mixed $fugly Fugly string or an array of fugly fragments
     * @return string Beautified url
     */
    public function beautify($fugly);

}

