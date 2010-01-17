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
			
			$saveUser = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'user', 'action' => 'save', 'label' => 'Save user'));
			$saveUser->setVisible(false);
			$users->addPage($saveUser);			
			
			$deleteUser = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'user', 'action' => 'delete', 'label' => 'Delete user'));
			$deleteUser->setVisible(false);
			$users->addPage($deleteUser);			
			
			$createGroup = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'group', 'action' => 'create', 'label' => 'Create group'));
			$users->addPage($createGroup);			
			
			$editGroup = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'group', 'action' => 'edit', 'label' => 'Edit group'));
			$users->addPage($editGroup);			

			$saveGroup = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'group', 'action' => 'save', 'label' => 'Save group'));
			$saveGroup->setVisible(false);
			$users->addPage($saveGroup);			
			
			$deleteGroup = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'group', 'action' => 'delete', 'label' => 'Delete group'));
			$deleteGroup->setVisible(false);
			$users->addPage($deleteGroup);			
			
			
			$navi->addPage($users);
									
			$locale = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'locale', 'label' => 'Locales'));
			$navi->addPage($locale);

			$updateLocale = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'locale', 'action' => 'update', 'label' => 'Update locales'));
			$locale->addPage($updateLocale);
			
			$sitemap = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'sitemap', 'label' => 'Sitemap'));
			$navi->addPage($sitemap);

			$editSitemap = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'sitemap', 'action' => 'edit', 'label' => 'Edit sitemap'));
			$sitemap->addPage($editSitemap);
						
			$editPage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'edit', 'label' => 'Edit page'));
			$sitemap->addPage($editPage);

			$savePage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'save', 'label' => 'Save page'));
			$savePage->setVisible(false);
			$sitemap->addPage($savePage);

			$deletePage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'delete', 'label' => 'Delete page'));
			$deletePage->setVisible(false);
			$sitemap->addPage($deletePage);
						
			$createPage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'create', 'label' => 'Create page'));
			$sitemap->addPage($createPage);

			$partialPage = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'page', 'action' => 'save-partial', 'label' => 'Save page'));
			$partialPage->setVisible(false);
			$sitemap->addPage($partialPage);
			
			
			$filelib = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'filelib', 'label' => 'Files'));
			$navi->addPage($filelib);

			
			
			$editFolder = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'folder', 'action' => 'edit', 'label' => 'Edit folder'));
			$filelib->addPage($editFolder);

			$deleteFolder = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'folder', 'action' => 'delete', 'label' => 'Delete folder'));
			$filelib->addPage($deleteFolder);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'folder', 'action' => 'save', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);

			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'sitemap', 'action' => 'link-list', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'filelib', 'action' => 'create-folder', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);

			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'filelib', 'action' => 'submit', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'file', 'action' => 'delete', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'filelib', 'action' => 'select', 'label' => 'Select file'));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$forms = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'label' => 'Forms'));
			$navi->addPage($forms);

			$createForm = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'create', 'label' => 'Create form'));
			$forms->addPage($createForm);

			$editForm = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'edit', 'label' => 'Edit form'));
			$forms->addPage($editForm);
						
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'create-post', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'field-create', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'field-delete', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'save', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'action' => 'delete', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$options = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'options', 'label' => 'Options'));
			$navi->addPage($options);

			$optionsSave = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'options', 'action' => 'save-application', 'label' => 'Save application options'));
			$optionsSave->setVisible(false);
			$navi->addPage($optionsSave);

			$optionsSave2 = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'options', 'action' => 'save-locale', 'label' => 'Save locale options'));
			$optionsSave2->setVisible(false);
			$navi->addPage($optionsSave2);
			
			
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
	
