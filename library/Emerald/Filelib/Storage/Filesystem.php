<?php
class Emerald_Filelib_Storage_Filesystem extends Emerald_Filelib_Storage_Abstract implements Emerald_Filelib_Storage_StorageInterface
{
    

    /**
     * @var string Physical root
     */
    private $_root;

    /**
     * @var string Physical public root
     */
    private $_publicRoot;

    /**
     * @var string Public root prefix from web root.
     */
    private $_publicDirectoryPrefix = '';
    
    /**
     * @var string Relative path from public to private root
     */
    private $_relativePathToRoot;

    /**
     * @var integer Files per directory
     */
    private $_filesPerDirectory = 500;

    /**
     * @var integer Levels in directory structure
     */
    private $_directoryLevels = 1;

    /**
     * @var integer Octal representation for directory permissions
     */
    private $_directoryPermission = 0700;

    /**
     * @var integer Octal representation for file permissions
     */
    private $_filePermission = 0600;
    
       
    /**
     * Sets symbolic link from public to private root
     *
     * @param string $relativePathToRoot
     * @return Emerald_Filelib
     */
    public function setRelativePathToRoot($relativePathToRoot)
    {
        $this->_relativePathToRoot = $relativePathToRoot;
        return $this;
    }


    /**
     * Returns symbolic link from public to private root
     *
     * @return string
     */
    public function getRelativePathToRoot()
    {
        return $this->_relativePathToRoot;
    }

    /**
     * Sets files per directory
     *
     * @param integer $filesPerDirectory
     * @return Emerald_Filelib
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
     * @return Emerald_Filelib
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
     * Sets directory permission
     *
     * @param integer $directoryPermission
     * @return Emerald_Filelib Filelib
     */
    public function setDirectoryPermission($directoryPermission)
    {
        $this->_directoryPermission = octdec($directoryPermission);
        return $this;
    }


    /**
     * Returns directory permission
     *
     * @return integer
     */
    public function getDirectoryPermission()
    {
        return $this->_directoryPermission;
    }

    /**
     * Sets file permission
     *
     * @param integer $filePermission
     * @return Emerald_Filelib Filelib
     */
    public function setFilePermission($filePermission)
    {
        $this->_filePermission = octdec($filePermission);
        return $this;
    }

    /**
     * Returns file permission
     *
     * @return integer
     */
    public function getFilePermission()
    {
        return $this->_filePermission;
    }



    /**
     * Returns directory identifier (path) for specified file id
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

    /**
     * Sets root
     *
     * @param string $root
     * @return Emerald_Filelib Filelib
     */
    public function setRoot($root)
    {
        $this->_root = $root;
    }


    /**
     * Returns root
     *
     * @return string
     */
    public function getRoot()
    {
        return $this->_root;
    }


    /**
     * Sets web access prefix
     *
     * @param string $publicDirectoryPrefix
     * @return Emerald_Filelib Filelib
     */
    public function setPublicDirectoryPrefix($publicDirectoryPrefix)
    {
        $this->_publicDirectoryPrefix = $publicDirectoryPrefix;
        return $this;
    }


    /**
     * Returns web access prefix
     *
     * @return string
     */
    public function getPublicDirectoryPrefix()
    {
        return $this->_publicDirectoryPrefix;
    }


    /**
     * Sets public root
     *
     * @param string $publicRoot
     * @return Emerald_Filelib Filelib
     */
    public function setPublicRoot($publicRoot)
    {
        $this->_publicRoot = $publicRoot;
        return $this;
    }


    /**
     * Returns public root
     *
     * @return string
     */
    public function getPublicRoot()
    {
        return $this->_publicRoot;
    }
    
    
    
    public function store(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FileItem $file)
    {
        $root = $this->getRoot();
        $dir = $root . '/' . $this->getDirectoryId($file->id);

        if(!is_dir($dir)) {
            @mkdir($dir, $this->getDirectoryPermission(), true);
        }

        if(!is_dir($dir) || !is_writable($dir)) {
            throw new Emerald_Filelib_Exception('Could not write into directory', 500);
        }
            
        $fileTarget = $dir . '/' . $file->id;

        copy($upload->getRealPath(), $fileTarget);
        chmod($fileTarget, $this->getFilePermission());
            
        if(!is_readable($fileTarget)) {
            throw new Emerald_Filelib_Exception('Could not copy file to folder');
        }
        
        
                
    }
    
    
    
    
    public function publish(Emerald_Filelib_FileItem $file)
    {
        
        $fl = $this->getFilelib();
        $symlinker = $file->getProfileObject()->getSymlinker();
        
        $link = $this->getPublicRoot() . '/' . $symlinker->getLink($file, true, true);
        
        if(!is_link($link)) {
            $path = dirname($link);
                
            if(!is_dir($path)) {
                mkdir($path, $this->getDirectoryPermission(), true);
            }
                
            if($this->getRelativePathToRoot()) {

                $path2 = substr($path, strlen($this->getPublicRoot()) + 1);

                // If the link goes to the root dir, $path2 is false and fuxors the depth without a check.
                if($path2 === false) {
                    $depth = 0;
                } else {
                    $depth = sizeof(explode(DIRECTORY_SEPARATOR, $path2));
                }

                // Relative linking requires some movin'n groovin.
                $oldCwd = getcwd();
                chdir($path);
                symlink($this->getRelativePathTo($file, $depth), $link);
                chdir($oldCwd);
            } else {
                symlink($file->getPathname(), $link);
            }
        }

        /*
        $file->getProfileObject()->getSymlinker()->deleteSymlink($file);
        $file->getProfileObject()->getSymlinker()->createSymlink($file);
        */
        
    }
    
    /**
     * Returns relative link from the public to private root
     *
     * @param Emerald_Filelib_File $file File item
     * @param $levelsDown How many levels down from root
     * @return string
     */
    public function getRelativePathTo(Emerald_Filelib_FileItem $file, $levelsDown = 0)
    {
        $sltr = $this->getRelativePathToRoot();
        
        if(!$sltr) {
            throw new Emerald_Filelib_Exception('Relative path must be set!');
        }
        $sltr = str_repeat("../", $levelsDown) . $sltr;
                
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $file->id;
        
        $path = substr($path, strlen($this->getRoot()));
        $sltr = $sltr . $path;
        
        return $sltr;
    }
    
    
        /**
     * Returns relative link from the public to private root
     *
     * @param Emerald_Filelib_File $file File item
     * @param $levelsDown How many levels down from root
     * @return string
     */
    public function dddgetRelativePathTo(Emerald_Filelib_FileItem $file, $levelsDown = 0)
    {
        $fl = $this->getFilelib();

        $sltr = $fl->getRelativePathToRoot();

        if(!$sltr) {
            throw new Emerald_Filelib_Exception('Relative path must be set!');
        }

        $sltr = str_repeat("../", $levelsDown) . $sltr;

        $path = $file->getPathname();
        $path = substr($path, strlen($fl->getRoot()));

        $sltr = $sltr . $path;

        return $sltr;

    }
    

    
    
    public function storeVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $tempFile)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $version->getIdentifier();
                 
        if(!is_dir($path)) {
            mkdir($path, $this->getDirectoryPermission(), true);
        }
                 
        copy($tempFile, $path . '/' . $file->id);
        
    }
    
    
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $fl = $this->getFilelib();
            
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getSymlinker()->getLinkVersion($file, $version, true);
        
        if(!is_link($link)) {

            $path = dirname($link);
            if(!is_dir($path)) {
                mkdir($path, $this->getDirectoryPermission(), true);
            }

            if($this->getRelativePathToRoot()) {

                // Relative linking requires some movin'n groovin.
                $oldCwd = getcwd();
                chdir($path);
                    
                $path2 = substr($path, strlen($this->getPublicRoot()) + 1);
                    
                // If the link goes to the root dir, $path2 is false and fuxors the depth without a check.
                if($path2 === false) {
                    $depth = 0;
                } else {
                    $depth = sizeof(explode(DIRECTORY_SEPARATOR, $path2));
                }
                
                $fp = dirname($this->getRelativePathTo($file, $depth));
                $fp .= '/' . $version->getIdentifier() . '/' . $file->id;
                
                symlink($fp, $link);
                    
                chdir($oldCwd);

            } else {
                
                die('do this! fix it now!');
                
                
                symlink($file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id, $link);
            }

        }
        
        
    }
    
    
    
    
    public function unpublish(Emerald_Filelib_FileItem $file)
    {
        // $fl = $this->getFilelib();
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getSymlinker()->getLink($file);
        if(is_link($link)) {
            unlink($link);
        }
        
    }
    

    
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getSymlinker()->getLinkVersion($file, $version, true);

        if(is_link($link)) {
            unlink($link);
        }
        
    }
    
    
    public function retrieve(Emerald_Filelib_FileItem $file)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $file->id;
        
        if(!is_file($path)) {
            throw new Emerald_Filelib_Exception('Could not retrieve file');
        }
        
        return new Emerald_FileObject($path);
        
    }
    
    
    public function retrieveVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $version->getIdentifier() . '/' . $file->id;
        
        if(!is_file($path)) {
            throw new Emerald_Filelib_Exception('Could not retrieve file');
        }
        
        return new Emerald_FileObject($path);
        
    }
    
    
    
    public function delete(Emerald_Filelib_FileItem $file)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $file->id;
            
        $fileObj = new SplFileObject($path);
        if(!$fileObj->isFile() || !$fileObj->isWritable()) {
            throw new Emerald_Filelib_Exception('Can not delete file');
        }
        if(!@unlink($fileObj->getPathname())) {
            throw new Emerald_Filelib_Exception('Can not delete file');
        }
    }
    
    
    public function deleteVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $version->getIdentifier() . '/' . $file->id;
        unlink($path);
    }
    
    
}


