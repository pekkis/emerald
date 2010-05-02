<?php
class Emerald_Application_Resource_Emdb extends Zend_Application_Resource_Db
{

	protected $_customer;
	
	public function init()
	{
		
		$db = parent::init();
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		$this->getCustomer()->setDb($db);
		
		Zend_Registry::set('Emerald_Db', $db);
		
		return $db;
		
	}
	
	
	
	public function getCustomer()
	{
		return $this->getBootstrap()->getResource('customer');
	}
	
 
}