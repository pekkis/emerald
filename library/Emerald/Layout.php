<?php
abstract class Emerald_Layout
{
	
	private $_page;
	
	private $_action;
	
	private $_actionStack;
	
	public function __construct()
	{
	}

	
	abstract protected function _run();
		
	
	
	final protected function _preRun()
	{
		$this->_action->getHelper('layout')->setLayout('layouts/' . $this->getLayoutFile());
		$this->_actionStack = $this->_action->getHelper('actionStack'); 
		
	}
	
	
	public function run()
	{
		$this->_preRun();
		$this->_run();	
	}
	
	
	
	public function setNoRender($noRender = true)
	{
		$this->_action->getHelper('viewRenderer')->setNoRender($noRender);
	}
	
	
	
	public function setLayoutFile($layoutFile)
	{
		$this->_layoutFile = $layoutFile;
	}
	
	
	public function getLayoutFile()
	{
		return $this->_layoutFile;
	}
	
	
	public function setAction($action)
	{
		$this->_action = $action;
	}
	
	
	public function getAction()
	{
		return $this->_action;
	}
	
	
	
	public function setPage($page)
	{
		$this->_page = $page;
		$this->setLayoutFile(strtolower(basename($this->getPage()->layout, '.phtml')));
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
									
			$params['page_id'] = $page->id;
			
			return $this->actionToStack(
				$action['action'], $action['controller'], $action['module'], $params
			);
					
		} catch(Exception $e) {
			
			return $e->getMessage();
		}
		
	}
	
	
	
}