<?php
/**
 * Stores the version identifiers of Emerald CMS components
 *
 * @package Emerald_Cms_Version
 * @author pekkis
 * @id $Id$
 *
 */
final class Emerald_Cms_Version
{
    /**
     * Current Emerald CMS version
     */
    const VERSION = '3.0.0-alpha1-dev';

    /**
     * Current jQuery version
     */
    const JQUERY_VERSION = '1.4.2';

    /**
     * Current jQuery UI version
     */
    const JQUERY_UI_VERSION = '1.8.5';

    /**
     * Compares a Emerald CMS version with the current one.
     *
     * @param string $version Emerald CMS version to compare.
     * @return int Returns -1 if older, 0 if it is the same, 1 if version 
     *             passed as argument is newer.
     */
    public static function compare($version)
    {
        $currentVersion = str_replace(' ', '', strtolower(self::VERSION));
        $version = str_replace(' ', '', $version);
        return version_compare($version, $currentVersion);
    }
        
}


