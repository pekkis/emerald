<?php
/**
 * Locale model
 *
 * @author pekkis
 *
 */
class EmCore_Model_Locale extends Emerald_Model_Cacheable
{

    protected static $_table = 'EmCore_Model_DbTable_Locale';

    /**
     * Updates site locales
     *
     * @param array $locales Array of locales
     * @return boolean Success or not
     */
    public function updateSiteLocales(array $locales)
    {
        $selectedLocalesRaw = $this->findAll();
        $selectedLocales = array();
        foreach($selectedLocalesRaw as $localeRaw) {
            $selectedLocales[] = $localeRaw->locale;
        }
        	
        $addLocales = array_diff($locales, $selectedLocales);

        try {
            $this->getTable()->getAdapter()->beginTransaction();
            	
            foreach($addLocales as $addLocale) {
                $this->getTable()->insert(array('locale' => $addLocale));
            }
            	
            $this->getTable()->getAdapter()->commit();

            $naviModel = new EmCore_Model_Navigation();
            $naviModel->clearNavigation();
            	
            return true;
            	
        } catch(Exception $e) {
            	
            $this->getTable()->getAdapter()->rollBack();
            return false;
        }

    }


    /**
     * Returns all available locales.
     *
     * @return array Array of locale.
     */
    public function getAvailableLocales()
    {
        $locales = array_keys(Zend_Locale::getLocaleList());

        // Filter evil locales.
        // @todo Filteriterator?
        $forbidden = array('root', 'auto', 'environment', 'browser', 'sr_YU', 'und', 'und_ZZ');
        $availables = array();
        foreach($locales as $locale) {
            if(!in_array($locale, $forbidden)) {
                $available = new stdClass();
                $available->locale = $locale;
            }
            $availables[] = $available;
        }

        ksort($availables);
        return $availables;

    }




    /**
     * Find a start page for customer
     *
     * @param Emerald_Application_Customer $customer Customer
     * @param EmCore_Model_Locale $locale Locale
     * @throws Emerald_Model_Exception
     * @return EmCore_Model_PageItem
     */
    public function startFrom(Emerald_Application_Customer $customer, $locale = null)
    {
        // If locale, use it, if no locale, try to find one somehow.
        if($locale) {
            $locale = $this->find($locale);
            if(!$locale) {
                throw new Emerald_Model_Exception('Invalid locale');
            }
        } else {
            $locale = $this->findDefault($customer);
            if(!$locale) {
                // Last resort: find locale, ANY locale and set it default.
                $locale = $this->findAny($customer);
                if(!$locale) {
                    throw new Emerald_Model_Exception('No locales', 404);
                }
                $this->setDefault($customer, $locale);
            }
        }
        // Find default page for any found locale.
        $page = $this->findDefaultPage($customer, $locale);
        return $page;
    }



    /**
     * Finds and returns a default page for a locale.
     *
     * @param Emerald_Application_Customer $customer Customer
     * @param EmCore_Model_LocaleItem $locale Locale
     * @throws Emerald_Model_Exception
     * @return EmCore_Model_PageItem
     */
    public function findDefaultPage(Emerald_Application_Customer $customer, EmCore_Model_LocaleItem $locale)
    {
        $pageModel = new EmCore_Model_Page();

        // Use locale option if available, if not then find any and set it default.
        if($ps = $locale->getOption('page_start')) {
            $page = $pageModel->find($ps);
        } else {
            $page = $pageModel->findAny($locale);
            if(!$page) {
                throw new Emerald_Model_Exception('No pages');
            }
            $locale->setOption('page_start', $page->id);
        }
        return $page;

    }


    public function find($id)
    {
        if(!$ret = $this->findCached($id)) {
            $localeTbl = $this->getTable();
            $localeRow = $localeTbl->find($id)->current();
            $ret = ($localeRow) ? new EmCore_Model_LocaleItem($localeRow->toArray()) : false;
            if($ret) {
                $this->storeCached($id, $ret);
            }
        }
        return $ret;
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
        return ($localeRow) ? new EmCore_Model_LocaleItem($localeRow) : false;
    }


    public function findAll()
    {
        $rows = $this->getTable()->fetchAll(array(), 'locale ASC');
        $locales = new ArrayIterator();
        foreach($rows as $row) {
            $locales->append(new EmCore_Model_LocaleItem($row));
        }
        return $locales;
    }


    public function setDefault(Emerald_Application_Customer $customer, EmCore_Model_LocaleItem $locale)
    {
        return $customer->setOption('default_locale', $locale);
    }


    public function save(EmCore_Model_LocaleItem $locale, $permissions = null)
    {
        $tbl = $this->getTable();
        $row = $tbl->find($locale->locale)->current();
        if(!$row) {
            $row = $tbl->createRow();
        }
        $row->setFromArray($locale->toArray());
        $row->save();

        if($permissions) {
            	
            $tbl = new EmCore_Model_DbTable_Permission_Locale_Ugroup();
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

        $this->clearCached($locale->locale);

        $acl = Zend_Registry::get('Emerald_Acl');
        if($acl->has($locale)) {
            $acl->remove($locale);
        }

    }


    public function delete(EmCore_Model_LocaleItem $locale)
    {
        $this->clearCached($locale->id);
        $tbl = $this->getTable();
        $row = $tbl->find($locale->locale)->current();
        if(!$row) {
            throw new Emerald_Model_Exception('Could not delete');
        }
        $row->delete();

        $acl = Zend_Registry::get('Emerald_Acl');
        if($acl->has($locale)) {
            $acl->remove($locale);
        }
    }


    public function getPermissions(EmCore_Model_LocaleItem $locale)
    {
        $groupModel = new EmCore_Model_Group();
        $groups = $groupModel->findAll();

        $permissions = Emerald_Cms_Permission::getAll();

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