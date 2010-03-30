<?php
class Emerald_Server
{

	private $_options;
	
	private $_db;
	
	private $_queue;
	
	public function __construct($options)
	{
		$this->_options = $options;
		
		Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp($options['smtp']));
		date_default_timezone_set($options['timezone']);
		
	}
	
	
	public function getOptions()
	{
		return $this->_options;
	}
	
	
	public function getDb()
	{
		$options = $this->getOptions();
		if(!$this->_db) {
			$this->_db = Zend_Db::factory($options['db']['adapter'], $options['db']['params']);		
		}
		
		return $this->_db;
	}
	
	
	public function registerCustomer(Emerald_Application_Customer $customer)
	{
		$db = $this->getDb();
		$serverModel = new EmCore_Model_Server($db);
		$serverModel->registerCustomer($customer);
	}
	
	
	/**
	 * @return Zend_Queue
	 */
	public function getQueue()
	{
		if(!$this->_queue) {
		
			$params = $this->getDb()->getConfig();
			
			$params['type'] = 'pdo_' . $this->getDb()->getConnection()->getAttribute(PDO::ATTR_DRIVER_NAME);
			
			$options = array(
			'name'          => 'customers',
	          'driverOptions' => $params
		      );
			$this->_queue = new Zend_Queue('Db', $options);

		}		
		
		return $this->_queue;
      
		
      
	}
	
	
	
	
}