<?php
class EmCore_Model_Page extends Emerald_Model_Cacheable
{
	
	static public $registry;
	
	protected $_beautifurls;
	
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_Page();
		}
		return $table;
	}

	
	public function getCachedBeautifurls()
	{
		if(!$this->_beautifurls) {
			$this->_beautifurls = $this->findCached('beautifurls');
			if(!$this->_beautifurls) {
				$this->_beautifurls = array();
			}			
		}
		
		return $this;
	}
	
	
	public function storeCachedBeautifurls()
	{
		$this->storeCached('beautifurls', $this->_beautifurls);
		
		return $this;
	}
	
	
	public function clearCachedBeautifurls()
	{
		$this->clearCached('beautifurls');
		
		return $this;
	}
	
	
	
	public function find($id)
	{
		if(!$page = $this->findFromRegistry($id)) {
			
			if(!$page = $this->findCached($id)) {
				$pageTbl = $this->getTable();
				$page = $pageTbl->find($id)->current();
				$page = ($page) ? new EmCore_Model_PageItem($page) : false;
				
				if($page) {
					$this->storeCached($page->id, $page);
				}
			}
						
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
			$page = new EmCore_Model_PageItem($row);
			$this->saveToRegistry($page);
			$pages[] = $page;
		}
		
		return new ArrayIterator($pages);
	}
	
	
	public function findByBeautifurl($beautifurl)
	{
		
			
		$this->getCachedBeautifurls();

		if(isset($this->_beautifurls[$beautifurl])) {
			return $this->find($this->_beautifurls[$beautifurl]);
		} else {
			$pageTbl = $this->getTable();
			$id = $pageTbl->getAdapter()->fetchOne("SELECT id FROM emerald_page WHERE beautifurl = ?", array($beautifurl));

			if(!$id) {
				return false;
			}
			
			$this->_beautifurls[$beautifurl] = $id;
			$this->storeCachedBeautifurls();
			
			return $this->find($id);
		}

	}
	
	
	
	public function findAny($locale = null)
	{
		$pageTbl = $this->getTable();
		
		$sel = $pageTbl->select();

		if($locale) {
			$sel->where("locale = ?", $locale);
		}
		
		$page = $pageTbl->fetchRow($sel);
		return ($page) ? new EmCore_Model_PageItem($page) : false;
		
	}
	
	
	
	
	public function save(EmCore_Model_PageItem $page, array $permissions = array())
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
			$gpm = new EmCore_Model_DbTable_PageGlobal();
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
			
			$tbl = new EmCore_Model_DbTable_Permission_Page_Ugroup();
						
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
		
		$this->storeCached($page->id, $page);
		$this->storeCached('page_global_' . $page->global_id, $page);
		$this->getCachedBeautifurls();
		foreach($this->_beautifurls as $key => $id) {
			if($id == $page->id) {
				unset($this->_beautifurls[$key]);
				$this->storeCachedBeautifurls();
				break;
			}
		}		

		$naviModel = new EmCore_Model_Navigation();
		$naviModel->pageUpdate($page);
		
		$acl = Zend_Registry::get('Emerald_Acl');
		if($acl->has($page)) {
			$acl->remove($page);	
		}
				
	}
	
	
	
	public function findSiblings(EmCore_Model_PageItem $page)
	{
		if(!$siblings = $this->findCached('page_global_' . $page->global_id)) {
			$pageTbl = $this->getTable();
			$siblings = $pageTbl->getAdapter()->fetchCol("SELECT id FROM emerald_page WHERE global_id = ?", array($page->global_id), "locale ASC");
			$this->storeCached('page_global_' . $page->global_id, $siblings);
		}
		
		$pages = array();
		foreach($siblings as $sid) {
			$pages[] = $this->find($sid);
		}
		return new ArrayIterator($pages);
	
	}
	
	
	public function findChildren(EmCore_Model_PageItem $page)
	{
		$tbl = $this->getTable();
		
		$res = $tbl->fetchAll(array('parent_id = ?' => $page->id), 'order_id ASC');
		
		$pages = array();
		foreach($res as $row) {
			$pages[] = new EmCore_Model_PageItem($row->toArray());
		}
		
		return new ArrayIterator($pages);
		
	}
	
	
	
	
	
	public function getPermissions(EmCore_Model_PageItem $page)
	{
		$groupModel = new EmCore_Model_Group();
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
	
	
	public function delete(EmCore_Model_PageItem $page)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($page->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		
		$this->clearCached($page->id);
		
		$row->delete();
		$naviModel = new EmCore_Model_Navigation();
		$naviModel->clearNavigation();
		
		$acl = Zend_Registry::get('Emerald_Acl');
		if($acl->has($page)) {
			$acl->remove($page);	
		}
		
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
	
	
	public function saveToRegistry(EmCore_Model_PageItem $page)
	{
		$registry = $this->getRegistry();
		$registry->set($page->id, $page);
		$registry->set($page->beautifurl, $page->id);
	}
	
	
}