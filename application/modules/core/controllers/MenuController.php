<?php
class Core_MenuController extends Emerald_Controller_Action 
{
	
	private $sitemap = null;
	
	public function indexAction()
	{
		
		
		$filters = array(
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
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
						
			$active = $navi->findBy('uri', '/' . $page->beautifurl, false);
			if($active) {
				$active->setActive(true);
			}
			
			$localeMenu = $navi->findBy('uri', '/' . $page->locale);
			$this->view->menu = $localeMenu;
			
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
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
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
						
			$active = $navi->findBy('uri', '/' . $page->beautifurl, false);
			if($active) {
				$active->setActive(true);
			}
			
			$localeMenu = $navi->findBy('uri', '/' . $page->locale);
			$this->view->menu = $localeMenu;
			
			$this->_helper->viewRenderer->setResponseSegment($this->_getParam('rs'));
		
		
		} catch(Exception $e) {
			
			throw $e;
		}
	}
	
	
}