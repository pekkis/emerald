<?php
class Core_Model_Page
{
	
	static public $registry;
	
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
		if(!$page = $this->findFromRegistry($id)) {
			$pageTbl = $this->getTable();
			$page = $pageTbl->find($id)->current();
			$page = ($page) ? new Core_Model_PageItem($page) : false;
			
			if($page) {
				$this->saveToRegistry($page);	
			}
		}
		return $page;
	}
	
	
	public function findAll($where = array(), $order = null, $count = null, $offset = null)
	{
		$rows = $this->getTable()->fetchAll($where, $order, $count, $offset);
		
		$pages = array();
		foreach($rows as $row) {
			$page = new Core_Model_PageItem($row);
			$this->saveToRegistry($page);
			$pages[] = $page;
		}
		
		return new ArrayIterator($pages);
	}
	
	
	public function findByBeautifurl($beautifurl)
	{
		if(!$page = $this->findFromRegistry($beautifurl)) {
			$pageTbl = $this->getTable();
			$row = $pageTbl->fetchRow(array('beautifurl = ?' => $beautifurl));
			$page = ($row) ? new Core_Model_PageItem($row) : false;
			
			if($page) {
				$this->saveToRegistry($page);
			}
		}
		return $page;
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

		if(!is_numeric($page->redirect_id)) {
			$page->redirect_id = null;
		}
		
		
		if(!is_numeric($page->global_id)) {
			$gpm = new Core_Model_DbTable_PageGlobal();
			$gp = $gpm->insert(array());
			$page->global_id = $gp;			
		}
		
		$tbl = $this->getTable();
		
		$row = $tbl->find($page->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($page->toArray());
		$row->save();
		$page->setFromArray($row->toArray());
		
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
	
	
	public function delete(Core_Model_PageItem $page)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($page->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		
		$row->delete();
		$naviModel = new Core_Model_Navigation();
		$naviModel->clearNavigation();
		
	}
	
	
	
	/**
	 * Returns page registry
	 * 
	 * @return Zend_Registry
	 */
	public function getRegistry()
	{
		if(!self::$registry) {
			self::$registry = new Zend_Registry();
		}
		
		return self::$registry;
		
	}
	
	
	public function findFromRegistry($identifier)
	{
		$registry = $this->getRegistry();
		
		if(!is_numeric($identifier)) {
			if(!$registry->isRegistered($identifier)) {
				return false;
			}
			$identifier = $registry->get($identifier);
		}
		
		if($registry->isRegistered($identifier)) {
			return $registry->get($identifier);	
		}
		return false;
	}
	
	
	public function saveToRegistry(Core_Model_PageItem $page)
	{
		$registry = $this->getRegistry();
		$registry->set($page->id, $page);
		$registry->set($page->beautifurl, $page->id);
	}
	
	
}