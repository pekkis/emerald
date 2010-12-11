<?php

namespace Emerald\Filelib;

/**
 * Stores the version of Emerald Filelib
 *
 * @author pekkis
 *
 */
final class Version
{
    /**
     * Current Emerald Filelib version
     */
    const VERSION = '1.0.0-beta1-dev';

    /**
     * Compares a Emerald Filelib version with the current one.
     *
     * @param string $version Emerald Filelib version to compare.
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
