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
		return;
		
		$filters = array(
		);
		$validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'optional', 'allowEmpty' => true),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$naviModel = new Core_Model_Navigation();
			$navi = $naviModel->getNavigation();
						
			if($input->page_id) {
				$page = $this->_pageFromPageId($input->page_id);
				$active = $navi->findBy('uri', URL_BASE . '/' . $page->beautifurl, false);
				if($active) {
					$active->setActive(true);
				}
				
				$localeMenu = $navi->findBy('uri', URL_BASE . '/' . $page->locale);
				$this->view->menu = $localeMenu;
			} else {
				$this->view->menu = $navi;
			}
			
			$this->view->getHelper('navigation')->setContainer($navi);						
			
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
						
			$active = $navi->findBy('uri', URL_BASE . '/' . $page->beautifurl, false);
			if($active) {
				$active->setActive(true);
			}
			
			$localeMenu = $navi->findBy('uri', URL_BASE . '/' . $page->locale);
			$this->view->menu = $localeMenu;
			
			$this->_helper->viewRenderer->setResponseSegment($this->_getParam('rs'));
		
		
		} catch(Exception $e) {
			
			throw $e;
		}
	}
	
	
}