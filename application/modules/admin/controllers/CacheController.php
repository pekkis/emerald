<?php
class Admin_CacheController extends Emerald_Controller_Action
{
	public $ajaxable = array(
		'clear' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	public function clearAction()
	{

		try {
			
			$cacheManager = Zend_Registry::get('Emerald_CacheManager');
			foreach($cacheManager as $cache) {
				$cache->clean();
			}

			$msg = new Emerald_Message(Emerald_Message::SUCCESS, "All caches were cleared.");
			
		} catch(Exception $e) {
			$msg = new Emerald_Message(Emerald_Message::ERROR, "Cleaning the caches failed.");
		}
		
		$this->view->message = $msg;
				
		
	}
	
	
}