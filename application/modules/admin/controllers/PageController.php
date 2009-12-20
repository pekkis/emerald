<?php
class Admin_PageController extends Emerald_Controller_AdminAction
{
	

	
	public function editAction()
	{
		$pageModel = new Core_Model_Page();

		$page = $pageModel->find($this->_getParam('id'));		
		
		$form = new Admin_Form_Page();
		$form->setLocale($page->locale);
		$form->setDefaults($page->toArray());
				
		$this->view->form = $form;
				
		
	}
	
	
	public function saveAction()
	{
		
		$pageModel = new Core_Model_Page();
		$page = $pageModel->find($this->_getParam('id'));

		
		$naviModel = new Core_Model_Navigation();
		$navi = $naviModel->getNavigation();
		
		
		$form = new Admin_Form_Page();
		$form->setLocale($page->locale);
		
		if(!$form->isValid($this->_getAllParams())) {

			Zend_Debug::dump($form->getMessages());
			
			die('not ok');
			
			
			
			
		} else {
															
			Zend_Debug::dump($page->toArray());
			
			Zend_Debug::dump($form->getValues());
			
			$page->setFromArray($form->getValues());
						
			$pageModel->save($page);
						
			die();
			
		}
		
		
		
		
	}
	
	
	
	
	
	
}