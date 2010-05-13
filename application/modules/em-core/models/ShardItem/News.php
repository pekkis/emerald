<?php
class EmCore_Model_ShardItem_News extends EmCore_Model_ShardItem
{
	public function getRoutes($page)
	{
		
		$viewRoute = $page->uri . '/:id/:title';
		$viewRoute = new Zend_Controller_Router_Route($viewRoute, array('module' => 'em-core', 'controller' => 'page', 'action' => 'view', 'beautifurl' => ltrim($page->uri, '/'), 'a' => 'view'), array('id' => '\d+'));

		$feedRoute = $page->uri . '/@feed/:mode';
		$feedRoute = new Zend_Controller_Router_Route($feedRoute, array('module' => 'em-core', 'controller' => 'page', 'action' => 'view', 'beautifurl' => ltrim($page->uri, '/'), 'a' => 'index', 'format' => 'xml'), array('mode' => "atom|rss"));

		$indexRoute = $page->uri . '/@index/:page';
		$indexRoute = new Zend_Controller_Router_Route($indexRoute, array('module' => 'em-core', 'controller' => 'page', 'action' => 'view', 'beautifurl' => ltrim($page->uri, '/'), 'a' => 'index'), array('id' => '\d+'));
		
		
		return array(
			"page_{$page->id}_news_feed" => $feedRoute,
			"page_{$page->id}_news_index" => $indexRoute,
			"page_{$page->id}_news_view" => $viewRoute,
		);
		
	}
	
	
	
	public function getNavigation($page)
	{
		$channelModel = new EmCore_Model_NewsChannel();
		$channel = $channelModel->findByPageId($page->id);
		
		$pages = array();
		
		foreach($channel->getItems(false) as $item) {
			
			$router = $this->getRouter();

			$pageRes = new Zend_Navigation_Page_Uri(
				array(
					'uri' => $router->assemble(array('module' => 'em-core', 'controller' => 'page', 'action' => 'view', 'beautifurl' => ltrim($page->uri, '/'), 'a' => 'view', 'id' => $item->id, 'title' => $item->title), "page_{$page->id}_news_view", true, false),
					'label' => $item->title,
					'locale' => $page->locale,
					'id' => $page->id,
					'global_id' => $page->global_id,
					'parent_id' => $page->id,
					'layout' => $page->layout,
					'shard_id' => $page->shard_id,
					'cache_seconds' => $page->cache_seconds,
				)
			);

			$pageRes->setResource("Emerald_Page_{$page->id}");
			$pageRes->setPrivilege('read');
			$pageRes->setVisible(false);
			
			$pages[] = $pageRes;			
		}

		
		return $pages;
		
		
	}
	
	
}