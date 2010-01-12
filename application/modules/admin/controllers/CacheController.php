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
			
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 401);
		}
		

		try {
			
			$cacheManager = Zend_Registry::get('Emerald_CacheManager');
			foreach($cacheManager as $cache) {
				$cache->clean();
			}

			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, "All caches were cleared.");
			
		} catch(Exception $e) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, "Cleaning the caches failed.");
		}
		
		$this->view->message = $msg;
				
		
	}
	
	
}