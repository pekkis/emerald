<?php
class EmAdmin_SitemapController extends Emerald_Controller_Action
{
	
	public $contexts = array(
        'link-list'     => array('js', 'json'),
    );
	
	public function init()
	{
		$this->getHelper('contextSwitch')
		->addContext('js', array('suffix' => 'js', 'headers' => array('Content-Type' => 'text/javascript; charset=UTF-8')))
		->initContext();
	}
	
	
	public function indexAction()
	{
		
		$localeModel = new EmCore_Model_Locale();
		$locales = $localeModel->findAll();
		$this->view->locales = $locales;
		
	}
	
	
	/**
	 * Displays the sitemap page tpl
	 */
	public function editAction()
	{
		$filters = array();
		$validators = array('locale' => Array('allowEmpty' => false, 'presence' => 'optional'));
		
		try {
			$input = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$input->process();
		} catch(Exception $e) {
			throw new Emerald_Exception('Not Found', 404);
		}

		$localeModel = new EmCore_Model_Locale();
		$locale = $localeModel->find($input->locale);
		
		if(!$input->locale) {
			return $this->getHelper('redirector')->gotoRouteAndExit(array('module' => 'em-admin', 'controller' => 'sitemap', 'action' => 'index'));
		}
		
		$this->view->locale = $locale;
		
		$navimodel = new EmCore_Model_Navigation();
		
		$navigation = $navimodel->getNavigation()->findBy('locale_root',  $locale->locale);

		$navigation = new Zend_Navigation(array($navigation));
		
		$navigation = new RecursiveIteratorIterator($navigation, RecursiveIteratorIterator::SELF_FIRST);

		
		
		$this->view->sitemap = $navigation;
		
		$shardModel = new EmCore_Model_Shard();
		$shards = $shardModel->findAll();
		
		$shardOpts = array();
		foreach($shards as $shard) {
			if($shard->isInsertable()) {
				$shardOpts[$shard->id] = $shard->name;
			}
		}
		$this->view->shardOpts = $shardOpts;
		
		$layouts = Zend_Registry::get('Emerald_Customer')->getLayouts();
		$layoutOpts = array();
		foreach($layouts as $layout) {
			$layoutOpts[$layout->getIdentifier()] = $layout->getIdentifier();
		}
		$this->view->layoutOpts = $layoutOpts;
		
		
		
	}
	
	
	public function linkListAction()
	{
		
		$navimodel = new EmCore_Model_Navigation();

		$navigation = $navimodel->getNavigation();
		
		$navigation = new RecursiveIteratorIterator($navigation, RecursiveIteratorIterator::SELF_FIRST);
		
		$this->view->navigation = $navigation;

		
	}
	
	
}
