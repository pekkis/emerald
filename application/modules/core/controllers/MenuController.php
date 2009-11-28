<?php
class Emerald_MenuController extends Emerald_Controller_Action 
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
			$this->view->childHtml = Array();
		
			$this->view->ban = $input->ban;
			$this->view->extended = $input->extended;
			$this->view->activeId = $page->id;
			$this->view->branch = $page->getChildren();
			$this->view->childHtml[$page->id] = $this->view->render('menu/list_level.phtml');
			do
			{
				$this->view->branch = $page->getBranch();
				$childHtml = $this->view->render('menu/list_level.phtml');
				$this->view->childHtml[$page->parent_id] = $childHtml;
			}
			while($page = $page->getParent());
		
			$this->getResponse()->appendBody($childHtml, $this->_getParam('rs'));
			$this->_helper->viewRenderer->setNoRender();
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
			$this->sitemap = new Emerald_Sitemap((string)$page->getLocale());
			
			$this->view->childHtml = Array();
		
			$this->view->ban = $input->ban;
			$this->view->extended = $input->extended;
			$this->view->activeId = $page->id;
			
			$childHtml = $this->_getSitemapBranch();
			$this->getResponse()->appendBody($childHtml);
			$this->_helper->viewRenderer->setNoRender();
		} catch(Exception $e) {
			
			throw $e;
		}
	}
	
	private function _getSitemapBranch($id = null)
	{
		$br = $this->sitemap->findBranch($id);
		if(count($br))
		{
			foreach($br as $node)
			{
				$this->view->childHtml[(int)$node->id] = $this->_getSitemapBranch((int)$node->id);
			}
			$this->view->branch = $br;
			$childHtml = $this->view->render('menu/list_level.phtml');
			return $childHtml;
		}
		return "";
	}
	
	
}