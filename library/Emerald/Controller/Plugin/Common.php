<?php
class Emerald_Controller_Plugin_Common extends Zend_Controller_Plugin_Abstract
{
	
	
	public function dispatchLoopStartup($request)
	{
		$view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
		$naviHelper = $view->getHelper('Navigation');

		$user = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('user');
		$acl = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('acl');
		
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($user);
		
		if($request->getModuleName() == 'admin') {
			
			$model = new Admin_Model_Navigation();
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
			
			$aclPlugin->setErrorPage('index', 'login', 'default');
			
						
			Zend_Controller_Front::getInstance()->registerPlugin($aclPlugin);
			
			// $this->view->translate()->setTranslator(Zend_Registry::get('Zend_Translate'));
			// $this->view->translate()->setLocale(Zend_Registry::get('Zend_Locale') ? Zend_Registry::get('Zend_Locale') : 'en');
				
		} else {
			$model = new Core_Model_Navigation();
			$navigation = $model->getNavigation();
		}
		
		
		$naviHelper->setContainer($navigation);
		
		
		
				
	}

	
	
	
	
	
}