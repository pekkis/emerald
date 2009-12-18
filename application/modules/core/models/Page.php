<?php
class Core_Model_Page
{
	private static $_pages = array();
	
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new Core_Model_DbTable_Page();
		}
		return $table;
	}
		
	
	
	public function find($id)
	{
		
		if(isset(self::$_pages[$id])) {
			return self::$_pages[$id];
		}
		
		$pageTbl = $this->getTable();
		$page = $pageTbl->find($id)->current();
				
		$page = ($page) ? new Core_Model_PageItem($page) : false;
		
		self::$_pages[$id] = $page;
		
		return $page;
		
	}
	
	
	public function findByBeautifurl($beautifurl)
	{
		$pageTbl = $this->getTable();
		$page = $pageTbl->fetchRow(array('beautifurl = ?' => $beautifurl));
		return ($page) ? new Core_Model_PageItem($page) : false;
		
	}
	
	
	
	public function findAny($locale = null)
	{
		$pageTbl = $this->getTable();
		
		$sel = $pageTbl->select();

		if($locale) {
			$sel->where("locale = ?", $locale);
		}
		
		$page = $pageTbl->fetchRow($sel);
		return ($page) ? new Core_Model_PageItem($page) : false;
		
	}
	
	
}