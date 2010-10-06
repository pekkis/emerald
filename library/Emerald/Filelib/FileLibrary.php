<?php

namespace Emerald\Filelib;

use \Emerald\Base\Options;

/**
 * Emerald filelib
 *
 * @author pekkis
 *
 */
class FileLibrary
{
    /**
     * @var \Emerald\Filelib\Backend\BackendInterface Backend
     */
    private $_backend;

    /**
     * @var \Emerald_Filelib_Storage_StorageInterface Storage
     */
    private $_storage;

    /**
     * @var \Emerald_Filelib_Publisher_PublisherInterface Publisher
     */
    private $_publisher;
    
    /**
     * @var \Emerald\Filelib\Acl\AclInterface Acl handler
     */
    private $_acl;

    /**
     * @var array Array of installed plugins
     */
    private $_plugins = array();

    /**
     * @var string Fileitem class
     */
    private $_fileItemClass = '\Emerald\Filelib\FileItem';

    /**
     * @var string Folderitem class
     */
    private $_folderItemClass = '\Emerald\Filelib\FolderItem';

    /**
     * File operator
     * @var \Emerald\Filelib\FileOperator
     */
    private $_fileOperator;

    /**
     * Folder operator
     * @var \Emerald\Filelib\FolderOperator
     */
    private $_folderOperator;
    
    /**
     * Cache
     * @var \Zend_Cache_Core
     */
    private $_cache;
        
    /**
     * Temporary directory
     * 
     * @var string
     */
    private $_tempDir;
    
    /**
     * Public prefix for accessing the files
     * 
     * @var unknown_type
     */
    private $_publicDirectoryPrefix = '/files';
    
    /**
     * @var array Profiles
     */
    private $_profiles = array();

    public function __construct($options = array())
    {
        Options::setConstructorOptions($this, $options);
    }
    
    /**
     * Sets public directory prefix
     * 
     * @param string $publicDirectoryPrefix
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setPublicDirectoryPrefix($publicDirectoryPrefix)
    {
        $this->_publicDirectoryPrefix = $publicDirectoryPrefix;
        return $this;
    }
    
    /**
     * Returns public directory prefix
     * 
     * @return string
     */
    public function getPublicDirectoryPrefix()
    {
        return $this->_publicDirectoryPrefix;
    }
    
    /**
     * Returns temp dir for filelib
     * @return string
     */
    public function getTempDir()
    {
        return $this->_tempDir ?: sys_get_temp_dir();
    }
    
    /**
     * Sets temp dir for filelib
     * @param string $tempDir
     */
    public function setTempDir($tempDir)
    {
        $this->_tempDir = $tempDir;
    }

    /**
     * Sets cache
     * @param \Zend_Cache_Core $cache
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setCache(\Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * Returns cache. If cache does not exist, init a black hole cache
     * @return \Zend_Cache_Core
     */
    public function getCache()
    {
        if(!isset($this->_cache)) {
            $frontendOptions = array(
			'lifetime' => 7200, // cache lifetime of 2 hours
			'automatic_serialization' => true
            );
            $backendOptions = array(
            );
            $this->_cache = \Zend_Cache::factory('core', 'black-hole', $frontendOptions, $backendOptions);
        }
        return $this->_cache;
    }

    /**
     * Adds a file profile
     * 
     * @param \Emerald\Filelib\FileProfile $profile
     * @return \Emerald\Filelib\FileLibrary
     */
    public function addProfile(\Emerald\Filelib\FileProfile $profile)
    {
        $profile->setFilelib($this);

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
     * @return \Emerald\Filelib\FileProfile
     */
    public function getProfile($identifier)
    {
        if($identifier instanceof \Emerald\Filelib\FileItem) {
            $identifier = $identifier->profile;
        }

        if(!isset($this->_profiles[$identifier])) {
            throw new \Emerald\Filelib\FilelibException("File profile '{$identifier}' not found");
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
     * Returns the file operator
     * 
     * @return \Emerald\Filelib\FileOperator
     */
    public function file()
    {
        if(!$this->_fileOperator) {
            $this->_fileOperator = new \Emerald\Filelib\FileOperator($this);
        }
        return $this->_fileOperator;
    }

    /**
     * Returns the folder operator
     * 
     * @return \Emerald\Filelib\FolderOperator
     */
    public function folder()
    {
        if(!$this->_folderOperator) {
            $this->_folderOperator = new \Emerald\Filelib\FolderOperator($this);
        }

        return $this->_folderOperator;
    }

    /**
     * Sets fileitem class
     *
     * @param string $fileItemClass Class name
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setFileItemClass($fileItemClass)
    {
        $this->_fileItemClass = $fileItemClass;
        return $this;
    }


    /**
     * Returns fileitem class
     *
     * @return string
     */
    public function getFileItemClass()
    {
        return $this->_fileItemClass;
    }


    /**
     * Sets folderitem class
     *
     * @param string $folderItemClass Class name
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setFolderItemClass($folderItemClass)
    {
        $this->_folderItemClass = $folderItemClass;
        return $this;
    }


    /**
     * Returns folderitem class
     *
     * @return string
     */
    public function getFolderItemClass()
    {
        return $this->_folderItemClass;
    }

    
    /**
     * Sets storage
     *
     * @param \Emerald_Filelib_Storage_StorageInterface $storage
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setStorage(\Emerald_Filelib_Storage_StorageInterface $storage)
    {
        $storage->setFilelib($this);
        $this->_storage = $storage;
        return $this;
    }


    /**
     * Returns storage
     *
     * @return \Emerald_Filelib_Storage_StorageInterface
     */
    public function getStorage()
    {
        if(!$this->_storage) {
            throw new \Emerald\Filelib\FilelibException('Filelib storage not set');
        }

        return $this->_storage;
    }
    
    /**
     * Sets publisher
     *
     * @param \Emerald_Filelib_Publisher_Interface $publisher
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setPublisher(\Emerald_Filelib_Publisher_PublisherInterface $publisher)
    {
        $publisher->setFilelib($this);
        $this->_publisher = $publisher;
        return $this;
    }


    /**
     * Returns publisher
     *
     * @return \Emerald_Filelib_Publisher_PublisherInterface
     */
    public function getPublisher()
    {
        if(!$this->_publisher) {
            throw new \Emerald\Filelib\FilelibException('Filelib Publisher not set');
        }

        return $this->_publisher;
    }
    

    /**
     * Sets backend
     *
     * @param \Emerald\Filelib\Backend\BackendInterface $backend
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setBackend(\Emerald\Filelib\Backend\BackendInterface $backend)
    {
        $backend->setFilelib($this);
        $backend->init();
        $this->_backend = $backend;
        return $this;
    }


    /**
     * Returns backend
     *
     * @return \Emerald\Filelib\Backend\BackendInterface
     */
    public function getBackend()
    {
        if(!$this->_backend) {
            throw new \Emerald\Filelib\FilelibException('Filelib backend not set');
        }

        return $this->_backend;
    }

    /**
     * Adds a plugin
     *
     * @param \Emerald_Filelib_Plugin_PluginInterface Plugin $plugin
     * @return \Emerald\Filelib\FileLibrary
     */
    public function addPlugin(\Emerald_Filelib_Plugin_PluginInterface $plugin)
    {
        $plugin->setFilelib($this);

        foreach($plugin->getProfiles() as $profileIdentifier) {
            $profile = $this->getProfile($profileIdentifier);
            $profile->addPlugin($plugin);
        }

        $this->_plugins[] = $plugin;

        $plugin->init();

        return $this;
    }

    /**
     * Returns all plugins
     *
     * @return array Array of plugins
     */
    public function getPlugins()
    {
        return $this->_plugins;
    }


    /**
     * Sets acl handler
     *
     * @param \Emerald\Filelib\Acl\AclInterface $acl
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setAcl(\Emerald\Filelib\Acl\AclInterface $acl)
    {
        $this->_acl = $acl;
        return $this;
    }


    /**
     * Returns acl handler
     *
     * @return \Emerald\Filelib\Acl\AclInterface
     */
    public function getAcl()
    {
        if(!$this->_acl) {
            $this->_acl = new \Emerald\Filelib\Acl\SimpleAcl();
        }
        return $this->_acl;
    }
    
}