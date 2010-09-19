<?php
/**
 * Sequential linker creates a sequential link with n levels of directories and m files per directory
 *
 * @package Emerald_Filelib
 * @author pekkis
 * @author Petri Mahanen
 *
 */
class Emerald_Filelib_Linker_Mirror extends Emerald_Filelib_Linker_Abstract implements Emerald_Filelib_Linker_Interface
{

    /**
     * @var integer Files per directory
     */
    private $_filesPerDirectory = 500;

    /**
     * @var integer Levels in directory structure
     */
    private $_directoryLevels = 1;
    
    /**
     * Sets files per directory
     *
     * @param integer $filesPerDirectory
     * @return Emerald_Filelib_Linker_Mirror
     */
    public function setFilesPerDirectory($filesPerDirectory)
    {
        $this->_filesPerDirectory = $filesPerDirectory;
        return $this;
    }

    /**
     * Returns files per directory
     *
     * @return integer
     */
    public function getFilesPerDirectory()
    {
        return $this->_filesPerDirectory;
    }

    /**
     * Sets levels per directory hierarchy
     *
     * @param integer $directoryLevels
     * @return Emerald_Filelib_Linker_Mirror
     */
    public function setDirectoryLevels($directoryLevels)
    {
        $this->_directoryLevels = $directoryLevels;
        return $this;
    }

    /**
     * Returns levels in directory hierarchy
     *
     * @return integer
     */
    public function getDirectoryLevels()
    {
        return $this->_directoryLevels;
    }
    
    
    
    /**
     * Returns directory path for specified file id
     *
     * @param integer $fileId File id
     * @return string
     */
    public function getDirectoryId($fileId)
    {
        $directoryLevels = $this->getDirectoryLevels() + 1;
        $filesPerDirectory = $this->getFilesPerDirectory();

        if($directoryLevels < 1) {
            throw new Emerald_Filelib_Exception("Invalid number of directory levels ('{$directoryLevels}')");
        }

        $arr = array();
        $tmpfileid = $fileId - 1;

        for($count = 1; $count <= $directoryLevels; ++$count) {
            $lus = $tmpfileid / pow($filesPerDirectory, $directoryLevels - $count);
            $tmpfileid = $tmpfileid % pow($filesPerDirectory, $directoryLevels - $count);
            $arr[] = floor($lus) + 1;
        }

        $puuppa = array_pop($arr);
        return implode(DIRECTORY_SEPARATOR, $arr);

    }
    
    
    
    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $link = $this->getLink($file);

        $pinfo = pathinfo($link);
        $link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $version->getIdentifier();
        $link .= '.' . $version->getExtension();

        return $link;
    }


    public function getLink(Emerald_Filelib_FileItem $file)
    {
        $url = array();
        $url[] = $this->getDirectoryId($file->id);
        $pinfo = pathinfo($file->name);
        $name = $file->name;
        $url[] = $name;
        $url = implode(DIRECTORY_SEPARATOR, $url);
        return $url;
    }


    
}
