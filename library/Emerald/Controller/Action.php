<?php
/**
 * Emerald controller action
 * 
 * @package Emerald_Controller
 * @author pekkis
 *
 */
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
		
		
		
		//	It is not easy to make the viewrenderer do what I want.		
		$this->getHelper('viewRenderer')->initView();
		if(self::$_added == 0) {
			$this->view->addBasePath($this->getCustomer()->getRoot() . '/views/');
			self::$_added = 1;	
		}
						
	}

	
	
	public function postDispatch()
	{
		if($rs = $this->_getParam('rs')) {
			$this->getHelper('viewRenderer')->setResponseSegment($rs);
		}
		
		
	}
	
	
	public function getDb()
	{
		return $this->getInvokeArg('bootstrap')->getResource('customerdb');
	}
	
	
	/**
	 * Returns ACL
	 * 
	 * @return Zend_Acl
	 */
	public function getAcl()
	{
		return $this->getInvokeArg('bootstrap')->getResource('acl');
	}
	
	
}
?>