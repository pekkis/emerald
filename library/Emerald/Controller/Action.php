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
		
	
	protected static $_pageModel; 
	
	
	/**
	 * Returns customer
	 * 
	 * @return Emerald_Application_Customer
	 */
	public function getCustomer()
	{
		return $this->getInvokeArg('bootstrap')->getResource('customer');
	}
	
	
	/**
	 * Returns current user
	 * 
	 * @return EmCore_Model_UserItem
	 */
	public function getCurrentUser()
	{
		return $this->getInvokeArg('bootstrap')->getResource('emuser');
	}
	
	public function postDispatch()
	{
		if($rs = $this->_getParam('rs')) {
			$this->getHelper('viewRenderer')->setResponseSegment($rs);
		}
		
		
	}
	
	
	public function getDb()
	{
		return $this->getInvokeArg('bootstrap')->getResource('emdb');
	}
	
	
	/**
	 * Returns ACL
	 * 
	 * @return Zend_Acl
	 */
	public function getAcl()
	{
		return $this->getInvokeArg('bootstrap')->getResource('emacl');
	}
	

	protected function _getPageModel()
	{
		if(!self::$_pageModel) {
			self::$_pageModel = new EmCore_Model_Page();
		}
		
		return self::$_pageModel;
	}
	
	
	protected function _pageFromPageId($pageId)
	{
		return $this->_getPageModel()->find($pageId);
	}
	
	
}
?>