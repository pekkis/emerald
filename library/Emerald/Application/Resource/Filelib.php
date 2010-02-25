<?php
class Emerald_Application_Resource_Filelib extends Zend_Application_Resource_ResourceAbstract
{

	protected $_filelib;

	public function getFilelib()
	{
		if(!$this->_filelib) {
			
			$options = $this->getOptions();
				
			
			
						
			$symlinkerOptions = $options['symlinker'];
			unset($options['symlinker']);
				
			$symlinker = new $symlinkerOptions['class']($symlinkerOptions['options']);
			
			$this->_filelib = new Emerald_Filelib($options);

			$symlinker->setFilelib($this->_filelib);
			
			$this->_filelib->setSymlinker($symlinker);
			
			if(isset($options['dbResource'])) {
				$this->getBootstrap()->bootstrap($options['dbResource']);

				$db = $this->getBootstrap()->getResource($options['dbResource']);
				$handler = new Emerald_Filelib_Backend_Db();
				$handler->setDb($db);
				
				$this->_filelib->setBackend($handler);
												
				// $options['Db'] = $this->getBootstrap()->getResource($options['DbResource']);

				unset($options['dbResource']);
			}
			
		}

		if(!isset($options['profiles'])) {
			$options['profiles'] = array('default' => 'Default profile');
		}

		foreach($options['profiles'] as $name => $description) {
			$this->_filelib->addProfile($name, $description);
		}
		
		if(isset($options['plugins'])) {
			
			foreach($options['plugins'] as $plugin) {
				
				if(!isset($plugin['profiles'])) {
					$plugin['profiles'] = array_keys($filelib->getProfiles());
				}
				
				$plugin = new $plugin['type']($plugin);
				
				$this->_filelib->addPlugin($plugin);
			}
			
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