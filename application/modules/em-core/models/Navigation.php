<?php
class EmCore_Model_Navigation
{

	private $_navigation;
	
	private $_pageModel;
	
	
	/**
	 * Returns page model
	 * 
	 * @return EmCore_Model_Page
	 */
	public function getPageModel()
	{
		if(!$this->_pageModel) {
			$this->_pageModel = new EmCore_Model_Page();	
		}
		return $this->_pageModel;
		
	}
	
	
	
	public function pageUpdate(EmCore_Model_PageItem $page)
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
		
		$beautifurler = $page->getLocaleItem()->getOption('beautifurler');
		if(!$beautifurler) {
			$beautifurler = 'Default';
		}
				
		$beautifurler = Emerald_Beautifurl::factory($beautifurler);
		$beautifurl = $beautifurler->fromArray($beautifurl, $page->locale);
		
		$navi->url = EMERALD_URL_BASE . "/" . $beautifurl;

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
		$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
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

			$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
			
			if(!$navi = $cache->load('navigation')) {
												
				$navi = new Zend_Navigation();
		
								
				$localeModel = new EmCore_Model_Locale();
				
				$pageTbl = new EmCore_Model_DbTable_Page();
				
				$locales = $localeModel->findAll();
								
				foreach($locales as $locale) {
					
					$page = new Zend_Navigation_Page_Uri(
						array(
							'uri' => EMERALD_URL_BASE . '/' . $locale->locale,
							'label' => $locale->locale,
							'locale' => $locale->locale,
							'locale_root' => $locale->locale,
							'cache_seconds' => 0,
						)
					);
					
					
					if($startPage = $locale->getOption('page_start')) {
						$startPage = $pageTbl->find($startPage)->current();
						if($startPage) {
							$page->uri = EMERALD_URL_BASE . '/' . $startPage->beautifurl;
							$page->cache_seconds = $startPage->cache_seconds;						
						}
					}
					
					
					$page->setResource("Emerald_Locale_{$locale->locale}");
					$page->setPrivilege('read');
					$page->setVisible(true);
					
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
		$pageModel = new EmCore_Model_Page();
		
		$pages = $pageModel->findAll(array("parent_id IS NULL", "locale = ?" => $locale), "order_id");
				
		foreach($pages as $page) {

			// recurse
			$pageRes = new Zend_Navigation_Page_Uri(
				array(
					'uri' => EMERALD_URL_BASE . '/' . $page->beautifurl,
					'label' => $page->title,
					'locale' => $page->locale,
					'id' => $page->id,
					'global_id' => $page->global_id,
					'parent_id' => null,
					'layout' => $page->layout,
					'shard_id' => $page->shard_id,
					'cache_seconds' => $page->cache_seconds,
				)
			);

			if($page->redirect_id) {
				$redirectPage = $pageModel->find($page->redirect_id);
				$pageRes->redirect_uri = EMERALD_URL_BASE . '/' . $redirectPage->beautifurl; 
			}
			
			$pageRes->setResource("Emerald_Page_{$page->id}");
			$pageRes->setPrivilege('read');
			$pageRes->setVisible($page->visibility);

			$this->_recursePage($pageRes, $pageRes->id);
			$localePage->addPage($pageRes);
		}
	}
	
	
	protected function _recursePage($parentPage, $pageId)
	{
		$pageModel = new EmCore_Model_Page();
		
		$pages = $pageModel->findAll(array("parent_id = ?" => $pageId), "order_id");
				
		foreach($pages as $page) {

			// recurse
			$pageRes = new Zend_Navigation_Page_Uri(
				array(
					'uri' => EMERALD_URL_BASE . '/' . $page->beautifurl,
					'label' => $page->title,
					'locale' => $page->locale,
					'id' => $page->id,
					'global_id' => $page->global_id,
					'parent_id' => null,
					'layout' => $page->layout,
					'shard_id' => $page->shard_id,
					'cache_seconds' => $page->cache_seconds,
				)
			);

			if($page->redirect_id) {
				$redirectPage = $pageModel->find($page->redirect_id);
				$pageRes->redirect_uri = EMERALD_URL_BASE . '/' . $redirectPage->beautifurl; 
			}
			
			$pageRes->setResource("Emerald_Page_{$page->id}");
			$pageRes->setPrivilege('read');
			$pageRes->setVisible($page->visibility);

			$this->_recursePage($pageRes, $pageRes->id);
			$parentPage->addPage($pageRes);
		}
	}
	
	
}
