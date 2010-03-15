<?php
class Core_Model_Locale
{
	
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new Core_Model_DbTable_Locale();
		}
		
		return $table;
	}
	
	
	
	public function updateSiteLocales(array $locales)
	{
		$selectedLocalesRaw = $this->findAll();
		$selectedLocales = array();
		foreach($selectedLocalesRaw as $localeRaw) {
			$selectedLocales[] = $localeRaw->locale;			
		}
			
		$addLocales = array_diff($locales, $selectedLocales);
		// $deleteLocales = array_diff($selectedLocales, $locales);

		try {
			$this->getTable()->getAdapter()->beginTransaction();

			/*
			if($deleteLocales) {
				$this->getTable()->delete($this->getTable()->getAdapter()->quoteInto("locale IN (?)", $deleteLocales));
			}
			*/
			
			foreach($addLocales as $addLocale) {
				$this->getTable()->insert(array('locale' => $addLocale));
			}
			
			$this->getTable()->getAdapter()->commit();

			$naviModel = new Core_Model_Navigation();
			$naviModel->clearNavigation();
			
			return true;
			
		} catch(Exception $e) {
									
			$this->getTable()->getAdapter()->rollBack();
			return false;
		}
		
	}
	
	
	public function getAvailableLocales()
	{
		$locales = array_keys(Zend_Locale::getLocaleList());

		$forbidden = array('root', 'auto', 'environment', 'browser', 'sr_YU', 'und', 'und_ZZ');
		
		$common = array('fi', 'fi_FI', 'sv', 'sv_SE', 'en', 'en_US', 'en_UK');
		
		$availables = array();

		foreach($locales as $locale) {

			if(!in_array($locale, $forbidden)) {
				$available = new stdClass();
				$available->locale = $locale;
				$available->class = (in_array($locale, $common)) ? 'common' : 'uncommon';
			}
						
			$availables[] = $available;			
			
		}
		
		ksort($availables);
		
		return $availables;
		
	}
	
	
	
	
	public function startFrom(Emerald_Application_Customer $customer, $locale = null)
	{
			
								
		
		if($locale) {
			$locale = $this->find($locale);
						
			
			if(!$locale) {
				throw new Emerald_Model_Exception('Invalid locale');
			}
			
		} else {
			$locale = $this->findDefault($customer);
			
			if(!$locale) {
				$locale = $this->findAny($customer);
				
				if(!$locale) {
					throw new Emerald_Model_Exception('No locales', 404);
				}
				
				$this->setDefault($customer, $locale);
				
			}
			
		}			

		
		$page = $this->findDefaultPage($customer, $locale);
		
		return $page;
	}

	
	
	public function findDefaultPage(Emerald_Application_Customer $customer, Core_Model_LocaleItem $locale)
	{
		$pageModel = new Core_Model_Page();
		
				
		if($ps = $locale->getOption('page_start')) {
			$page = $pageModel->find($ps);
		} else {
			$page = $pageModel->findAny($locale);
			
			if(!$page) {
				throw new Emerald_Model_Exception('No pages');
			}
			
			$this->setDefaultPage($customer, $locale, $page);
			
		}		
				
		return $page;
		
	}
	
	
	
	public function setDefaultPage(Emerald_Application_Customer $customer, Core_Model_LocaleItem $locale, Core_Model_PageItem $page)
	{
		$locale->page_start = $page->id;
		return $this->save($locale);
	}
	
	
	
	
	
	
	public function find($id)
	{
		$localeTbl = $this->getTable();
		$localeRow = $localeTbl->find($id)->current();
		return ($localeRow) ? new Core_Model_LocaleItem($localeRow->toArray()) : false;
	}
	
	
	public function findDefault(Emerald_Application_Customer $customer)
	{
		$defaultLocale = $customer->getOption('default_locale');
		return ($defaultLocale) ? $this->find($defaultLocale) : false;
	}
	
	
	public function findAny(Emerald_Application_Customer $customer)
	{
		$localeTbl = $this->getTable();
		$localeRow = $localeTbl->fetchRow();
		return ($localeRow) ? new Core_Model_LocaleItem($localeRow) : false;
	}
	
	
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'locale ASC');
		$locales = new ArrayIterator();
		foreach($rows as $row) {
			$locales->append(new Core_Model_LocaleItem($row));
		}
		
		return $locales;
		
	}
	
	
	public function setDefault(Emerald_Application_Customer $customer, Core_Model_LocaleItem $locale)
	{
		return $customer->setOption('default_locale', $locale);	
	}
	
	
	public function save(Core_Model_LocaleItem $locale, $permissions = null)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($locale->locale)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($locale->toArray());
		$row->save();
		
		
		if($permissions) {
			
			$tbl = new Core_Model_DbTable_Permission_Locale_Ugroup();
			$tbl->getAdapter()->beginTransaction();
			$tbl->delete($tbl->getAdapter()->quoteInto("locale_locale = ?", $locale->locale));
			
			foreach($permissions as $key => $data) {
				if($data) {
					$sum = array_sum($data);
					$tbl->insert(array('locale_locale' => $locale->locale, 'ugroup_id' => $key, 'permission' => $sum));
				}
			}
			
			$tbl->getAdapter()->commit();
			
		}
		
		$acl = Zend_Registry::get('Emerald_Acl');
		if($acl->has($locale)) {
			$acl->remove($locale);	
		}
				
		
		
		
	}
	
	
	public function delete(Core_Model_LocaleItem $locale)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($locale->locale)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
	}
	
	
	public function getPermissions(Core_Model_LocaleItem $locale)
	{
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();
				
		$permissions = Emerald_Permission::getAll();

		$perms = array();
		
		$acl = Zend_Registry::get('Emerald_Acl');
						
		
		foreach($groups as $group) {
			foreach($permissions as $permKey => $permName) {
				if($acl->isAllowed($group, $locale, $permName)) {
					$perms[$group->id][] = $permKey;
				}	
			}
		}
		
		return $perms;
	}
	
	
	
	
}