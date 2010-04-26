<?php
class Emerald_Controller_Plugin_Common extends Zend_Controller_Plugin_Abstract
{
	
	
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		$customer = Zend_Registry::get('Emerald_Customer');
		
		if($customer->isInstalled() && !$customer->isRegistered()) {
			$server = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('server');
			$server->registerCustomer($customer);
		}
					
		$view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
		$naviHelper = $view->getHelper('Navigation');

		$user = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('emuser');
		$acl = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('emacl');
		
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($user);
			
		
		if($request->getModuleName() == 'em-admin') {
			
			$model = new EmAdmin_Model_Navigation();
			$navigation = $model->getNavigation();
						
			$iter = new RecursiveIteratorIterator($navigation, RecursiveIteratorIterator::SELF_FIRST);
			foreach($iter as $page) {
				$resName = "{$page->module}_" . ($page->controller ? $page->controller : 'index') . '_'  . ($page->action ? $page->action : 'index');
				
				if(!$acl->has($resName)) {
					$acl->addResource($resName);	
				}
				$page->setResource($resName);
				$page->setPrivilege('read');
			}
			
			$aclPlugin = new Emerald_Controller_Plugin_Acl($acl, $user);
			
			$aclPlugin->setErrorPage('index', 'login', 'em-core');
												
			Zend_Controller_Front::getInstance()->registerPlugin($aclPlugin);
			
			// $this->view->translate()->setTranslator(Zend_Registry::get('Zend_Translate'));
			// $this->view->translate()->setLocale(Zend_Registry::get('Zend_Locale') ? Zend_Registry::get('Zend_Locale') : 'en');
				
		} else {
			$model = new EmCore_Model_Navigation();
			$navigation = $model->getNavigation();
			
		
							
									
			
		}
		
		
		$naviHelper->setContainer($navigation);
		
		
		
				
	}
	
	
	
}