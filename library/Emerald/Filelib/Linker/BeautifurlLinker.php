<?php

namespace Emerald\Filelib\Linker;

/**
 * Creates beautifurls(tm) from the virtual directory structure and names.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class BeautifurlLinker extends \Emerald\Filelib\Linker\AbstractLinker implements \Emerald\Filelib\Linker\Linker
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



    public function getLinkVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $link = $this->getLink($file);
        $pinfo = pathinfo($link);
        $link = ($pinfo['dirname'] === '.' ? '' : $pinfo['dirname'] . '/') . $pinfo['filename'] . '-' . $version->getIdentifier();
        $link .= '.' . $version->getExtension();
        
        return $link;
    }


    public function getLink(\Emerald\Filelib\FileItem $file, $force = false)
    {
        if($force || !isset($file->link)) {
            	
            $folders = array();
            $folders[] = $folder = $file->getFilelib()->folder()->find($file->getFolderId());
            	
            while($folder = $folder->getFilelib()->folder()->find($folder->getParentId())) {
                array_unshift($folders, $folder);
            }

            $beautifurl = array();
            	
            foreach($folders as $folder) {
                $beautifurl[] = $folder->getName();
            }
            	
            $beautifurl[] = $file->getName();

            if($this->getExcludeRoot()) {
                array_shift($beautifurl);
            }

            $beautifurl = implode(DIRECTORY_SEPARATOR, $beautifurl);
           
            $file->link = $beautifurl;

        }

        return $file->link;

    }

    
    


}
