<?php
require_once "Zend/Application.php";

/**
 * A config-caching extension of Zend_Application
 * 
 * Supports automagical caching of .ini type application configurations in either array or apc format.
 * Good for both ease of maintenance and speed.
 * 
 * @author pekkis
 * @package Emerald_Common_Application
 * @todo Add more cache backends (Xcache, Zend Server etc)
 *
 */
class Emerald_Common_Application extends Zend_Application
{
    /**
     * Config is cached or not
     * @var boolean
     */
    private $_isCached = false;

    /**
     * Cache options
     * @var array
     */
    private $_cache;

    /**
     * Loaded options
     * @var mixed
     */
    private $_loptions;

    /**
     * Config defaults
     * @var unknown_type
     */
    private $_defaults = array(
		'type' => 'none',
        'key' => 'dawn',
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

    


    /**
     * Returns whether the application config is in a cached state or not
     * @return boolean
     */
    public function isCached()
    {
        return $this->_isCached;
    }
    
    
    /**
     * Tries to load config from cache.
     * 
     * @throws Zend_Application_Exception
     */
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
                throw new Zend_Application_Exception("Unsupported config cache type '{$this->_cache['type']}'");
        }

        if($noptions) {
            $this->_isCached = true;
        }

        return $noptions ?: $this->_loptions;

    }

    /**
     * Saves application config from the cache.
     * 
     * Saves application config to the cache. Gets options from the bootstrap, not the application,
     * because the alterations made in bootstrapping do not get back to the app.
     * 
     * @throws Zend_Application_Exception
     */
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
                throw new Zend_Application_Exception("Unsupported config cache type '{$this->_cache['type']}'");
        }

    }

    
    
    /**
     * @see Zend_Application::run()
     */
    public function run()
    {
        if(!$this->isCached() && $this->_cache['type'] !== 'none') {
            $this->_cacheSave();
        }
        return parent::run();
    }

}

