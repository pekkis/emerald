<?php
class Admin_SitemapController extends Emerald_Controller_AdminAction
{
	/**
	 * Displays the sitemap page tpl
	 */
	public function indexAction()
	{
		$filters = array();
		$validators = array('locale' => Array('allowEmpty' => false, 'presence' => 'optional'));
		
		try
		{
			$input = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$input->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
				
		$localeModel = new Core_Model_Locale();
		$this->view->locales = $locales = $localeModel->findAll();

		if(!$locales->current()) {
			return $this->getHelper('redirector')->gotoRouteAndExit(array('module' => 'admin', 'controller' => 'locale', 'action' => 'index'));
		}

		if(!$input->locale)
		{
			return $this->getHelper('redirector')->gotoRouteAndExit(array('module' => 'admin', 'controller' => 'sitemap', 'action' => 'index', 'locale' => $locales->current()->locale));
		}
		
		foreach($locales as $lc)
		{
			if($lc->locale == $input->locale)
				$this->view->editlocale = $input->locale;
		}
		
		
		
		$navimodel = new Core_Model_Navigation();
		
		$navigation = $navimodel->getNavigation();
		
		$navigation = new RecursiveIteratorIterator($navigation, RecursiveIteratorIterator::SELF_FIRST);
		
		
		$this->view->sitemap = $sitemap;
		
		
	}
	
	
	
}
