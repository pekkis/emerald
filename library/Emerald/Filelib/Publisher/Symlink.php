<?php
class Emerald_Filelib_Publisher_Symlink extends Emerald_Filelib_Publisher_Filesystem implements Emerald_Filelib_Publisher_PublisherInterface
{
    
    
    /**
     * @var string Relative path from public to private root
     */
    private $_relativePathToRoot;
    
    
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
                
        $path = $this->getFilelib()->getStorage()->getRoot() . '/' . $this->getFilelib()->getStorage()->getDirectoryId($file->id) . '/' . $file->id;
        
        $path = substr($path, strlen($this->getFilelib()->getStorage()->getRoot()));
        $sltr = $sltr . $path;
        
        return $sltr;
    }
    
    
    
    public function publish(Emerald_Filelib_FileItem $file)
    {
        
        $fl = $this->getFilelib();
        $linker = $file->getProfileObject()->getLinker();
        
        $link = $this->getPublicRoot() . '/' . $linker->getLink($file, true);
        
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
        $file->getProfileObject()->getLinker()->deleteSymlink($file);
        $file->getProfileObject()->getLinker()->createSymlink($file);
        */
        
    }
    
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $fl = $this->getFilelib();
            
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLinkVersion($file, $version);
        
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
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLink($file);
        if(is_link($link)) {
            unlink($link);
        }
        
    }
    

    
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLinkVersion($file, $version);

        if(is_link($link)) {
            unlink($link);
        }
        
    }
    
    
    
    
    
}

