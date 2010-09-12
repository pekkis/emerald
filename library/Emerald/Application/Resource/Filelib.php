<?php
class Emerald_Application_Resource_Filelib extends Zend_Application_Resource_ResourceAbstract
{

    protected $_filelib;

    public function getFilelib()
    {


        if(!$this->_filelib) {
            	
            $options = $this->getOptions();
            	
            if(isset($options['cache'])) {
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

            if(!isset($publisherOptions['options'])) {
                $publisherOptions['options'] = array();
            }
            
            $filelib = new Emerald_Filelib($options);
            	
            if(isset($options['dbResource'])) {
                $this->getBootstrap()->bootstrap($options['dbResource']);

                $db = $this->getBootstrap()->getResource($options['dbResource']);

                $handler = new Emerald_Filelib_Backend_Db();
                $handler->setDb($db);

                $filelib->setBackend($handler);

                // $options['Db'] = $this->getBootstrap()->getResource($options['DbResource']);

                unset($options['dbResource']);
            }

            
            
            $storageOptions = $this->_handleStorageOptions($storageOptions);
            $storage = new $storageOptions['type']($storageOptions['options']);
            $filelib->setStorage($storage);

            
            $publisher = new $publisherOptions['type']($publisherOptions['options']);
            $filelib->setPublisher($publisher);                
                        
            if(!isset($options['profiles'])) {
                $options['profiles'] = array('default' => 'Default profile');
            }

            foreach($options['profiles'] as $name => $poptions) {

                $linkerOptions = $poptions['linker'];
                unset($poptions['linker']);

                $linker = new $linkerOptions['class']($linkerOptions['options']);
                	
                $linker->setFilelib($filelib);

                $profile = new Emerald_Filelib_FileProfile($poptions);

                $profile->setLinker($linker);

                $filelib->addProfile($profile);
            }
            	
            if(isset($options['plugins'])) {

                foreach($options['plugins'] as $plugin) {
                    	
                    // If no profiles are defined, use in all profiles.
                    if(!isset($plugin['profiles'])) {
                        $plugin['profiles'] = array_keys($filelib->getProfiles());
                    }
                    	
                    $plugin = new $plugin['type']($plugin);
                    $filelib->addPlugin($plugin);
                }

            }
            	
            if($cache) {
                $filelib->setCache($cache);
            }
            	
            	
            $this->_filelib = $filelib;
        }

        return $this->_filelib;
    }

    /**
     * Init
     *
     * @return Emerald_Filelib
     */
    public function init()
    {


        $filelib = $this->getFilelib();

        Zend_Registry::set('Emerald_Filelib', $filelib);

        return $filelib;
    }

    
    
    private function _handleStorageOptions($storageOptions)
    {
        if($storageOptions['type'] == 'Emerald_Filelib_Storage_Gridfs') {
            if(isset($storageOptions['options']['resource'])) {
                $storageOptions['options']['mongo'] = $this->getBootstrap()->bootstrap('mongo')->getResource('mongo');
                unset($storageOptions['resource']);
            }
        }
        
        return $storageOptions;
        
        
    }






}