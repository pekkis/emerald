<?php
class Core_Model_Page
{
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
		$pageTbl = $this->getTable();
		$page = $pageTbl->find($id)->current();
		return ($page) ? new Core_Model_PageItem($page) : false;
			
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