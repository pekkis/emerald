<?php
class Core_LogoutController extends Emerald_Controller_Action 
{
	
	
	public function indexAction()
	{
		if($this->_emerald->getSession()) {
			$this->_emerald->getSession()->id = Zend_Session::getId();
			$this->_emerald->getSession()->user_id = Emerald_User::USER_ANONYMOUS;
			$this->_emerald->getSession()->save();
		}
				
		Zend_Session::destroy(true);
		Zend_Session::forgetMe();
		
		$this->_redirect('/');
		
	}
	
	
	
}