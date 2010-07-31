<?php
class Emerald_Filelib_FileOperator
{

    protected $_cache;

    protected $_cachePrefix = 'emerald_filelib_fileoperator';

    /**
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        if(!$this->_cache) {
            $this->_cache = $this->getFilelib()->getCache();
        }
        return $this->_cache;
    }


    public function getCacheIdentifier($id)
    {
        if(is_array($id)) {
            $id = implode('_', $id);
        }
        return $this->_cachePrefix . '_' . $id;
    }


    public function findCached($id) {
        return $this->getCache()->load($this->getCacheIdentifier($id));
    }


    public function clearCached($id)
    {
        $this->getCache()->remove($this->getCacheIdentifier($id));
    }


    public function storeCached($id, $data)
    {
        $this->getCache()->save($data, $this->getCacheIdentifier($id));
    }



    /**
     * Returns backend
     *
     * @return Emerald_Filelib_Backend_Interface
     */
    public function getBackend()
    {
        return $this->_backend;
    }


    /**
     * Returns filelib
     *
     * @return Emerald_Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }


    public function __construct(Emerald_Filelib $filelib)
    {
        $this->_filelib = $filelib;
        $this->_backend = $filelib->getBackend();
    }



    /**
     * Updates a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @return unknown_type
     */
    public function update(Emerald_Filelib_FileItem $file)
    {
        $file->getProfileObject()->getSymlinker()->deleteSymlink($file);
        $this->getBackend()->updateFile($file);
        $this->storeCached($file->id, $file);

        if($this->isAnonymous($file)) {
            $file->getProfileObject()->getSymlinker()->createSymlink($file);
        }

        $this->storeCached($file->id, $file);

    }


    /**
     * Finds a file
     *
     * @param mixed $idFile File id
     * @return Emerald_Filelib_FileItem
     */
    public function find($id)
    {
        if(!$file = $this->findCached($id)) {
            $file = $this->getBackend()->findFile($id);
            	
            if($file) {
                $this->storeCached($file->id, $file);
            }
        }

        if(!$file) {
            return false;
        }
        $file->setFilelib($this->getFilelib());
        $file->setProfileObject($this->getFilelib()->getProfile($file->profile));
        return $file;

    }

    /**
     * Finds and returns all files
     *
     * @return Emerald_Filelib_FileItemIterator
     */
    public function findAll()
    {
        $items = $this->getBackend()->findAllFiles();
        foreach($items as $item) {
            $item->setFilelib($this->getFilelib());
            $item->setProfileObject($this->getFilelib()->getProfile($item->profile));
        }
        return $items;
    }




    /**
     * Returns whether a file is anonymous
     *
     * @todo This is still mock!
     * @param Emerald_Filelib_FileItem $file File
     * @return boolean
     */
    public function isAnonymous(Emerald_Filelib_FileItem $file)
    {
        return $this->getFilelib()->getAcl()->isAnonymousReadable($file);

    }


    /**
     * Gets a new upload
     *
     * @param string $path Path to upload file
     * @return Emerald_Filelib_FileUpload
     */
    public function prepareUpload($path)
    {
        $upload = new Emerald_Filelib_FileUpload($path);
        $upload->setFilelib($this->getFilelib());
        return $upload;
    }


    /**
     * Uploads file to filelib.
     *
     * @param mixed $upload Uploadable, path or object
     * @param Emerald_Filelib_FolderItem $folder
     * @return Emerald_Filelib_FileItem
     * @throws Emerald_Filelib_Exception
     */
    public function upload($upload, $folder, $profile = 'default')
    {
        if(!$upload instanceof Emerald_Filelib_FileUpload) {
            $upload = $this->prepareUpload($upload);
        }

        if(!$this->getFilelib()->getAcl()->isWriteable($folder)) {
            throw new Emerald_Filelib_Exception("Folder '{$folder->id}'not writeable");
        }

        if(!$upload->canUpload()) {
            throw new Emerald_Filelib_Exception("Can not upload");
        }

        $profile = $this->getFilelib()->getProfile($profile);
        foreach($profile->getPlugins() as $plugin) {
            $upload = $plugin->beforeUpload($upload);
        }

        $file = $this->getBackend()->upload($upload, $folder, $profile);

        $file->setFilelib($this->getFilelib());
        $file->setProfileObject($profile);
        	
        if(!$file) {
            throw new Emerald_Filelib_Exception("Can not upload");
        }


        try {
            $root = $this->getFilelib()->getRoot();
            $dir = $root . '/' . $this->getFilelib()->getDirectoryId($file->id);

            if(!is_dir($dir)) {
                @mkdir($dir, $this->getFilelib()->getDirectoryPermission(), true);
            }

            if(!is_dir($dir) || !is_writable($dir)) {
                throw new Emerald_Filelib_Exception('Could not write into directory', 500);
            }
            	
            $fileTarget = $dir . '/' . $file->id;

            copy($upload->getRealPath(), $fileTarget);
            chmod($fileTarget, $this->getFilelib()->getFilePermission());
            	
            if(!is_readable($fileTarget)) {
                throw new Emerald_Filelib_Exception('Could not copy file to folder');
            }

        } catch(Exception $e) {
            // Maybe log here?
            throw $e;
        }

        foreach($file->getProfileObject()->getPlugins() as $plugin) {
            $upload = $plugin->afterUpload($file);
        }

        if($this->getFilelib()->getAcl()->isAnonymousReadable($file)) {
            $file->getProfileObject()->getSymlinker()->deleteSymlink($file);
            $file->getProfileObject()->getSymlinker()->createSymlink($file);
        }

        return $file;
    }


    /**
     * Deletes a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @throws Emerald_Filelib_Exception
     */
    public function delete(Emerald_Filelib_FileItem $file)
    {
        try {

            $this->getBackend()->deleteFile($file);
            $this->clearCached($file->id);
            	
            $file->getProfileObject()->getSymlinker()->deleteSymlink($file);
            foreach($file->getProfileObject()->getPlugins() as $plugin) {
                if($plugin instanceof Emerald_Filelib_Plugin_VersionProvider_Interface && $plugin->providesFor($file)) {
                    $plugin->deleteVersion($file);
                }
            }
            	
            $path = $this->getFilelib()->getRoot() . '/' . $this->getFilelib()->getDirectoryId($file->id) . '/' . $file->id;
            	
            $fileObj = new SplFileObject($path);
            if(!$fileObj->isFile() || !$fileObj->isWritable()) {
                throw new Emerald_Filelib_Exception('Can not delete file');
            }
            	
            if(!@unlink($fileObj->getPathname())) {
                throw new Emerald_Filelib_Exception('Can not delete file');
            }
            	
            return true;
            	
        } catch(Exception $e) {
            throw new Emerald_Filelib_Exception($e->getMessage());
        }

    }


    /**
     * Returns file type of a file
     *
     * @param Emerald_Filelib_FileItem File $file item
     * @return string File type
     */
    public function getType(Emerald_Filelib_FileItem $file)
    {
        // @todo Semi-mock until mimetype database is pooped in.
        $split = explode('/', $file->mimetype);
        return $split[0];
    }


    /**
     * Returns whether a file has a certain version
     *
     * @param Emerald_Filelib_FileItem $file File item
     * @param string $version Version
     * @return boolean
     */
    public function hasVersion(Emerald_Filelib_FileItem $file, $version)
    {
        $filetype = $this->getType($file);
        $profile = $file->getProfileObject();
        if($profile->fileHasVersion($file, $version)) {
            return true;
        }
        return false;
    }


    /**
     * Returns version provider for a file/version
     *
     * @param Emerald_Filelib_FileItem $file File item
     * @param string $version Version
     * @return object Provider
     */
    public function getVersionProvider(Emerald_Filelib_FileItem $file, $version)
    {
        return $file->getProfileObject()->getVersionProvider($file, $version);
    }


    /**
     * Renders a file's path
     *
     * @param Emerald_Filelib_FileItem $file
     * @param array $opts Options
     * @return string File path
     */
    public function renderPath(Emerald_Filelib_FileItem $file, $opts = array())
    {
        if(isset($opts['version'])) {

            $version = $opts['version'];
            	
            if($this->hasVersion($file, $version)) {
                $provider = $this->getVersionProvider($file, $version);
                $path = $provider->getRenderPath($file);
            } else {
                throw new Emerald_Filelib_Exception("Version '{$version}' is not available");
            }
        } else {
            $path = $file->getRenderPath();
        }

        return $path;

    }



    /**
     * Renders a file to a response
     *
     * @param Emerald_Filelib File $file item
     * @param Zend_Controller_Response_Http $response Response
     * @param array $opts Options
     */
    public function render(Emerald_Filelib_FileItem $file, Zend_Controller_Response_Http $response, $opts = array())
    {
        $path = $this->renderPath($file, $opts);

        if($this->getFilelib()->getAcl()->isAnonymousReadable($file)) {
            return $response->setRedirect($path, 302);
        }

        if(!$this->getFilelib()->getAcl()->isReadable($file)) {
            throw new Emerald_Filelib_Exception('Not readable', 404);
        }



        if(isset($opts['download'])) {
            $response->setHeader('Content-disposition', "attachment; filename={$file->name}");
        }

        if(!is_readable($path)) {
            throw new Emerald_Filelib_Exception('File not readable');
        }

        $response->setHeader('Content-Type', $file->mimetype);

        readfile($path);

    }




}