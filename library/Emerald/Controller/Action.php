<?php
class Emerald_Controller_Action extends Zend_Controller_Action
{
	private static $_added = 0;	
	
	
	public function getCustomer()
	{
		return $this->getInvokeArg('bootstrap')->getResource('customer');
	}
	
	
	public function getCurrentUser()
	{
		return $this->getInvokeArg('bootstrap')->getResource('user');
	}
	
	
	
	public function preDispatch()
	{
		parent::preDispatch();
				
		
		//	It is not easy to make the viewrenderer do what I want.		
		$this->getHelper('viewRenderer')->initView();
		if(self::$_added == 0) {
			$this->view->addBasePath($this->getCustomer()->getRoot() . '/views/');
			self::$_added = 1;	
		}
						
	}
	
	
	public function getDb()
	{
		return $this->getInvokeArg('bootstrap')->getResource('user');
	}
	
	
	
	
}
?>