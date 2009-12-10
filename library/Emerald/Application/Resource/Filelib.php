<?php
class Emerald_Application_Resource_Filelib extends Zend_Application_Resource_ResourceAbstract
{

	protected $_filelib;

	public function getFilelib()
	{
		if(!$this->_filelib) {
			
			$options = $this->getOptions();
			$this->_filelib = new Emerald_Filelib($options);
			
			if(isset($options['DbResource'])) {
				$this->getBootstrap()->bootstrap($options['DbResource']);

				$db = $this->getBootstrap()->getResource($options['DbResource']);
				$handler = new Emerald_Filelib_Backend_Db();
				$handler->setDb($db);
				
				$this->_filelib->setBackend($handler);
												
				// $options['Db'] = $this->getBootstrap()->getResource($options['DbResource']);

				unset($options['DbResource']);
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