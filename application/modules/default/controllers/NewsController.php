<?php
class NewsController extends Emerald_Controller_Action 
{

	public function headlinesAction()
	{
		$filters = array(
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
			'headlines' => array('Int', 'presence' => 'optional', 'default' => 3),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$page = $input->page;
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
				$channel->title = $this->_emerald->getTranslate()->translate('shard/news/channel/default_title', $page->getLocale()); 
				$channel->link_readmore = $this->_emerald->getTranslate()->translate('shard/news/channel/default_link_readmore', $page->getLocale());
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
	
	
	
	public function indexAction()
	{
		$filters = array(
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
			'page_no' => array('Int', 'presence' => 'optional', 'default' => 1)
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$page = $input->page;
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
				$channel->title = $this->_emerald->getTranslate()->translate('shard/news/channel/default_title', $page->getLocale()); 
				$channel->link_readmore = $this->_emerald->getTranslate()->translate('shard/news/channel/default_link_readmore', $page->getLocale());
				$channel->save();
			}
	
			$this->view->channel = $channel;
	
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $page, 'write');
					
			$pageCount = ceil($channel->getItemCount($writable) / $channel->items_per_page);
			$pageNo = $this->_getParam('page_no', 1);
			
			$news = $channel->getItems($writable, $channel->items_per_page, ($pageNo - 1) * $channel->items_per_page);
	
			$this->view->pageCount = $pageCount;
			$this->view->pageNo = $pageNo;
			
			$this->view->news = $news;
			$this->view->writable = $writable;
	
			if($writable) {
				Emerald_Js::addAdminScripts($this->view);			
			}
			
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
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
			'id' => array('Int', 'presence' => 'required')
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
		
			$page = $input->page;
			$page->assertReadable();
			$this->view->page = $input->page;
			
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $input->page, 'write');
			$this->view->writable = $writable;
						
			$channelTbl = Emerald_Model::get('NewsChannel');
			
			$channel = $channelTbl->fetchRow(array('page_id = ?' => $page->id));
			
			$newsItemTbl = Emerald_Model::get('NewsItem');
			
			$where = array(
				'id = ?' => $input->id,
				'news_channel_id = ?' => $channel->id
			);
			
			if(!$item = $newsItemTbl->fetchRow($where)) {
				throw new Emerald_Exception('Invalid news id');
			}
			
			
			
			$this->view->item = $item;

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
			
					
			$builder = new Emerald_Feed_Builder_NewsChannel($channel);

			$feed = Zend_Feed::importBuilder($builder, $input->mode);
			
			$this->getResponse()->appendBody($feed->saveXML());

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