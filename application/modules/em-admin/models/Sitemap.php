<?php
class EmAdmin_Model_Sitemap
{
	
	private $_pageModel;
	
	/**
	 * @return EmCore_Model_Page
	 */
	public function getPageModel()
	{
		if(!$this->_pageModel) {
			$this->_pageModel = new EmCore_Model_Page();
		}
		return $this->_pageModel;
	}
	
	
	
	public function mirror(EmCore_Model_PageItem $page, $action)
	{
		
		if($action == 'edit') {
			
			// @todo: whatthe fuck do here
			
		} else {
			
			$localeModel = new EmCore_Model_Locale();
			$locales = $localeModel->findAll();
			
			if($page->parent_id) {

				$parentPage = $this->getPageModel()->find($page->parent_id);
				$parentSiblings = $this->getPageModel()->findSiblings($parentPage);				
				
				foreach($locales as $locale) {
					if($locale != $page->locale) {
						foreach($parentSiblings as $sibling) {
							if($sibling->locale == $locale) {
								$this->_copyChild($page, $sibling, $locale);
							}	
						}
					}
				}
				
			} else {
				foreach($locales as $locale) {
					if($locale != $page->locale) {
						$this->_copyRoot($page, $locale->locale);	
					}
				}
				
			}
			
		}
		
	}
	
	
	
	
	public function copySitemap($from, $to)
	{
		$pageModel = $this->getPageModel();
		$pages = $pageModel->findAll(array('locale = ?' => $from, 'parent_id IS NULL'));
		foreach($pages as $page) {
			$this->_copyRoot($page, $to);
		}
	}
	
	
	
	private function _copyRoot(EmCore_Model_PageItem $page, $to)
	{
		$pageModel = $this->getPageModel();
		$copyPage = clone $page;
		$copyPage->locale = $to;

		$arr = $copyPage->toArray();
		unset($arr['id']);
		
		$copyPage = new EmCore_Model_PageItem($arr);
		$this->getPageModel()->save($copyPage, $this->getPageModel()->getPermissions($page));		
						
		foreach($this->getPageModel()->findChildren($page) as $page) {
			$this->_copyChild($page, $copyPage, $to);
		}
		
	}
	
	
	private function _copyChild(EmCore_Model_PageItem $page, EmCore_Model_PageItem $parentCopyPage, $to)
	{
		$pageModel = $this->getPageModel();
		$copyPage = clone $page;

		// $copyPage->beautifurl = preg_replace("/^({$page->locale})(.*)$/", "{$to}$2", $copyPage->beautifurl);
		$copyPage->locale = $to;
		$copyPage->parent_id = $parentCopyPage->id;
				
		$arr = $copyPage->toArray();
		unset($arr['id']);
		
		$copyPage = new EmCore_Model_PageItem($arr);
		$this->getPageModel()->save($copyPage, $this->getPageModel()->getPermissions($page));
		
		foreach($this->getPageModel()->findChildren($page) as $page) {
			$this->_copyChild($page, $copyPage, $to);
		}
		
	}
	
	
	
	
	
}