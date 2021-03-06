<?php
class EmCore_NewsController extends Emerald_Cms_Controller_Action
{

    public $contexts = array(
		'index' => array('html', 'xml'),
		'headlines' => array('html'),
		'view' => array('html'),
    );

    public function init()
    {
        $contextSwitch = $this->_helper->getHelper('ContextSwitch');
        if (!$contextSwitch->hasContext('html')) {
            $contextSwitch->addContext('html', array('suffix' => 'ajax'));
        }
        $contextSwitch->initContext();
    }



    public function indexAction()
    {

        $filters = array(
        );

        $validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'page' => array(new Zend_Validate_Int(), 'presence' => 'optional', 'allowEmpty' => false, 'default' => 1),
			'tag' => array(new Zend_Validate_StringLength(0, 255), 'presence' => 'optional', 'allowEmpty' => true, 'default' => '')
        );


        try {
            $input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);
            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }

            	
            $channelModel = new EmCore_Model_NewsChannel();


            $readable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read');
            	
            $writable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write');

            $channel = $channelModel->findByPageId($page->id);
            $this->view->channel = $channel;
            	
            if(!$readable) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }

            $news = $channel->getItems($writable, $input->tag);
            $news->setCurrentPageNumber($input->page);

            $this->view->channel = $channel;
            $this->view->news = $news;
            $this->view->writable = $writable;
            $this->view->page = $page;
            	
            	
            	
            	
        } catch(Exception $e) {
            	
            Zend_Debug::dump($input->getMessages());
            	
            echo $e;
            die();
            	
            throw $e;
        }

    }


    public function headlinesAction()
    {
        $filters = array();
        $validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'amount' => array('Int', 'presence' => 'optional', 'default' => 3),
        );

        try {
            $input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);

            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
            $channelModel = new EmCore_Model_NewsChannel();

            $readable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read');
            $writable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write');

            $channel = $channelModel->findByPageId($page->id);
            $channel->items_per_page = $input->amount;
            $this->view->channel = $channel;
            	
            if(!$readable) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }

            $news = $channel->getItems($writable);
            $news->setCurrentPageNumber(1);

            $this->view->channel = $channel;
            $this->view->news = $news;
            $this->view->writable = $writable;
            $this->view->page = $page;
            	
        } catch(Exception $e) {
            throw $e;
        }

    }

    public function tagCloudAction()
    {

        $filters = array();
        $validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'amount' => array('Int', 'presence' => 'optional', 'default' => 3),
        );

        try {
            $input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);
            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
            $channelModel = new EmCore_Model_NewsChannel();
            $channel = $channelModel->findByPageId($page->id);
            	
            $cloud = $channelModel->getTagCloud($channel);
            	
            $this->view->cloud = $cloud;
            	
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
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();

            $newsModel = new EmCore_Model_NewsItem();
            $item = $newsModel->find($input->id);
            	
            $this->view->item = $item;
            	
            $channel = $item->getChannel();
            $this->view->channel = $channel;
            	
            $page = $channel->getPage();
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            $this->view->page = $page;
            $writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $page, 'write');
            $this->view->writable = $writable;

        } catch(Exception $e) {
            throw $e;
        }
        	
    }




}