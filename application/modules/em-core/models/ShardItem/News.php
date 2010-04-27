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
	
	
	
}