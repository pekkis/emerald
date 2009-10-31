<?php
class Emerald_Application_Customer
{
	/**
	 * Array of customer roots.
	 *
	 * @var array
	 */
	private $_roots = array(
		'templates' => 'views/scripts/templates',
		'innertemplates' => 'views/scripts/innertemplates',
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
		
	
	public function __construct($root)
	{
		$this->_root = $root;
		$this->_config = new Zend_Config_Xml($this->_root . '/config.xml');
        $this->_db = Zend_Db::factory('PDO_MYSQL', $this->_config->db->toArray());
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
	
	
}
?>