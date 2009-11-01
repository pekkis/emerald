<?php
class Emerald_Controller_Action extends Zend_Controller_Action
{
	
	/**
	 * Emerald application
	 *
	 * @var Emerald_Application
	 */
	protected $_emerald;
		
	/**
	 * Zend db
	 *
	 * @var Zend_Db_Adapter_Pdo_Mysql
	 */
	protected $_db;
	
	static protected $_added = 0;
	
	
	/**
	 * Emerald customer
	 *
	 * @var Emerald_Application_Customer
	 */
	protected $_customer;
		
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		parent::__construct($request, $response, $invokeArgs);
		$this->_emerald = Emerald_Application::getInstance();
		$this->_db = $this->_emerald->getDb();
		$this->_customer = $this->_emerald->getCustomer();
		
		
		
	}
	
	
	public function preDispatch()
	{
		parent::preDispatch();
		
		//	It is not easy to make the viewrenderer do what I want.		
		$this->getHelper('viewRenderer')->initView();
		if(self::$_added == 0) {
			$this->view->addBasePath($this->_emerald->getCustomer()->getRoot() . '/views/');
			self::$_added = 1;	
		}
						
	}
	
	
	
	
}
?>