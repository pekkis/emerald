<?php
class Core_Model_Navigation
{

	private $_navigation;
	
	private $_pageModel;
	
	
	/**
	 * Returns page model
	 * 
	 * @return Core_Model_Page
	 */
	public function getPageModel()
	{
		if(!$this->_pageModel) {
			$this->_pageModel = new Core_Model_Page();	
		}
		return $this->_pageModel;
		
	}
	
	
	
	public function pageUpdate(Core_Model_PageItem $page)
	{
		// Zend_Debug::Dump($page->id, "UPDATING");
		
		$navi = $this->clearNavigation()->getNavigation();
		

		
		$navi = $navi->findBy("id", $page->id);

		$route = array();
		$beautifurl = array();

		$parent = $navi;
		
		array_unshift($route, '[' . $parent->id . ']');
		array_unshift($beautifurl, $parent->label);
		
		while($parent = $parent->getParent()) {

			if(!$parent instanceof Zend_Navigation_Page) {
				break;
			}
			
						
			if($parent->id) {
				array_unshift($route, '[' . $parent->id . ']');
				array_unshift($beautifurl, $parent->label);	
			}
					
		}

		$route = implode(";", $route);
		$beautifurl = Emerald_Beautifurl::fromArray($beautifurl, $page->locale);
		
		$navi->url = "/" . $beautifurl;

		$this->getPageModel()->getTable()->update(
			array('path' => $route, 'beautifurl' => $beautifurl),
			$this->getPageModel()->getTable()->getAdapter()->quoteInto("id = ?", $page->id)
		);

		$this->_navigation = null;
		
		
		if($pages = $navi->getPages()) {
			foreach($pages as $child) {
				$childPage = $this->getPageModel()->find($child->id);
				$this->pageUpdate($childPage);
			}
		}
		
		$navi = $this->clearNavigation()->getNavigation();
		
		
	}
	
	
	
	public function clearNavigation()
	{
		$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('global');
		$cache->remove('navigation');
		
		$this->_navigation = null;
		return $this;
	}
	
	
	/**
	 * Returns the whole site navi
	 * 
	 * @return Zend_Navigation
	 */
	public function getNavigation()
	{
		if(!$this->_navigation) {

			$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('global');
			
			if(!$navi = $cache->load('navigation')) {
				$navi = new Zend_Navigation();
		
				$localeTbl = new Core_Model_DbTable_Locale();
				$pageTbl = new Core_Model_DbTable_Page();
				
				$locales = $localeTbl->fetchAll(array(), "locale");
				
				foreach($locales as $locale) {
					
					$page = new Zend_Navigation_Page_Uri(
						array(
							'uri' => '/' . $locale->locale,
							'label' => $locale->locale,
							'locale' => $locale->locale,
						)
					);
					
					$page->setResource("locale");
					$page->setPrivilege('read');
					
					$this->_recurseLocale($page, $locale->locale);
					
					$navi->addPage($page);
					
					
				}
				
				$cache->save($navi, 'navigation');
			}			
			
			
			$this->_navigation = $navi;
			
		}
		
		return $this->_navigation;
						
		
	}
	
	
	protected function _recurseLocale(Zend_Navigation_Page $localePage, $locale)
	{
		
		$pageTbl = new Core_Model_DbTable_Page();

		$pages = $pageTbl->fetchAll(array("parent_id IS NULL", "locale = ?" => $locale), "order_id");
		
		foreach($pages as $pageRow) {
			
			// recurse
			$page = new Zend_Navigation_Page_Uri(
				array(
					'uri' => '/' . $pageRow->beautifurl,
					'label' => $pageRow->title,
					'locale' => $pageRow->locale,
					'id' => $pageRow->id,
					'parent_id' => null,
					'layout' => $pageRow->layout,
					'shard_id' => $pageRow->shard_id,
				)
			);
			
			$page->setResource("Emerald_Page_{$page->id}");
			$page->setPrivilege('read');

			$this->_recursePage($page, $pageRow->id);
			$localePage->addPage($page);
		}
	}
	
	
	protected function _recursePage($parentPage, $pageId)
	{
		$pageTbl = new Core_Model_DbTable_Page();

		$pages = $pageTbl->fetchAll(array("parent_id = ?" => $pageId), "order_id");
		
		foreach($pages as $pageRow) {
			
			// recurse
			$page = new Zend_Navigation_Page_Uri(
				array(
					'uri' => '/' . $pageRow->beautifurl,
					'label' => $pageRow->title,
					'locale' => $pageRow->locale,
					'id' => $pageRow->id,
					'parent_id' => $pageId,
					'layout' => $pageRow->layout,
					'shard_id' => $pageRow->shard_id,
				)
			);
						
			$page->setResource("Emerald_Page_{$page->id}");
			$page->setPrivilege('read');
			
			$this->_recursePage($page, $pageRow->id);
			$parentPage->addPage($page);
			
		}
	}
	
	
	
	
	
	
	
}