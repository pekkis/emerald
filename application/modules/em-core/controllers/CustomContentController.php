<?php
class EmCore_CustomContentController extends Emerald_Cms_Controller_Action
{

    public $ajaxable = array(
		'save' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }


    public function indexAction()
    {
        $filters = array();
        $validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'block_id' => array('Int', 'presence' => 'required'),
        );

        try {
            $input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();
            	
            $page = $this->_pageFromPageId($input->page_id);
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
            $writable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write');

            $customModel = new EmCore_Model_CustomContent();
            $customcontent = $customModel->find($page->id, $input->block_id);
            parse_str($customcontent->params, $parsedParams);

            // If not writable, just do a redirect
            if(!$writable && $customcontent->module && $customcontent->controller && $customcontent->action) {
                return $this->_forward($customcontent->action, $customcontent->controller, $customcontent->module, $parsedParams);
            }
            	
            $this->view->writable = $writable;
            $this->view->customcontent = $customcontent;
            $this->view->page = $page;

        } catch(Exception $e) {
            throw $e;
        }

    }



    public function editAction()
    {

        $filters = array(
        );

        $validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
			'block_id' => array('Int', 'presence' => 'required'),
        );

        try {

            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();
            	
            $customModel = new EmCore_Model_CustomContent();
            $customcontent = $customModel->find($input->page_id, $input->block_id);

            $page = $this->_pageFromPageId($input->page_id);
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
            $pageModel = new EmCore_Model_Page();
            $siblings = $pageModel->findSiblings($page);

            $this->view->customcontent = $customcontent;

            $form = new EmCore_Form_CustomContent();
            $form->setDefaults($customcontent->toArray());
            	
            foreach($siblings as $sibling) {
                $form->siblings->addMultiOption($sibling->id, $sibling->locale);
            }
            $form->siblings->setValue($page->id);
            	
            $this->view->form = $form;

            $this->view->page = $page;
            	
        } catch(Exception $e) {
            throw new Emerald_Common_Exception($e->getMessage(), 500);
        }

    }



    public function saveAction()
    {

        $form = new EmCore_Form_CustomContent();

        if($form->isValid($this->getRequest()->getPost())) {
            	
            $page = $this->_pageFromPageId($form->page_id->getValue());
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
            $model = new EmCore_Model_CustomContent();
            	
            $customcontent = $model->find($form->page_id->getValue(), $form->block_id->getValue());
            	
            $customcontent->setFromArray($form->getValues());
            	
            $model->save($customcontent);

            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::SUCCESS, 'Save ok');
            	
            	
        } else {
            $message = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::ERROR, 'Save failed');
            $message->errors = array_keys($form->getMessages());
        }



        $this->view->message = $msg;

    }





}