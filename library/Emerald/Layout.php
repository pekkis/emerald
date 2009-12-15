<?php
class Emerald_Layout
{
	
	private $_page;
	
	private $_action;
	
	private $_actionStack;
	
	public function __construct(Core_Model_PageItem $page, Emerald_Controller_Action $action)
	{
		$this->_page = $page;
		$this->_action = $action;
		
		$this->_action->getHelper('layout')->setLayout('layouts/' . strtolower(basename($this->_page->layout, '.phtml')));
		$this->_actionStack = $this->_action->getHelper('actionStack'); 

		$this->_action->getHelper('viewRenderer')->setNoRender();		
		$this->init();
		
		
	}
	
	
	
	public function getPage()
	{
		return $this->_page;
	}
	
	public function actionToStack($action, $controller = null, $module = null, array $params = array())
	{
				
		$this->_actionStack->actionToStack($action, $controller, $module, $params);

		
	}
	
	
	public function shard($page, $identifier, $params = array())
	{

		
		try {
			
			if(!$page instanceof Core_Model_PageItem) {

				$pageModel = new Core_Model_Page();
				$page = $pageModel->find($page);
				
				// prevents unneseccary template errors
				// @todo What is this?
				if(!$page) return; 
			}
						
			$requestParams = Zend_Controller_Front::getInstance()->getRequest()->getQuery();
			$params = array_merge($requestParams, $params);

			$shard = Emerald_Shard::factory($identifier);
			
			$action = Emerald_Shard::getDefaultAction($shard);
			
			
			if(isset($params['a'])) {
				$action['action'] = $params['a'];
			}
									
			$params['page'] = $page;
			
			return $this->actionToStack(
				$action['action'], $action['controller'], $action['module'], $params
			);
					
		} catch(Exception $e) {
			
			return (Emerald_Server::getInstance()->inProduction()) ? '' : $e->getMessage();
		}
		
	}
	
	
	
}