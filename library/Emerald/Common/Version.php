<?php
/**
 * Stores the version identifiers of Emerald Common components
 *
 * @package Emerald_Common_Version
 * @author pekkis
 *
 */
final class Emerald_Common_Version
{
    /**
     * Current Emerald CMS version
     */
    const VERSION = '3.0.0-dev';

    /**
     * Compares a Emerald Common version with the current one.
     *
     * @param string $version Emerald Common version to compare.
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


