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
		$deleteLocales = array_diff($selectedLocales, $locales);

		try {
			$this->getTable()->getAdapter()->beginTransaction();
				
			if($deleteLocales) {
				$this->getTable()->delete($this->getTable()->getAdapter()->quoteInto("locale IN (?)", $deleteLocales));
			}
			foreach($addLocales as $addLocale) {
				$this->getTable()->insert(array('locale' => $addLocale));
			}
			
			$this->getTable()->getAdapter()->commit();
			
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
					throw new Emerald_Model_Exception('No locales');
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
		
				
		if($locale->page_start) {
			$page = $pageModel->find($locale->page_start);
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
	
	
	public function save(Core_Model_LocaleItem $locale)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($locale->locale)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($locale->toArray());
		$row->save();
	}
	
	
	
}