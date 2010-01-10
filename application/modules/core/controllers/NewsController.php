<?php
class Core_NewsController extends Emerald_Controller_Action 
{

	public $contexts = array(
		'index' => array('xml'),
	);
	
	public $ajaxable = array('index' => array('html'));
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();

		if(!$this->getHelper('ajaxContext')->getCurrentContext()) {
			$this->getHelper('contextSwitch')->initContext();	
		}
		
	}
	
	
	
	public function indexAction()
	{
		
		$filters = array(
		);
		$validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'page' => array(new Zend_Validate_Int(), 'presence' => 'optional', 'default' => 1)
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$page = $this->_pageFromPageId($input->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
				throw new Emerald_Exception('Forbidden', 401);
			}
				
			
			$channelModel = new Core_Model_NewsChannel();

	
			$readable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read');
			
			$writable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write');
						
			$channel = $channelModel->findByPageId($page->id);
			$this->view->channel = $channel;
			
			
			
			if(!$readable) {
				throw new Emerald_Exception('Forbidden', 401);				
			}
						
			$news = $channel->getItems($writable);
			$news->setCurrentPageNumber($input->page);

			$this->view->channel = $channel;
			$this->view->news = $news;
			$this->view->writable = $writable;
			$this->view->page = $page;
			
		} catch(Exception $e) {
			throw $e;
		}
		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	public function headlinesAction()
	{
		
		
		$filters = array(
		);
		$validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'headlines' => array('Int', 'presence' => 'optional', 'default' => 3),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$page = $this->_pageFromPageId($input->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
				throw new Emerald_Exception('Forbidden', 401);
			}
			
			
			$page->assertReadable();
			
			$this->view->page = $input->page;
			
			$channelTbl = Emerald_Model::get('NewsChannel');
			$where = array(
				'page_id = ?' => $page->id
			);
					
			if(!$channel = $channelTbl->fetchRow($where)) {
				$channel = $channelTbl->createRow(array(), true);
				$channel->page_id = $page->id;
				$channel->created_by = $this->getCurrentUser()->id;
				$channel->title = Zend_Registry::get('Zend_Translate')->translate('News', $page->getLocale()); 
				$channel->link_readmore = Zend_Registry::get('Zend_Translate')->translate('Read more', $page->getLocale());
				$channel->save();
			}
	
			$this->view->channel = $channel;
	
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $page, 'write');
			
			$news = $channel->getItems(false, $input->headlines, 0);
						
			$this->view->news = $news;
			$this->view->writable = false;
	
			if($channel->allow_syndication) {
				$this->view->headLink()->headLink(array('rel' => 'alternate', 'title' => $channel->description, 'type' => 'application/rss+xml', 'href' => "/news/feed/id/{$channel->id}/mode/rss"), 'APPEND');			
			}
			
		} catch(Exception $e) {
			throw $e;
		}
		
	}
	
	
	
	
	
	public function viewAction()
	{
		$filters = array(
		);
		$validators = array(
			'id' => array('Int', 'presence' => 'required')
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$newsModel = new Core_Model_NewsItem();
			$item = $newsModel->find($input->id);
			
			$this->view->item = $item;
			
			$channel = $item->getChannel();
			$this->view->channel = $channel;
			
			$page = $channel->getPage();
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
				throw new Emerald_Exception('Forbidden', 401);
			}
			$this->view->page = $page;
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $page, 'write');
			$this->view->writable = $writable;

		} catch(Exception $e) {
			throw $e;
		}
			
	}
	
	
	
	
	public function feedAction()
	{

		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),		
			'mode' => array(new Zend_Validate_InArray(array('rss', 'atom')), 'presence' => 'optional', 'default' => 'rss'),
		);

		
		
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
						
			$channelTbl = Emerald_Model::get('NewsChannel');
			$channel = $channelTbl->find($input->id)->current();
			
			if(!$channel->allow_syndication) {
				throw new Emerald_Exception('Feed not found', 404);
			}
			
			$page = $channel->getPage();
			$page->assertReadable();
			$this->view->page = $input->page;
			
					
			
			$this->getResponse()->setHeader('Content-type', "application/{$input->mode}+xml; charset: UTF-8");

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
									
		} catch(Zend_Filter_Exception $e) {
			throw new Emerald_Exception('Feed not found', 404);
		} catch(Emerald_Exception $e) {
			throw new Emerald_Exception($e->getMessage(), $e->getHttpResponseCode());
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
		
		
		
		
		
		
			
		
		
	}
	
	
	
}