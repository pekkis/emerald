<?php
/**
 * Prototype proof-of-concept test plugin for video versioning
 *
 * @package Emerald_Filelib
 * @author pekkis
 * @todo This is just a proof a concept
 *
 */
class Emerald_Filelib_Plugin_Video_FlashifyPlugin
extends Emerald_Filelib_Plugin_VersionProvider_AbstractVersionProvider
{
    protected $_providesFor = array('video', 'application');

    public function createVersion(Emerald\Filelib\FileItem $file)
    {

        $path = $file->getPath() . '/' . $this->getIdentifier();

        if(!is_dir($path)) {
            mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
        }
        	
        $exec_string = "/usr/bin/ffmpeg -i {$file->getPathname()} -f flv {$path}/{$file->id}";
         
        exec($exec_string); //where exxc is the command used to execute shell comma


    }


}
?>