<?php
/**
 * Class to store and retrieve the version of Emerald.
 *
 * @package Emerald_Version
 * @author pekkis
 */
final class Emerald_Version
{
    /**
     * Emerald version
     */
    const VERSION = '0.6.0';

    /**
     * Compare the specified Emerald version string $version
     * with the current Emerald_Version::VERSION of Emerald.
     *
     * @param string $version Version string
     * @return integer
     * 
     */
    public static function compareVersion($version)
    {
        return version_compare($version, strtolower(self::VERSION));
    }
}