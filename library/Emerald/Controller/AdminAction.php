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
		$this->_helper->layout->setLayout('admin_outer');
		
		if($this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ANONYMOUS))
		{
			$this->_forward("index", "login","default");
		}
		
		$this->view->translate()->setTranslator($this->_emerald->getTranslate());
		$this->view->translate()->setLocale(Emerald_Application::getInstance()->getLocale() ? Emerald_Application::getInstance()->getLocale() : 'en');
		
	}

	protected function _isUserAllowed($target, $permission)
	{
		return Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $target, $permission);
	}
}