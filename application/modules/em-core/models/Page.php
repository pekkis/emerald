<?php
class EmCore_Model_Page extends Emerald_Model_Cacheable
{
	
	protected $_beautifurls;
	
	protected static $_table = 'EmCore_Model_DbTable_Page';
	
	public function getCachedBeautifurls()
	{
		if(!$this->_beautifurls) {
			$this->_beautifurls = $this->findCached('beautifurls');
			if(!$this->_beautifurls) {
				$this->_beautifurls = array();
			}			
		}
		return $this->_beautifurls;
	}
	
	
	public function setCachedBeautifurls(array $beautifurls)
	{
		$this->_beautifurls = $beautifurls;
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
		if(!$page = $this->findCached($id)) {
			$pageTbl = $this->getTable();
			$page = $pageTbl->find($id)->current();
			$page = ($page) ? new EmCore_Model_PageItem($page) : false;
			if($page) {
				$this->storeCached($page->id, $page);
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
	

	public function findGlobal($globalId, $locale)
	{
		if(!$id = $this->findCached('page_global_' . $globalId . '_locale_' . $locale)) {
			$pageTbl = $this->getTable();
			$id = $pageTbl->getAdapter()->fetchOne("SELECT id FROM emerald_page WHERE global_id = ? AND locale = ?", array($globalId, $locale));
			if(!$id) {
				return false;
			}
			
			$this->storeCached('page_global_' . $globalId . '_locale_' . $locale, $id);
		}	

		return $this->find($id);

	}
	
	
	
	
	public function findAny($locale = null)
	{
		$pageTbl = $this->getTable();
		
		$sel = $pageTbl->select();

		if($locale) {
			$sel->where("locale = ?", $locale);
		}
		
		$sel->order("id ASC");
		
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
					
					try {
						$tbl->insert(array('page_id' => $page->id, 'ugroup_id' => $key, 'permission' => $sum));	
					} catch(Exception $e) {
						echo $e;
						die();
					}
					
				}
			}
			
			$tbl->getAdapter()->commit();
			
						
		}
		
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

		$this->storeCached($page->id, $page);
		$this->storeCached('page_global_' . $page->global_id . '_locale_' . $page->locale, $page->id);
		$this->clearCached('page_global_' . $page->global_id);
		
		$acl = Zend_Registry::get('Emerald_Acl');
		if($acl->has($page)) {
			$acl->remove($page);	
		}
		
		$this->getCache()->remove('Emerald_PageRoutes');

				
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
		
		$this->clearCached($page->id, $page);
		$this->clearCached('page_global_' . $page->global_id . '_locale_' . $page->locale);
		$this->clearCached('page_global_' . $page->global_id);
		
	}
	
	
	
}