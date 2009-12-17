<?php
class Emerald_Application_Customer
{
	/**
	 * Array of customer roots.
	 *
	 * @var array
	 */
	private $_roots = array(
		'layouts' => 'views/scripts/layouts',
	);
	
	private $_root;

	/**
	 * Config
	 *
	 * @var Zend_Config_Xml
	 */
	private $_config;
	
	
	/**
	 * Db
	 *
	 * @var Zend_Db_Adapter_Pdo_Mysql
	 */
	private $_db;
	
	private $_optionContainer;
		
	
	public function __construct($root)
	{
		$this->_root = $root;
		$this->_config = new Zend_Config_Ini($this->getRoot() . '/configs/application.ini');
        // $this->_db = Zend_Db::factory('PDO_MYSQL', $this->_config->db->toArray());
	}
	
	
	/**
	 * Returns customer roots
	 *
	 * @param string $specified Specified root
	 * @return string root directory
	 */
	public function getRoot($specified = null)
	{
		return (!$specified) ? $this->_root : $this->_root . '/' . $this->_roots[$specified];
	}
	
	/**
     * Returns config
     *
     * @return Zend_Config_Ini
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
	

    
    public function setDb($db)
    {
		
    	$this->_db = $db;
    }
    
    
    public function getOption($key)
    {
    	return $this->getOptionContainer()->$key;
    }
    
    
    public function setOption($key, $value)
    {
    	return $this->getOptionContainer()->$key = $value;	
    }
    
    
    
    public function getOptionContainer()
    {
    	if(!$this->_optionContainer) {
    		$this->_optionContainer = new Emerald_Db_OptionContainer();
			$this->_optionContainer->setTable(new Core_Model_DbTable_Application_Option);
    		
    	}
    	return $this->_optionContainer;
    }
    
    
}
?>