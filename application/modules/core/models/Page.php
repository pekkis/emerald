<?php
class Core_Model_Page
{
	private static $_pages = array();
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
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
	
	
	
	
	public function save(Core_Model_PageItem $page, array $permissions = array())
	{
		if(!is_numeric($page->parent_id)) {
			$page->parent_id = null;
		}
				
		if(!is_numeric($page->id)) {
			$page->id = null;
		}
		
		$tbl = $this->getTable();
		
		$row = $tbl->find($page->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($page->toArray());
		$row->save();
		
		
		if($permissions) {
			
			$tbl = new Core_Model_DbTable_Permission_Page_Ugroup();
						
			$tbl->getAdapter()->beginTransaction();
			
			$tbl->delete($tbl->getAdapter()->quoteInto("page_id = ?", $page->id));
			
			foreach($permissions as $key => $data) {
				if($data) {
					$sum = array_sum($data);
					$tbl->insert(array('page_id' => $page->id, 'ugroup_id' => $key, 'permission' => $sum));
				}
			}
			
			$tbl->getAdapter()->commit();
			
		}
		

		$naviModel = new Core_Model_Navigation();
		$naviModel->pageUpdate($page);
				
	}
	
	
	
	public function getPermissions(Core_Model_PageItem $page)
	{
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();
				
		$permissions = Emerald_Permission::getAll();

		$perms = array();
		
		$acl = Zend_Registry::get('Emerald_Acl');
		
		foreach($groups as $group) {
			foreach($permissions as $permKey => $permName) {
				if($acl->isAllowed($group, $page, $permName)) {
					$perms[$group->id][] = $permKey;
				}	
			}
		}
		
		return $perms;
	}
	
	
	
}