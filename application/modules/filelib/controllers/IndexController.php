<?php
class Filelib_IndexController extends Zend_Controller_Action
{

	public function indexAction()
	{
		
		
		
		
	}
		
	

	public function renderAction()
	{
		$fl = Zend_Registry::get('Emerald_Filelib');
		
		$file = $fl->findFile($this->_getParam('id'))->current();
		
		$fl->render($file, $this->getResponse());
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
	}
	

	
	public function itAction()
	{
		$fl = Zend_Registry::get('Emerald_Filelib');
		
		$fi = new Filelib_Model_FolderIterator($fl, null);

		$it = new RecursiveIteratorIterator($fi, RecursiveIteratorIterator::SELF_FIRST);

		foreach($it as $item) {
			
			echo $item->name;
			echo "\n";
			
		}
		
		Zend_Debug::dump($it);
		die();
		
		
		
	}
	
	
	
	
}