<?php
class EmAdmin_Model_Navigation
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
			
			/* dashboard */
			
			$dashboard = new Zend_Navigation_Page_Mvc(
				array(
					'module' => 'em-admin',
					'label' => 'Dashboard',
					'resource' => 'Emerald_Activity_administration___expose',
				)
			);
			$navi->addPage($dashboard);
						
			$editActivity = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_activities', 'module' => 'em-admin', 'controller' => 'activity', 'action' => 'edit', 'label' => 'Edit activities'));
			$dashboard->addPage($editActivity);

			$saveActivity = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_activities', 'module' => 'em-admin', 'controller' => 'activity', 'action' => 'save', 'label' => 'Save activities'));
			$saveActivity->setVisible(false);
			$dashboard->addPage($saveActivity);
			
			/* users */
			
			$users = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'user', 'label' => 'Users & groups'));
			
			$createUser = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'user', 'action' => 'create', 'label' => 'Create user'));
			$users->addPage($createUser);			

			$editUser = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'user', 'action' => 'edit', 'label' => 'Edit user'));
			$users->addPage($editUser);			
			
			$saveUser = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'user', 'action' => 'save', 'label' => 'Save user'));
			$saveUser->setVisible(false);
			$users->addPage($saveUser);			
			
			$deleteUser = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'user', 'action' => 'delete', 'label' => 'Delete user'));
			$deleteUser->setVisible(false);
			$users->addPage($deleteUser);			
			
			$createGroup = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'group', 'action' => 'create', 'label' => 'Create group'));
			$users->addPage($createGroup);			
			
			$editGroup = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'group', 'action' => 'edit', 'label' => 'Edit group'));
			$users->addPage($editGroup);			

			$saveGroup = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'group', 'action' => 'save', 'label' => 'Save group'));
			$saveGroup->setVisible(false);
			$users->addPage($saveGroup);			
			
			$deleteGroup = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_users', 'module' => 'em-admin', 'controller' => 'group', 'action' => 'delete', 'label' => 'Delete group'));
			$deleteGroup->setVisible(false);
			$users->addPage($deleteGroup);			
						
			$navi->addPage($users);

			/* locale */
			
			$locale = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_locales', 'module' => 'em-admin', 'controller' => 'locale', 'label' => 'Locales'));
			$navi->addPage($locale);
			
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_locales', 'module' => 'em-admin', 'controller' => 'locale', 'action' => 'delete', 'label' => 'Delete locale'));
			$page->setVisible(false);
			$locale->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_locales', 'module' => 'em-admin', 'controller' => 'locale', 'action' => 'edit', 'label' => 'Edit locale'));
			$page->setVisible(true);
			$locale->addPage($page);

			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_locales', 'module' => 'em-admin', 'controller' => 'locale', 'action' => 'save', 'label' => 'Save locale'));
			$page->setVisible(false);
			$locale->addPage($page);
						
			$updateLocale = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_locales', 'module' => 'em-admin', 'controller' => 'locale', 'action' => 'update', 'label' => 'Update locales'));
			$locale->addPage($updateLocale);
			
			/* sitemap */
						
			$sitemap = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'sitemap', 'label' => 'Sitemap'));
			$navi->addPage($sitemap);

			$editSitemap = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'sitemap', 'action' => 'edit', 'label' => 'Edit sitemap'));
			$sitemap->addPage($editSitemap);

			$copySitemap = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'sitemap', 'action' => 'copy-from', 'label' => 'Copy sitemap from another locale'));
			$copySitemap->setVisible(false);
			$sitemap->addPage($copySitemap);
			
			$editPage = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'page', 'action' => 'edit', 'label' => 'Edit page'));
			$sitemap->addPage($editPage);

			$savePage = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'page', 'action' => 'save', 'label' => 'Save page'));
			$savePage->setVisible(false);
			$sitemap->addPage($savePage);

			$deletePage = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'page', 'action' => 'delete', 'label' => 'Delete page'));
			$deletePage->setVisible(false);
			$sitemap->addPage($deletePage);
						
			$createPage = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'page', 'action' => 'create', 'label' => 'Create page'));
			$sitemap->addPage($createPage);

			$partialPage = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'page', 'action' => 'save-partial', 'label' => 'Save page'));
			$partialPage->setVisible(false);
			$sitemap->addPage($partialPage);

			/* filelib */			
			
			$filelib = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'filelib', 'label' => 'Files'));
			$navi->addPage($filelib);
			
			$editFolder = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'folder', 'action' => 'edit', 'label' => 'Edit folder'));
			$filelib->addPage($editFolder);

			$deleteFolder = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'folder', 'action' => 'delete', 'label' => 'Delete folder'));
			$filelib->addPage($deleteFolder);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'folder', 'action' => 'save', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);

			$page = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'sitemap', 'action' => 'link-list', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'filelib', 'action' => 'create-folder', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);

			$page = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'filelib', 'action' => 'submit', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'file', 'action' => 'delete', 'label' => ''));
			$page->setVisible(false);
			$filelib->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('module' => 'em-admin', 'controller' => 'filelib', 'action' => 'select', 'label' => 'Select file'));
			$page->setVisible(false);
			$filelib->addPage($page);

			/* forms */
			
			$forms = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'label' => 'Forms'));
			$navi->addPage($forms);

			$createForm = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'create', 'label' => 'Create form'));
			$forms->addPage($createForm);

			$editForm = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'edit', 'label' => 'Edit form'));
			$forms->addPage($editForm);
						
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'create-post', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'field-create', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'field-delete', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'save', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			$page = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_forms', 'module' => 'em-admin', 'controller' => 'form', 'action' => 'delete', 'label' => ''));
			$page->setVisible(false);
			$forms->addPage($page);
			
			/* options */
			
			$options = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_options', 'module' => 'em-admin', 'controller' => 'options', 'label' => 'Options'));
			$navi->addPage($options);

			$optionsSave = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_options', 'module' => 'em-admin', 'controller' => 'options', 'action' => 'save-application', 'label' => 'Save application options'));
			$optionsSave->setVisible(false);
			$navi->addPage($optionsSave);

			$optionsSave2 = new Zend_Navigation_Page_Mvc(array('resource' => 'Emerald_Activity_administration___edit_options', 'module' => 'em-admin', 'controller' => 'options', 'action' => 'save-locale', 'label' => 'Save locale options'));
			$optionsSave2->setVisible(false);
			$navi->addPage($optionsSave2);
			
			$this->_navigation = $navi;
			
		}
		
		return $this->_navigation;
						
		
	}
	
	
}
	
