<?php
class Emerald_Controller_Plugin_Common extends Zend_Controller_Plugin_Abstract
{
	
	
	public function dispatchLoopStartup($request)
	{
		if($request->getModuleName() == 'admin') {
									
			$user = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('user');
			$acl = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('acl');
			
			$model = new Admin_Model_Navigation();
			$navigation = new RecursiveIteratorIterator($model->getNavigation(), RecursiveIteratorIterator::SELF_FIRST);

			
			
			foreach($navigation as $page) {
				
				$resName = "{$page->module}_" . ($page->controller ? $page->controller : 'index') . '_'  . ($page->action ? $page->action : 'index');
				
				if(!$acl->has($resName)) {
					$acl->addResource($resName);	
				}
				$page->setResource($resName);
				$page->setPrivilege('read');
		
			}
			
			Zend_Controller_Front::getInstance()->registerPlugin(new Emerald_Controller_Plugin_Acl($acl, $user));
			
			if($user->inGroup(Core_Model_Group::GROUP_ANONYMOUS))
			{
				$this->getResponse()->setRedirect('/login');
			}
			
			// $this->view->translate()->setTranslator(Zend_Registry::get('Zend_Translate'));
			// $this->view->translate()->setLocale(Zend_Registry::get('Zend_Locale') ? Zend_Registry::get('Zend_Locale') : 'en');
				
		}
		
	}

	
	
	
	
	
}