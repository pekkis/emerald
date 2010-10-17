<?php

namespace Emerald\Filelib\File;
/**
 * Operates on files
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FileOperator extends \Emerald\Filelib\AbstractOperator
{
    /**
     * @var string
     */
    protected $_cachePrefix = 'emerald_filelib_fileoperator';

    /**
     * @var \Emerald\Filelib\File\Uploader
     */
    protected $_uploader;    
    
    /**
     * @var array Profiles
     */
    private $_profiles = array();
    
    /**
     * @var string Fileitem class
     */
    private $_className = '\Emerald\Filelib\File\FileItem';
    
    
    /**
     * Sets fileitem class
     *
     * @param string $className Class name
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setClass($className)
    {
        $this->_className = $className;
        return $this;
    }


    /**
     * Returns fileitem class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->_className;
    }
    
    
    /**
     * Returns an instance of the currently set folder class
     * 
     * @param array $data Data as array
     */
    public function getInstance($data = array())
    {
        $className = $this->getClass();
        $file = new $className();
        $file->setFilelib($this->getFilelib());
        
        if($data) {
            $file->fromArray($data);   
        }
        return $file;        
    }
    
    /**
     * Adds a file profile
     * 
     * @param \Emerald\Filelib\File\FileProfile $profile
     * @return \Emerald\Filelib\FileLibrary
     */
    public function addProfile(FileProfile $profile)
    {
        $profile->setFilelib($this->getFilelib());

        if(!isset($this->_profiles[$profile->getIdentifier()])) {
            $this->_profiles[$profile->getIdentifier()] = $profile;
        }
        
        return $this;
    }

    /**
     * Returns a file profile
     * 
     * @param string $identifier File profile identifier
     * @throws \Emerald\Filelib\FilelibException
     * @return \Emerald\Filelib\File\FileProfile
     */
    public function getProfile($identifier)
    {
        if(!isset($this->_profiles[$identifier])) {
            throw new FilelibException("File profile '{$identifier}' not found");
        }

        return $this->_profiles[$identifier];
    }

    /**
     * Returns all file profiles
     * 
     * @return array Array of file profiles
     */
    public function getProfiles()
    {
        return $this->_profiles;
    }
    
    
    /**
     * Returns uploader
     * 
     * @return \Emerald\Filelib\File\Uploader
     */
    public function getUploader()
    {
        if(!$this->_uploader) {
        	$this->_uploader = new \Emerald\Filelib\File\Uploader();
        }
        return $this->_uploader;
    }
    
    /**
     * Sets uploader
     * 
     * @param \Emerald\Filelib\File\Uploader $uploader
     * @return \Emerald\Filelib\File\FileOperator
     */
    public function setUploader(\Emerald\Filelib\File\Uploader $uploader)
    {
    	$this->_uploader = $uploader;
    	return $this;
    }

    /**
     * Updates a file
     *
     * @param \Emerald\Filelib\File\File $file
     * @return unknown_type
     */
    public function update(\Emerald\Filelib\File\File $file)
    {
        $this->unpublish($file);        
        
        $this->getBackend()->updateFile($file);
        $this->storeCached($file->getId(), $file);

        if($this->isAnonymousReadable($file)) {
            $this->publish($file);
        }

        $this->storeCached($file->getId(), $file);
        
        return $this;

    }


    /**
     * Finds a file
     *
     * @param mixed $idFile File id
     * @return \Emerald\Filelib\File\File
     */
    public function find($id)
    {
        if(!$file = $this->findCached($id)) {
            $file = $this->getBackend()->findFile($id);
            
            if(!$file) {
                return false;
            }

            $file = $this->_fileItemFromArray($file);
            $this->storeCached($file->getId(), $file);
        }
        
        return $file;

    }

    /**
     * Finds and returns all files
     *
     * @return \Emerald\Filelib\File\FileIterator
     */
    public function findAll()
    {
        $ritems = $this->getBackend()->findAllFiles();

        $items = array();
        foreach($ritems as $ritem) {
            $item = $this->_fileItemFromArray($ritem);
            $items[] = $item;
        }
        return $items;
    }




    /**
     * Returns whether a file is anonymous
     *
     * @todo This is still mock!
     * @param \Emerald\Filelib\File\File $file File
     * @return boolean
     */
    public function isAnonymousReadable(\Emerald\Filelib\File\File $file)
    {
        return $this->getFilelib()->getAcl()->isAnonymousReadable($file);

    }


    /**
     * Gets a new upload
     *
     * @param string $path Path to upload file
     * @return \Emerald\Filelib\File\FileUpload
     */
    public function prepareUpload($path)
    {
        $upload = new \Emerald\Filelib\File\FileUpload($path);
        $upload->setFilelib($this->getFilelib());
        return $upload;
    }

    
    /**
     * Uploads many files at once
     * 
     * @param Iterator $batch Collection of \SplFileInfo objects
     * @return ArrayIterator Collection of uploaded file items
     */
    public function uploadBatch(\Iterator $batch, $folder, $profile = 'default')
    {
        if(!($folder instanceof \Emerald\Filelib\Folder\Folder)) {
            throw new \Emerald\Filelib\FilelibException('Invalid folder supplied for batch upload');
        }
        
        foreach ($batch as $item) {
            if (!($item instanceof \SplFileInfo)) {
                throw new \Emerald\Filelib\FilelibException('Invalid upload detected in batch');
            }
        }
        
                
        $ret = new \ArrayIterator(array());
        foreach ($batch as $item) {
            if($item->isFile()) {
                $upload = $this->prepareUpload($item->getPathname());
                $uploaded = $this->upload($upload, $folder, $profile);
                $ret->append($uploaded);
            }
        }
        
        return $ret;
    }
    
    

    /**
     * Uploads file to filelib.
     *
     * @param mixed $upload Uploadable, path or object
     * @param \Emerald\Filelib\Folder\Folder $folder
     * @return \Emerald\Filelib\File\File
     * @throws \Emerald\Filelib\FilelibException
     */
    public function upload($upload, $folder, $profile = 'default')
    {
        if(!($folder instanceof \Emerald\Filelib\Folder\Folder)) {
            throw new \Emerald\Filelib\FilelibException('Invalid folder supplied for upload');
        }
       
        
        if(!$upload instanceof \Emerald\Filelib\File\FileUpload) {
            $upload = $this->prepareUpload($upload);
        }

        
        if(!$this->getFilelib()->getAcl()->isWriteable($folder)) {
            throw new \Emerald\Filelib\FilelibException("Folder '{$folder->getId()}'not writeable");
        }

        if(!$this->getUploader()->isAccepted($upload)) {
            throw new \Emerald\Filelib\FilelibException("Can not upload");
        }

        $profile = $this->getFilelib()->file()->getProfile($profile);
        foreach($profile->getPlugins() as $plugin) {
            $upload = $plugin->beforeUpload($upload);
        }

        $file = $this->getBackend()->upload($upload, $folder, $profile);
        
        if(!$file) {
            throw new \Emerald\Filelib\FilelibException("Can not upload");
        }
        
        
        $file = $this->_fileItemFromArray($file);
        $file->setLink($profile->getLinker()->getLink($file, true));
        
        $this->getBackend()->updateFile($file);
        
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
     * @param \Emerald\Filelib\File\File $file
     * @throws \Emerald\Filelib\FilelibException
     */
    public function delete(\Emerald\Filelib\File\File $file)
    {
        try {

            $this->unpublish($file);
            
            $this->getBackend()->deleteFile($file);
            $this->clearCached($file->getId());
            $this->getFilelib()->getStorage()->delete($file);

            foreach($file->getProfileObject()->getPlugins() as $plugin) {
                if($plugin instanceof \Emerald\Filelib\Plugin\VersionProvider\VersionProvider && $plugin->providesFor($file)) {
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
     * @param \Emerald\Filelib\File\File File $file item
     * @return string File type
     */
    public function getType(\Emerald\Filelib\File\File $file)
    {
        // @todo Semi-mock until mimetype database is pooped in.
        $split = explode('/', $file->getMimetype());
        return $split[0];
    }


    /**
     * Returns whether a file has a certain version
     *
     * @param \Emerald\Filelib\File\File $file File item
     * @param string $version Version
     * @return boolean
     */
    public function hasVersion(\Emerald\Filelib\File\File $file, $version)
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
     * @param \Emerald\Filelib\File\File $file File item
     * @param string $version Version
     * @return object Provider
     */
    public function getVersionProvider(\Emerald\Filelib\File\File $file, $version)
    {
        return $file->getProfileObject()->getVersionProvider($file, $version);
    }

    
    public function getUrl(\Emerald\Filelib\File\File $file, $opts = array())
    {
        if(isset($opts['version'])) {
            $version = $opts['version'];
            
            if(!$this->hasVersion($file, $version)) {
                throw new \Emerald\Filelib\FilelibException("Version '{$version}' is not available");
            }
            
            $provider = $this->getVersionProvider($file, $version);
            $url = $this->getFilelib()->getPublisher()->getUrlVersion($file, $provider);
                     
        } else {
            $url = $this->getFilelib()->getPublisher()->getUrl($file);
        }
        return $url;
    }
    

    /**
     * Renders a file to a response
     *
     * @param \Emerald_Filelib File $file item
     * @param \Zend_Controller_Response_Http $response Response
     * @param array $opts Options
     */
    public function render(\Emerald\Filelib\File\File $file, $opts = array())
    {
        
        if(!$this->getFilelib()->getAcl()->isReadable($file)) {
            throw new \Emerald\Filelib\FilelibException('Not readable', 404);
        }
        
        if(isset($opts['version'])) {
            $version = $opts['version'];
            if(!$this->hasVersion($file, $version)) {
                throw new \Emerald\Filelib\FilelibException("Version '{$version}' is not available");
            }
            $provider = $this->getVersionProvider($file, $version);
            $res = $this->getFilelib()->getStorage()->retrieveVersion($file, $provider);
        } else {
            $res = $this->getFilelib()->getStorage()->retrieve($file);
        }

        if(!is_readable($res->getPathname())) {
            throw new \Emerald\Filelib\FilelibException('File not readable');
        }

        readfile($res->getPathname());

    }

    
    public function publish(\Emerald\Filelib\File\File $file)
    {
                        
        $this->getFilelib()->getPublisher()->publish($file);
        foreach($file->getProfileObject()->getPlugins() as $plugin) {
            $plugin->onPublish($file);
        }
    }
    
    public function unpublish(\Emerald\Filelib\File\File $file)
    {
        $this->getFilelib()->getPublisher()->unpublish($file);
        foreach($file->getProfileObject()->getPlugins() as $plugin) {
            $plugin->onUnpublish($file);
        }
        
    }
    

    
    
}