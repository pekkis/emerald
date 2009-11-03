<?php
class Emerald_Layout
{
	
	private $_page;
	
	private $_action;
	
	private $_actionStack;
	
	public function __construct(Emerald_Page $page, Emerald_Controller_Action $action)
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
	
	
}