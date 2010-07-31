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
            	

            if(!isset($options['profiles'])) {
                $options['profiles'] = array('default' => 'Default profile');
            }

            foreach($options['profiles'] as $name => $poptions) {

                $symlinkerOptions = $poptions['symlinker'];
                unset($poptions['symlinker']);

                $symlinker = new $symlinkerOptions['class']($symlinkerOptions['options']);
                	
                $symlinker->setFilelib($filelib);

                $profile = new Emerald_Filelib_FileProfile($poptions);

                $profile->setSymlinker($symlinker);

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







}