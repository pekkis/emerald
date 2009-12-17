<?php
class Crud_IndexController extends Zend_Controller_action
{
	
	
	public function indexAction()
	{
		
		
		$crud = new Emerald_Db_Crud();

		$iter = new DirectoryIterator('/wwwroot/emerald/application/modules/core/models/DbTable');
				
		$crud->addModels(array('Core_Model_DbTable_JesusGarcia', 'Core_Model_DbTable_HtmlContent', 'Core_Model_DbTable_Locale', 'Core_Model_DbTable_Page'));

		$this->view->crud = $crud;
		
		
	}	
	
	
	
	public function modelAction()
	{
		$id = $this->_getParam('id');
				
		$model = new $id();
		
		
		$items = $model->fetchAll();
		
		$this->view->model = $model;
		$this->view->items = $items;
		
			

		
	}
	
	
	
}
