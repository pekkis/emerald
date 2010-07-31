<?php
require_once "Zend/Application.php";
require_once "Zend/Debug.php";

class Emerald_Application extends Zend_Application
{
	private $_isCached = false;
	
	private $_cache;
	
	private $_loptions;
	
	private $_defaults = array(
		'type' => 'none',
	);
	
	public function __construct($environment, $options = null, $cache = array())
	{
		$this->_loptions = $options;
		$this->_cache = array_merge($this->_defaults, $cache);
		
		if($this->_cache['type'] !== 'none') {
			$options = $this->_cacheLoad($options);
		}
		
		return parent::__construct($environment, $options);
		
	}

	
	
	public function run()
	{
		if(!$this->_isCached && $this->_cache['type'] !== 'none') {
			$this->_cacheSave();
		}
		
		return parent::run();
		
	}
	
	
	private function _cacheLoad()
	{
		switch($this->_cache['type']) {
			
			case 'array':
				$filename = $this->_loptions . ".{$this->_cache['key']}.php";
				(is_readable($filename)) ? require $filename : $noptions = null;
				break;
			case 'apc':
				$noptions = apc_fetch($this->_cache['key']);
				break;
			default:
				require_once("Zend/Application/Exception.php");
				throw new Zend_Application_Exception('Unsupported config cache type');
		}

		if($noptions) {
			$this->_isCached = true;
		}
		
		return $noptions ?: $this->_loptions;
		
	}
	
	private function _cacheSave()
	{
		switch($this->_cache['type']) {
			
			case 'array':
				$filename = $this->_loptions . ".{$this->_cache['key']}.php";
				file_put_contents($filename, "<?php\n\$noptions = " . var_export($this->getBootstrap()->getOptions(), true) . ';');
				break;
			case 'apc':
				apc_store($this->_cache['key'], $this->getBootstrap()->getOptions());
				break;
			default:
				require_once("Zend/Application/Exception.php");
				throw new Zend_Application_Exception('Unsupported config cache type');
		}

				
		
	}
	
	
}

