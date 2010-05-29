<?php 
class Emerald_Application_Resource_Emrouter extends Zend_Application_Resource_Router
{
	
	private $_shardModel;
	
	public function getShardModel()
	{
		if(!$this->_shardModel) {
			return new EmCore_Model_Shard();
		}	
	}
	
	
	public function getShard($page)
	{
		$shardItem = $this->getShardModel()->find($page->shard_id);
		return $shardItem;
		
	}
	
	
	public function init()
	{
		$router = parent::init();
		$cache = $this->getBootstrap()->bootstrap('cache')->getResource('cache')->getCache('default');
		
		$this->getBootstrap()->bootstrap('translate');
		
		$pageRoutes = $cache->load('Emerald_PageRoutes');
		
		if($pageRoutes === false) {
			
			$this->getBootstrap()->bootstrap('modules')->bootstrap('emdb');
			
			$pageRoutes = array();
			$naviModel = new EmCore_Model_Navigation();
			
			$shardModel = new EmCore_Model_Shard();
			
			$navi = $naviModel->getNavigation();
			
			$navi = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);
			
			foreach($navi as $page) {
				
				if($page->id && $page->shard_id) {

					$shard = $this->getShard($page);

					$routes = $shard->getRoutes($page);

					foreach($routes as $name => $route) {
						$pageRoutes[$name] = $route;
					}
					
					
					// $naviModel->navigationFromShard($page);
					
				}
			}
			
			if($pageRoutes) {
				$router->addRoutes($pageRoutes);	
			}
			
			$cache->save($pageRoutes, 'Emerald_PageRoutes');
			
			/*
			foreach($navi as $page) {
				if($page->id && $page->shard_id && ($page->parent_id != $page->id)) {
					$naviModel->navigationFromShard($page);
				}
				$naviModel->saveNavigation();
			}
			*/	
			
						
			
		} else {

			if($pageRoutes) {
				$router->addRoutes($pageRoutes);	
			}
			
		}

	
		
				
		// Zend_Debug::dump($naviModel->getNavigation());
										
		// Zend_Debug::dump($navi->getInnerIterator());
		
		
		return $router;
		
	}
	
	
}