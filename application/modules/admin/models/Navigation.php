<?php
class Admin_Model_Navigation
{

	private $_navigation;
	
	
	
	/**
	 * Returns the whole site navi
	 * 
	 * @return Zend_Navigation
	 */
	public function getNavigation()
	{
		if(!$this->_navigation) {
			
			$navi = new Zend_Navigation();
			
			$dashboard = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'label' => 'Dashboard'));
									
			$navi->addPage($dashboard);

			$users = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'user', 'label' => 'Users & groups'));
			
			$createUser = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'user', 'action' => 'create', 'label' => 'Create user'));
			$users->addPage($createUser);			

			$editUser = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'user', 'action' => 'edit', 'label' => 'Edit user'));
			$users->addPage($editUser);			
			
			$createGroup = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'group', 'action' => 'create', 'label' => 'Create group'));
			$users->addPage($createGroup);			
			
			$editGroup = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'group', 'action' => 'edit', 'label' => 'Edit group'));
			$users->addPage($editGroup);			
			
			$navi->addPage($users);
									
			$locale = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'locale', 'label' => 'Locales'));
			$navi->addPage($locale);
						
			$sitemap = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'sitemap', 'label' => 'Sitemap'));
			$navi->addPage($sitemap);

			$editSitemap = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'sitemap', 'action' => 'edit', 'label' => 'Edit sitemap'));
			$sitemap->addPage($editSitemap);
						
			$editPage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'edit', 'label' => 'Edit page'));
			$sitemap->addPage($editPage);

			$createPage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'create', 'label' => 'Create page'));
			$sitemap->addPage($createPage);
						
			$filelib = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'filelib', 'label' => 'Files'));
			$navi->addPage($filelib);

			
			
			$editFolder = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'folder', 'action' => 'edit', 'label' => 'Edit folder'));
			$filelib->addPage($editFolder);
						
			$forms = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'label' => 'Forms'));
			$navi->addPage($forms);

			$createForm = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'create', 'label' => 'Create form'));
			$forms->addPage($createForm);

			$editForm = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'edit', 'label' => 'Edit form'));
			$forms->addPage($editForm);
						
			$options = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'options', 'label' => 'Options'));
			$navi->addPage($options);

			/* invisibles */
			
			$cache = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'cache', 'action' => 'clear', 'label' => 'Cache'));
			$cache->setVisible(false);
			$navi->addPage($cache);

			$about = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'about', 'action' => 'index', 'label' => 'About'));
			$about->setVisible(false);
			$navi->addPage($about);
			
			
			$this->_navigation = $navi;
			
		}
		
		return $this->_navigation;
						
		
	}
	
	
}
	
