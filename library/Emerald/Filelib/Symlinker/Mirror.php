<?php
/**
 * Mirror symlinker mirrors the private root directory structure, creating
 * semi-fugly urls.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Symlinker_Mirror
extends Emerald_Filelib_Symlinker_Abstract
implements Emerald_Filelib_Symlinker_Interface
{

    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $link = $this->getLink($file, $prefix);

        $pinfo = pathinfo($link);
        $link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $version->getIdentifier();
        $link .= '.' . $version->getExtension();

        return $link;
    }


    public function getLink(Emerald_Filelib_FileItem $file)
    {
        $url = array();
        $url[] = $this->getFilelib()->getDirectoryId($file->id);

        $pinfo = pathinfo($file->name);

        $name = $file->name;

        $url[] = $name;

        $url = implode(DIRECTORY_SEPARATOR, $url);

        return $url;

    }


}
