<?php
/**
 * Stores the version identifiers of Emerald components
 *
 * @package Emerald_Version
 * @author pekkis
 * @id $Id$
 *
 */
final class Emerald_Version
{
    const VERSION = '3.0.0-beta4';

    const JQUERY_VERSION = '1.4.2';

    const JQUERY_UI_VERSION = '1.8.2';

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
