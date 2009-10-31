<?php
/**
 * Emerald Core
 * 
 * @version $Id: Server.php 406 2008-05-18 08:43:21Z pekkis $
 *
 */
class Emerald_Server
{
	
	private $_name = 'Emerald';

	/**
	 * Emerald server version
	 *
	 * @var string
	 */
	private $_version = '2.0.0';
	
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
    public static function getInstance()
    {
        static $instance;
        if(!$instance) {
            $instance = new self();
        }
        return $instance;  
    }
	
    
    
    /**
     * Prepares db connection (does not init it), loads core config.
     *
     */
    private function __construct()
    {
    	$this->_root = realpath(dirname(__FILE__). "/../../..");
    	$this->_config = new Zend_Config_Xml($this->getRoot() . '/application/config.xml');

    	$db = Zend_Db::factory('PDO_MYSQL', $this->_config->db->toArray());
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $db->getConnection()->exec("SET names utf8");
        
        $this->_db = $db;

        
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
	
    
    public function getIdentifier($spec = 'full')
    {
    	
    	switch($spec) {
    		
    		case 'full':
    			$identifier = $this->_name . ';' . $this->_version;
    			break;
    			
    		case 'name':
    			$identifier = $this->_name;
    			break;

    	}
    	
    
    	return $identifier;
    	
    	
    }
    
    
    public function getVersion()
    {
    	return $this->_version;
    }
    
    
    public function findCustomer(Zend_Controller_Request_Http $request)
    {
    	// Resolve symlinks to real path
    	$path = realpath($this->getRoot() . '/customers/' . $request->getServer('HTTP_HOST'));
		
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