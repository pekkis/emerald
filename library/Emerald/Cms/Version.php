<?php
/**
 * Stores the version identifiers of different Emerald components
 *
 * @package Emerald_Cms_Version
 * @author pekkis
 * @id $Id$
 *
 */
final class Emerald_Cms_Version
{
    const VERSION = '3.0.0-milestone1';

    const JQUERY_VERSION = '1.4.2';

    const JQUERY_UI_VERSION = '1.8.5';

    /**
     * Compare the specified Emerald version string $version
     * with the current version of Emerald.
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