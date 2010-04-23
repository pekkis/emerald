<?php
class Emerald_Application_Resource_Customerdb extends Zend_Application_Resource_Db
{

	protected $_customer;
	
	public function init()
	{
		$db = $this->getBootstrap()->getResource('db');
		
		$customer = $this->getCustomer();
								
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		// $db->getConnection()->exec("SET names utf8");
		
		$customer->setDb($db);
		
		Zend_Registry::set('Emerald_Db', $db);
		
		return $db;
		
	}
	
	
	
	public function getCustomer()
	{
		if(!$this->_customer) {
			$this->getBootstrap()->bootstrap('customer');
			$this->_customer = $this->getBootstrap()->getResource('customer');
		}
		return $this->_customer;
	}
	
	
	public function setCustomer($customer)
	{
		$this->_customer = $customer;
	}
	
	
 
}