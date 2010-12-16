<?php
/**
 * Filelib initialization
 * 
 * @author pekkis
 * @package Emerald_Common_Application
 * @todo Some kind of initializer stuff for converting resources to init
 *
 */
class Emerald_Common_Application_Resource_Filelib extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var Emerald\Filelib\FileLibrary
     */
    protected $_filelib;

    /**
     * Returns filelib
     * 
     * @return Emerald\Filelib\FileLibrary
     */
    public function getFilelib()
    {

        if (!$this->_filelib) {
            	
            $options = $this->getOptions();
                       
            // These are kludgings... rethink required 
            
            if (isset($options['cache'])) {
                $this->getBootstrap()->bootstrap('cache');
                $cache = Zend_Registry::get('Emerald_CacheManager')->getCache($options['cache']);
                unset($options['cache']);
            } else {
                $cache = false;
            }
           
            // $aclOptions = $options['acl'];
            // unset($options['acl']);
            
            $storageOptions = $options['storage'];
            unset($options['storage']);

            $publisherOptions = $options['publisher'];
            unset($options['publisher']);

            if (!isset($publisherOptions['options'])) {
                $publisherOptions['options'] = array();
            }

            $backendOptions = $options['backend'];
            unset($options['backend']);

            
            
            $backendOptions = $this->_handleBackendOptions($backendOptions);

            $config = new Emerald\Filelib\Configuration($options);
            if(isset($options['tempDir'])) {
                $config->setTempDir($options['tempDir']);
            }
            
            $backend = new $backendOptions['type']($backendOptions['options']);
            $config->setBackend($backend);
            
            $storageOptions = $this->_handleStorageOptions($storageOptions);
            $storage = new $storageOptions['type']($storageOptions['options']);
            $config->setStorage($storage);
            
            $publisher = new $publisherOptions['type']($publisherOptions['options']);
            $config->setPublisher($publisher);                
                        
            if (!isset($options['profiles'])) {
                $options['profiles'] = array('default' => 'Default profile');
            }

            foreach ($options['profiles'] as $name => $poptions) {
                $linkerOptions = $poptions['linker'];
                unset($poptions['linker']);

                $linker = new $linkerOptions['class']($linkerOptions['options']);

                $profile = new Emerald\Filelib\File\FileProfile($poptions);
                $profile->setLinker($linker);

                $config->addProfile($profile);
            }
            	
            if (isset($options['plugins'])) {
                foreach ($options['plugins'] as $plugin) {
                    // If no profiles are defined, use in all profiles.
                    if (!isset($plugin['profiles'])) {
                        $plugin['profiles'] = array_keys($config->file()->getProfiles());
                    }
                    $plugin = new $plugin['type']($plugin);
                    $config->addPlugin($plugin);
                }
            }
            	
            if($cache) {
                
                $cacheAdapter = new \Emerald\Base\Cache\Adapter\ZendCacheAdapter();
                $cacheAdapter->setCache($cache);
                $config->setCache($cacheAdapter);
            }

            
            $config->setAcl(new \Emerald\Filelib\Acl\SimpleAcl());
            
            
            $this->_filelib = new \Emerald\Filelib\FileLibrary($config);
            
        }

        return $this->_filelib;
    }

    /**
     * @return Emerald\Filelib\FileLibrary
     */
    public function init()
    {
        $filelib = $this->getFilelib();
        Zend_Registry::set('Emerald_Filelib', $filelib);
        return $filelib;
    }

    
    
    private function _handleStorageOptions($storageOptions)
    {
        if ($storageOptions['type'] == '\Emerald\Filelib\Storage\GridfsStorage') {
            if (isset($storageOptions['options']['resource'])) {
                $storageOptions['options']['mongo'] = $this->getBootstrap()->bootstrap($storageOptions['options']['resource'])->getResource($storageOptions['options']['resource']);
                unset($storageOptions['resource']);
            }
        }
        
        return $storageOptions;
        
        
    }


    private function _handleBackendOptions($backendOptions)
    {
        if ($backendOptions['type'] == 'Emerald\Filelib\Backend\ZendDbBackend') {
            if (isset($backendOptions['options']['resource'])) {
                $backendOptions['options']['db'] = $this->getBootstrap()->bootstrap($backendOptions['options']['resource'])->getResource($backendOptions['options']['resource']);
                unset($backendOptions['options']['resource']);
            }
        } elseif ($backendOptions['type'] == 'Emerald\Filelib\Backend\MongoBackend') {
            if (isset($backendOptions['options']['resource'])) {
                $backendOptions['options']['mongo'] = $this->getBootstrap()->bootstrap($backendOptions['options']['resource'])->getResource($backendOptions['options']['resource']);
                unset($backendOptions['options']['resource']);
            }
        } elseif ($backendOptions['type'] == 'Emerald\Filelib\Backend\Doctrine2Backend') {
            $backendOptions['options']['entityManager'] = $this->getBootstrap()->bootstrap($backendOptions['options']['resource'])->getResource($backendOptions['options']['resource']);
            unset($backendOptions['options']['resource']);
        }
        
        return $backendOptions;
    }

}