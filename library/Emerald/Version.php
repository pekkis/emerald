<?php
/**
 * Class to store and retrieve the version of Emerald.
 *
 * @package Emerald_Version
 * @author pekkis
 * @id $Id$
 * 
 */
final class Emerald_Version
{
    /**
     * Emerald version
     */
    const VERSION = '3.0.0-alpha';

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
    
    
    public static function getVersionNumber()
    {
    	$refle = new ReflectionClass('Emerald_Version');
    	
    	$doc = $refle->getDocComment();
    	preg_match("/Version\.php (\d{1,4}) /i", $doc, $match);
    	
    	return $match[1];
    
    }
}
