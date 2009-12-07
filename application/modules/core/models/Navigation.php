<?php
class Core_Model_Navigation
{

	private $_navigation;
	
	
	/**
	 * Returns the whole site navi
	 * 
	 * @return Zend_Navigation
	 */
	public function getNavigation()
	{
		if(!$this->_navigation) {
									
			$navi = new Zend_Navigation();
	
			$localeTbl = new Core_Model_DbTable_Locale();
			$pageTbl = new Core_Model_DbTable_Page();
			
			$locales = $localeTbl->fetchAll(array(), "locale");
			
			foreach($locales as $locale) {
				
				$page = new Zend_Navigation_Page_Uri(
					array(
						'uri' => '/' . $locale->locale,
						'label' => $locale->locale,
					)
				);
				
				$this->_recurseLocale($page, $locale->locale);
				
				$navi->addPage($page);
				
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
					'uri' => '/' . $pageRow->iisiurl,
					'label' => $pageRow->title,
				)
			);
									
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
					'uri' => '/' . $pageRow->iisiurl,
					'label' => $pageRow->title,
				)
			);
						
			
			$this->_recursePage($page, $pageRow->id);
			$parentPage->addPage($page);
			
		}
	}
	
	
	
	
	
	
	
}