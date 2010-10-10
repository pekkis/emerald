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

            $filelib = new Emerald\Filelib\FileLibrary($options);
            
            $backend = new $backendOptions['type']($backendOptions['options']);
            $filelib->setBackend($backend);
            
            $storageOptions = $this->_handleStorageOptions($storageOptions);
            $storage = new $storageOptions['type']($storageOptions['options']);
            $filelib->setStorage($storage);
            
            $publisher = new $publisherOptions['type']($publisherOptions['options']);
            $filelib->setPublisher($publisher);                
                        
            if (!isset($options['profiles'])) {
                $options['profiles'] = array('default' => 'Default profile');
            }

            foreach ($options['profiles'] as $name => $poptions) {
                $linkerOptions = $poptions['linker'];
                unset($poptions['linker']);

                $linker = new $linkerOptions['class']($linkerOptions['options']);
                $linker->setFilelib($filelib);

                $profile = new Emerald\Filelib\FileProfile($poptions);
                $profile->setLinker($linker);
                $filelib->addProfile($profile);
            }
            	
            if (isset($options['plugins'])) {
                foreach ($options['plugins'] as $plugin) {
                    // If no profiles are defined, use in all profiles.
                    if (!isset($plugin['profiles'])) {
                        $plugin['profiles'] = array_keys($filelib->getProfiles());
                    }
                    $plugin = new $plugin['type']($plugin);
                    $filelib->addPlugin($plugin);
                }
            }
            	
            if($cache) {
                
                $cacheAdapter = new \Emerald\Base\Cache\Adapter\ZendCacheAdapter();
                $cacheAdapter->setCache($cache);
                $filelib->setCache($cacheAdapter);
            }
            	
            $this->_filelib = $filelib;
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
                unset($backendOptions['resource']);
            }
        } elseif ($backendOptions['type'] == 'Emerald\Filelib\Backend\MongoBackend') {
            if (isset($backendOptions['options']['resource'])) {
                $backendOptions['options']['mongo'] = $this->getBootstrap()->bootstrap($backendOptions['options']['resource'])->getResource($backendOptions['options']['resource']);
                unset($backendOptions['resource']);
            }
        }
        
        return $backendOptions;
    }

}