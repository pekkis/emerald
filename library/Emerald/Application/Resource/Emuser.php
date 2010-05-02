<?php
class Emerald_Application_Resource_Emuser extends Zend_Application_Resource_ResourceAbstract
{
	
	public function init()
	{
		$this->getBootstrap()->bootstrap('modules');
		
		$auth = Emerald_Auth::getInstance();
				
		$userModel = new EmCore_Model_User();
		
		if($auth->hasIdentity()) {
			$user = $auth->getIdentity();
		} else {
			$user = $userModel->findAnonymous();
			if(!$user) {
				throw new Emerald_Exception('Something wrong with ur user');
			}
			// $auth->getStorage()->write($user);
		}
				
		Zend_Registry::set('Emerald_User', $user);
		
		return $user;		
		
	}
	
	
	
	
	
	
}