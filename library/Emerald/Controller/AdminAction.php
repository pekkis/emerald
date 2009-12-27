<?php
/*
 * Base class for Administrative Actions (tm)
 * 
 * Adds functionality to the basic actions:
 * - OuterTemplating
 * - etc.
 * 
 * @author mkoh
 * @since day one :)
 */
class Emerald_Controller_AdminAction extends Emerald_Controller_Action
{

	
	/**
	 * Checks the authentication status, all admin actions require that the
	 * user is identified (not anon)
	 */
	public function preDispatch()
	{
		// Sets the default layout to be the admin_outer (backwards compat)
		// $this->_helper->layout->setLayout('admin_outer');
		
		if($this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ANONYMOUS))
		{
			$this->_forward("index", "login","default");
		}
		
		$this->view->translate()->setTranslator(Zend_Registry::get('Zend_Translate'));
		$this->view->translate()->setLocale(Zend_Registry::get('Zend_Locale') ? Zend_Registry::get('Zend_Locale') : 'en');
		
	}

	protected function _isUserAllowed($target, $permission)
	{
		return Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $target, $permission);
	}
}