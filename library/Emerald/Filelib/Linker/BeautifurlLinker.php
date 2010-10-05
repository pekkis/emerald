<?php
/**
 * Creates beautifurls(tm) from the virtual directory structure and names.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Linker_BeautifurlLinker extends Emerald_Filelib_Linker_AbstractLinker implements Emerald_Filelib_Linker_LinkerInterface
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



    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version)
    {
        $link = $this->getLink($file);
        $pinfo = pathinfo($link);
        $link = ($pinfo['dirname'] === '.' ? '' : $pinfo['dirname'] . '/') . $pinfo['filename'] . '-' . $version->getIdentifier();
        $link .= '.' . $version->getExtension();
        
        return $link;
    }


    public function getLink(Emerald_Filelib_FileItem $file, $force = false)
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

        return $file->link;

    }

    
    


}
