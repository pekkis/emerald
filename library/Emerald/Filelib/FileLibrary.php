<?php

namespace Emerald\Filelib;

use \Emerald\Base\Options, \Emerald\Base\Cache;

/**
 * Emerald filelib
 *
 * @author pekkis
 *
 */
class FileLibrary
{
    /**
     * @var \Emerald\Filelib\Backend\Backend Backend
     */
    private $_backend;

    /**
     * @var \Emerald\Filelib\Storage\Storage Storage
     */
    private $_storage;

    /**
     * @var \Emerald\Filelib\Publisher\Publisher Publisher
     */
    private $_publisher;
    
    /**
     * @var \Emerald\Filelib\Acl\Acl Acl handler
     */
    private $_acl;

    /**
     * @var array Array of installed plugins
     */
    private $_plugins = array();


    /**
     * File operator
     * @var \Emerald\Filelib\File\FileOperator
     */
    private $_fileOperator;

    /**
     * Folder operator
     * @var \Emerald\Filelib\Folder\FolderOperator
     */
    private $_folderOperator;
    
    /**
     * Cache
     * @var \Emerald\Base\Cache\Cache
     */
    private $_cache;
        
    /**
     * Temporary directory
     * 
     * @var string
     */
    private $_tempDir;
    
    

    public function __construct($options = array())
    {
        Options::setConstructorOptions($this, $options);
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
     * @param \Emerald\Base\Cache\Cache $cache
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setCache(Cache\Cache $cache)
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
        if(!$this->_cache) {
            $this->_cache = new Cache\MockCache();
        }
        return $this->_cache;
    }

    /**
     * Returns the file operator
     * 
     * @return \Emerald\Filelib\File\FileOperator
     */
    public function file()
    {
        if(!$this->_fileOperator) {
            $this->_fileOperator = new File\FileOperator($this);
        }
        return $this->_fileOperator;
    }

    /**
     * Returns the folder operator
     * 
     * @return \Emerald\Filelib\Folder\FolderOperator
     */
    public function folder()
    {
        if(!$this->_folderOperator) {
            $this->_folderOperator = new Folder\FolderOperator($this);
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
        $this->file()->setClass($fileItemClass);
        return $this;
    }

    /**
     * Sets folderitem class
     *
     * @param string $folderItemClass Class name
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setFolderItemClass($folderItemClass)
    {
        $this->folder()->setClass($folderItemClass);
        return $this;
    }
    
    /**
     * Sets storage
     *
     * @param \Emerald\Filelib\Storage\Storage $storage
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setStorage(Storage\Storage $storage)
    {
        $storage->setFilelib($this);
        $this->_storage = $storage;
        return $this;
    }

    /**
     * Returns storage
     *
     * @return \Emerald\Filelib\Storage\Storage
     */
    public function getStorage()
    {
        if(!$this->_storage) {
            throw new FilelibException('Filelib storage not set');
        }

        return $this->_storage;
    }
    
    /**
     * Sets publisher
     *
     * @param \Emerald\Filelib\Publisher\Interface $publisher
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setPublisher(Publisher\Publisher $publisher)
    {
        $publisher->setFilelib($this);
        $this->_publisher = $publisher;
        return $this;
    }

    /**
     * Returns publisher
     *
     * @return \Emerald\Filelib\Publisher\Publisher
     */
    public function getPublisher()
    {
        if(!$this->_publisher) {
            throw new FilelibException('Filelib Publisher not set');
        }

        return $this->_publisher;
    }

    /**
     * Sets backend
     *
     * @param \Emerald\Filelib\Backend\Backend $backend
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setBackend(Backend\Backend $backend)
    {
        $backend->setFilelib($this);
        $backend->init();
        $this->_backend = $backend;
        return $this;
    }

    /**
     * Returns backend
     *
     * @return \Emerald\Filelib\Backend\Backend
     */
    public function getBackend()
    {
        if(!$this->_backend) {
            throw new FilelibException('Filelib backend not set');
        }

        return $this->_backend;
    }

    /**
     * Adds a plugin
     *
     * @param \Emerald\Filelib\Plugin\Plugin Plugin $plugin
     * @return \Emerald\Filelib\FileLibrary
     */
    public function addPlugin(Plugin\Plugin $plugin, $priority = 1000)
    {
        $plugin->setFilelib($this);

        foreach($plugin->getProfiles() as $profileIdentifier) {
            $profile = $this->file()->getProfile($profileIdentifier);
            $profile->addPlugin($plugin, $priority);
        }

        $plugin->init();

        return $this;
    }

    /**
     * Sets acl handler
     *
     * @param \Emerald\Filelib\Acl\Acl $acl
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setAcl(Acl\Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }

    /**
     * Returns acl handler
     *
     * @return \Emerald\Filelib\Acl\Acl
     */
    public function getAcl()
    {
        if(!$this->_acl) {
            $this->_acl = new Acl\SimpleAcl();
        }
        return $this->_acl;
    }
    
}