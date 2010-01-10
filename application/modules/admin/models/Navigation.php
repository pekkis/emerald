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
			
			$sitemap = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'sitemap', 'label' => 'Sitemap'));
			$navi->addPage($sitemap);
						
			$filelib = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'filelib', 'label' => 'Files'));
			$navi->addPage($filelib);

			$forms = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'form', 'label' => 'Forms'));
			$navi->addPage($forms);
					
			$users = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'user', 'label' => 'Users & groups'));
			$navi->addPage($users);

			$locale = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'locale', 'label' => 'Locales'));
			$navi->addPage($locale);
			
			$options = new Zend_Navigation_Page_Mvc(array('module' => 'admin', 'controller' => 'options', 'label' => 'Options'));
			$navi->addPage($options);
						
			$this->_navigation = $navi;
			
		}
		
		return $this->_navigation;
						
		
	}
	
	
}
	
