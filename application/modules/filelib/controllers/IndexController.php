<?php
class Filelib_IndexController extends Zend_Controller_Action
{

	public function indexAction()
	{
			
		
		
	}
		
	


	

	
	public function itAction()
	{
		$fl = Zend_Registry::get('Emerald_Filelib');
		
		$fi = new Emerald_Filelib_FolderIterator($fl, null);

		$it = new RecursiveIteratorIterator($fi, RecursiveIteratorIterator::SELF_FIRST);

		foreach($it as $item) {
			
			echo $item->name;
			echo "\n";
			
		}
		
		Zend_Debug::dump($it);
		die();
		
		
		
	}
	
	
	
	
}