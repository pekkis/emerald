<?php
/**
 * This symlinker creates magnificent beautifurls from the directory names
 * and structure.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Symlinker_Beautifurl
extends Emerald_Filelib_Symlinker_Abstract
implements Emerald_Filelib_Symlinker_Interface
{

    /**
     * @var boolean Exclude root folder from beautifurls or not
     */
    private $_excludeRoot = false;

    /**
     * Sets whether the root folder is excluded from beautifurls.
     *
     * @param boolean $excludeRoot
     */
    public function setExcludeRoot($excludeRoot)
    {
        $this->_excludeRoot = $excludeRoot;
    }


    /**
     * Returns whether the root folder is to be excluded from beautifurls.
     *
     * @return unknown_type
     */
    public function getExcludeRoot()
    {
        return $this->_excludeRoot;
    }



    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $prefix = true)
    {
        $link = $this->getLink($file, $prefix);
        $pinfo = pathinfo($link);
        $link = ($pinfo['dirname'] === '.' ? '' : $pinfo['dirname'] . '/') . $pinfo['filename'] . '-' . $version->getIdentifier();
        $link .= '.' . $version->getExtension();
        
        return $link;
    }


    public function getLink(Emerald_Filelib_FileItem $file, $prefix = true, $force = false)
    {
        if($force || !isset($file->link)) {
            	
            $folders = array();
            $folders[] = $folder = $file->findFolder();
            	
            while($folder = $folder->findParent()) {
                array_unshift($folders, $folder);
            }

            $beautifurl = array();
            	
            foreach($folders as $folder) {
                $beautifurl[] = $folder->name;
            }
            	
            $beautifurl[] = $file->name;

            if($this->getExcludeRoot()) {
                array_shift($beautifurl);
            }

            $beautifurl = implode(DIRECTORY_SEPARATOR, $beautifurl);
           
            $file->link = $beautifurl;

        }
        	
        /*
        if($prefix) {
            return $this->getFilelib()->getPublicRoot() . '/' . $file->link;
        }
        */

        return $file->link;

    }



}
