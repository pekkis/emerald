<?php
/**
 * Emerald Core
 * 
 * @version $Id: Server.php 406 2008-05-18 08:43:21Z pekkis $
 *
 */
class Emerald_Server
{
	
	
	/**
	 * Emerald server root
	 *
	 * @var string
	 */
	private $_root;
	
	/**
	 * Emerald server config
	 *
	 * @var unknown_type
	 */
	private $_config;
	
	
    /**
     * Get singleton
     *
     * @return Emerald_Server
     */
    public static function getInstance($options = null)
    {
        static $instance;
        if(!$instance) {
            $instance = new self($options);
        }
        return $instance;  
    }
	
    
    
    /**
     * Prepares db connection (does not init it), loads core config.
     *
     */
    private function __construct($config)
    {
    	$this->_config = $config;
    	
    	$this->_root = APPLICATION_PATH;
		
        date_default_timezone_set($config['timezone']);
        
        // $this->loader = new Zend_Loader_PluginLoader();
		
        
    }

    
    
    /**
     * Gets root
     *
     * @return string
     */
    public function getRoot()
    {
    	return $this->_root;
    }
    
    
    
    /**
     * Returns config
     *
     * @return Zend_Config_Xml
     */
    public function getConfig()
    {
    	return $this->_config;
    }
    
    
    
    public function findCustomer($host)
    {
    	// Resolve symlinks to real path
    	$path = realpath($this->getRoot() . '/../customers/' . $host);
		
    	if(!Zend_Loader::isReadable($path))
    		return false;
    	
    	return new Emerald_Application_Customer($path);
    	
    }
    
    
    public function getAdminLocales()
    {
    	return array('fi', 'en');
    }
    
    
}
?>