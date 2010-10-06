<?php

namespace Emerald\Filelib;

/**
 * Operates on files
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FileOperator
{

    /**
     * @var \Zend_Cache_Core
     */
    protected $_cache;

    /**
     * @var string
     */
    protected $_cachePrefix = 'emerald_filelib_fileoperator';

    /**
     * @var \Emerald\Filelib\Uploader
     */
    protected $_uploader;    
        
    /**
     * Returns uploader
     * 
     * @return \Emerald\Filelib\Uploader
     */
    public function getUploader()
    {
        if(!$this->_uploader) {
        	$this->_uploader = new \Emerald\Filelib\Uploader();
        }
        return $this->_uploader;
    }
    
    /**
     * Sets uploader
     * 
     * @param \Emerald\Filelib\Uploader $uploader
     * @return \Emerald\Filelib\FileOperator
     */
    public function setUploader(\Emerald\Filelib\Uploader $uploader)
    {
    	$this->_uploader = $uploader;
    	return $this;
    }
    
    /**
     * Returns cache
     * 
     * @return \Zend_Cache_Core
     */
    public function getCache()
    {
        if(!$this->_cache) {
            $this->_cache = $this->getFilelib()->getCache();
        }
        return $this->_cache;
    }


    /**
     * Returns cache identifier
     * 
     * @param mixed $id Id
     * @return string
     */
    public function getCacheIdentifier($id)
    {
        if(is_array($id)) {
            $id = implode('_', $id);
        }
        return $this->_cachePrefix . '_' . $id;
    }


    /**
     * Tries to load file from cache, returns object on success.
     * 
     * @param mixed $id
     * @return mixed 
     */
    public function findCached($id) {
        return $this->getCache()->load($this->getCacheIdentifier($id));
    }


    /**
     * Clears cache for id
     * 
     * @param mixed $id
     */
    public function clearCached($id)
    {
        $this->getCache()->remove($this->getCacheIdentifier($id));
    }


    /**
     * Stores file to cache
     * 
     * @param mixed $id
     * @param mixed $data
     */
    public function storeCached($id, $data)
    {
        $this->getCache()->save($data, $this->getCacheIdentifier($id));
    }

    /**
     * Returns backend
     *
     * @return \Emerald\Filelib\Backend\BackendInterface
     */
    public function getBackend()
    {
        return $this->_backend;
    }


    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }


    public function __construct(\Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
        $this->_backend = $filelib->getBackend();
    }



    /**
     * Updates a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @return unknown_type
     */
    public function update(\Emerald\Filelib\FileItem $file)
    {
        $this->unpublish($file);        
        // $file->getProfileObject()->getLinker()->deleteSymlink($file);
        
        $this->getBackend()->updateFile($file);
        $this->storeCached($file->id, $file);

        if($this->isAnonymous($file)) {
            $this->publish($file);
            //$file->getProfileObject()->getLinker()->createSymlink($file);
        }

        $this->storeCached($file->id, $file);
        
        return $this;

    }


    /**
     * Finds a file
     *
     * @param mixed $idFile File id
     * @return \Emerald\Filelib\FileItem
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
     * @return \Emerald\Filelib\FileItemIterator
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
     * @param \Emerald\Filelib\FileItem $file File
     * @return boolean
     */
    public function isAnonymous(\Emerald\Filelib\FileItem $file)
    {
        return $this->getFilelib()->getAcl()->isAnonymousReadable($file);

    }


    /**
     * Gets a new upload
     *
     * @param string $path Path to upload file
     * @return \Emerald\Filelib\FileUpload
     */
    public function prepareUpload($path)
    {
        $upload = new \Emerald\Filelib\FileUpload($path);
        $upload->setFilelib($this->getFilelib());
        return $upload;
    }


    /**
     * Uploads file to filelib.
     *
     * @param mixed $upload Uploadable, path or object
     * @param \Emerald\Filelib\FolderItem $folder
     * @return \Emerald\Filelib\FileItem
     * @throws \Emerald\Filelib\FilelibException
     */
    public function upload($upload, $folder, $profile = 'default')
    {
        if(!$upload instanceof \Emerald\Filelib\FileUpload) {
            $upload = $this->prepareUpload($upload);
        }

        
        if(!$this->getFilelib()->getAcl()->isWriteable($folder)) {
            throw new \Emerald\Filelib\FilelibException("Folder '{$folder->id}'not writeable");
        }

        if(!$this->getUploader()->isAccepted($upload)) {
            throw new \Emerald\Filelib\FilelibException("Can not upload");
        }

        $profile = $this->getFilelib()->getProfile($profile);
        foreach($profile->getPlugins() as $plugin) {
            $upload = $plugin->beforeUpload($upload);
        }

        $file = $this->getBackend()->upload($upload, $folder, $profile);

        $file->setFilelib($this->getFilelib());
        $file->setProfileObject($profile);
        	
        if(!$file) {
            throw new \Emerald\Filelib\FilelibException("Can not upload");
        }
        
        try {
            
            $this->getFilelib()->getStorage()->store($upload, $file);

            foreach($file->getProfileObject()->getPlugins() as $plugin) {
                $upload = $plugin->afterUpload($file);
            }

            if($this->getFilelib()->getAcl()->isAnonymousReadable($file)) {
                $this->publish($file);
            }
            
            
        } catch(Exception $e) {
            // Maybe log here?
            throw $e;
        }


        return $file;
    }


    /**
     * Deletes a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @throws \Emerald\Filelib\FilelibException
     */
    public function delete(\Emerald\Filelib\FileItem $file)
    {
        try {

            $this->unpublish($file);
            
            $this->getBackend()->deleteFile($file);
            $this->clearCached($file->id);
            $this->getFilelib()->getStorage()->delete($file);

            foreach($file->getProfileObject()->getPlugins() as $plugin) {
                if($plugin instanceof \Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface && $plugin->providesFor($file)) {
                    $plugin->onDelete($file);
                }
            }
            	
            return true;
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }


    /**
     * Returns file type of a file
     *
     * @param \Emerald\Filelib\FileItem File $file item
     * @return string File type
     */
    public function getType(\Emerald\Filelib\FileItem $file)
    {
        // @todo Semi-mock until mimetype database is pooped in.
        $split = explode('/', $file->mimetype);
        return $split[0];
    }


    /**
     * Returns whether a file has a certain version
     *
     * @param \Emerald\Filelib\FileItem $file File item
     * @param string $version Version
     * @return boolean
     */
    public function hasVersion(\Emerald\Filelib\FileItem $file, $version)
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
     * @param \Emerald\Filelib\FileItem $file File item
     * @param string $version Version
     * @return object Provider
     */
    public function getVersionProvider(\Emerald\Filelib\FileItem $file, $version)
    {
        return $file->getProfileObject()->getVersionProvider($file, $version);
    }


    /**
     * Renders a file's path
     *
     * @param \Emerald\Filelib\FileItem $file
     * @param array $opts Options
     * @return string File path
     */
    public function renderPath(\Emerald\Filelib\FileItem $file, $opts = array())
    {
        if(isset($opts['version'])) {

            $version = $opts['version'];
            	
            if($this->hasVersion($file, $version)) {
                $provider = $this->getVersionProvider($file, $version);
                $path = $provider->getRenderPath($file);
            } else {
                throw new \Emerald\Filelib\FilelibException("Version '{$version}' is not available");
            }
        } else {
            $path = $file->getRenderPath();
        }

        return $path;

    }



    /**
     * Renders a file to a response
     *
     * @param \Emerald_Filelib File $file item
     * @param \Zend_Controller_Response_Http $response Response
     * @param array $opts Options
     */
    public function render(\Emerald\Filelib\FileItem $file, \Zend_Controller_Response_Http $response, $opts = array())
    {
        $path = $this->renderPath($file, $opts);
        if($this->getFilelib()->getAcl()->isAnonymousReadable($file)) {
            return $response->setRedirect($path, 302);
        }

        if(!$this->getFilelib()->getAcl()->isReadable($file)) {
            throw new \Emerald\Filelib\FilelibException('Not readable', 404);
        }



        if(isset($opts['download'])) {
            $response->setHeader('Content-disposition', "attachment; filename={$file->name}");
        }

        if(!is_readable($path)) {
            throw new \Emerald\Filelib\FilelibException('File not readable');
        }

        $response->setHeader('Content-Type', $file->mimetype);

        readfile($path);

    }

    
    public function publish(\Emerald\Filelib\FileItem $file)
    {
                        
        $this->getFilelib()->getPublisher()->publish($file);
        foreach($file->getProfileObject()->getPlugins() as $plugin) {
            $plugin->onPublish($file);
        }
    }
    
    public function unpublish(\Emerald\Filelib\FileItem $file)
    {
        $this->getFilelib()->getPublisher()->unpublish($file);
        foreach($file->getProfileObject()->getPlugins() as $plugin) {
            $plugin->onUnpublish($file);
        }
        
    }
    

}