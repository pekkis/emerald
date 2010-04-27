<?php 
class Emerald_Application_Resource_Emrouter extends Zend_Application_Resource_ResourceAbstract
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
		
		$router = $this->getBootstrap()->getResource('router');
		$cache = $this->getBootstrap()->getResource('cache')->getCache('default');
		
		if(!$pageRoutes = $cache->load('Emerald_PageRoutes')) {
									
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
					
				}
			}
			
			$cache->save($pageRoutes, 'Emerald_PageRoutes');
			
		}

		if($pageRoutes) {
			$router->addRoutes($pageRoutes);	
		}
		
		
		
		
		
	}
	
	
}