<?php
class Core_MenuController extends Emerald_Controller_Action 
{
	
	private $sitemap = null;
	
	public $ajaxable = array(
        'index'     => array('json'),
    );
	
	public function init()
	{
		$this->getHelper('ajaxContext')->setAutoJsonSerialization(false)->initContext();
	}
	
	
	public function indexAction()
	{
		$filters = array(
		);
		$validators = array(
			'locale' => array(new Zend_Validate_StringLength(1, 5), 'presence' => 'optional', 'allowEmpty' => true),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$naviModel = new Core_Model_Navigation();
			$navi = $naviModel->getNavigation();
						
			if($input->locale) {
				$menu = $navi->findBy('locale_root', $input->locale);
				$this->view->menu = $menu;
			} else {
				$this->view->menu = $navi;
			}
			
			$this->_helper->viewRenderer->setResponseSegment($this->_getParam('rs'));
		} catch(Exception $e) {
			
			throw $e;
		}
	}
	
	public function sitemapAction()
	{
		$filters = array(
		);
		$validators = array(
			'page' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'ban' => array('Digits'),
			'extended' => array(array('InArray',array(0,1))),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
		
			$page = $input->page;
			
			$naviModel = new Core_Model_Navigation();
			$navi = $naviModel->getNavigation();
						
			$active = $navi->findBy('uri', EMERALD_URL_BASE . '/' . $page->beautifurl, false);
			if($active) {
				$active->setActive(true);
			}
			
			$localeMenu = $navi->findBy('uri', EMERALD_URL_BASE . '/' . $page->locale);
			$this->view->menu = $localeMenu;
			
			$this->_helper->viewRenderer->setResponseSegment($this->_getParam('rs'));
		
		
		} catch(Exception $e) {
			
			throw $e;
		}
	}
	
	
}