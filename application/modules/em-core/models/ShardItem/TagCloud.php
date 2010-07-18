<?php
class EmCore_Model_ShardItem_TagCloud extends EmCore_Model_ShardItem
{

	public function getRoutes($page)
	{
		$tagRoute = $page->uri . '/:tag';
		$tagRoute = new Zend_Controller_Router_Route($tagRoute, array('module' => 'em-core', 'controller' => 'page', 'action' => 'view', 'beautifurl' => ltrim($page->uri, '/'), 'a' => 'tag'), array());
				
		return array(
			"page_{$page->id}_tag-cloud_tag" => $tagRoute,
		);
		
	}
	
	
	
}