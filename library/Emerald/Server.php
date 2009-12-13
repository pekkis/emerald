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
	 * Emerald server Db
	 *
	 * @var Zend_Db_Adapter_Pdo_Mysql
	 */
	private $_db;
	
	
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
		
    	    	
    	$db = Zend_Db::factory($config['db']['adapter'], $config['db']['params']);
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $db->getConnection()->exec("SET names utf8");

        $this->_db = $db;
        
        $profiler = new Zend_Db_Profiler_Firebug('Core DB Queries');
		$profiler->setEnabled(true);

		// Attach the profiler to your db adapter
		$db->setProfiler($profiler);
				        
        
        date_default_timezone_set($config['timezone']);
        

		
        
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
    
    
    
    
    /**
     * Returns Db
     *
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    public function getDb()
    {
    	return $this->_db;
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
    
    
    /**
     * Is the Emerald core instance in production or not
     *
     * @return bool true or false
     */
    public function inProduction()
    {
    	return $this->getConfig()->production == 'true' ? true : false; 
    }
    
}
?>