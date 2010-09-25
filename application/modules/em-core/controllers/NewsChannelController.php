<?php
class EmCore_NewsChannelController extends Emerald_Controller_Action
{
    public $ajaxable = array(
		'save' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }

    public function editAction()
    {
        $filters = array();

        $validators = array(
			'id' => array('Int', 'presence' => 'required'),
        );

        try {
            	
            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
            $input->process();

            $channelModel = new EmCore_Model_NewsChannel();
            $channel = $channelModel->find($input->id);

            $page = $this->_pageFromPageId($channel->page_id);
            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
                throw new Emerald_Exception('Forbidden', 403);
            }

            $form = new EmCore_Form_NewsChannel();
            $form->setDefaults($channel->toArray());

            $this->view->page = $page;
            $this->view->form = $form;
            	
            	
        } catch(Exception $e) {
            throw $e;
        }



    }


    public function saveAction()
    {

        $form = new EmCore_Form_NewsChannel();

        if($form->isValid($this->getRequest()->getPost())) {
            $page = $this->_pageFromPageId($form->page_id->getValue());
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
                throw new Emerald_Exception('Forbidden', 403);
            }

            $model = new EmCore_Model_NewsChannel();
            	
            $item = $model->find($form->id->getValue());
            	
            $values = $form->getValues();
            	
            $item->setFromArray($form->getValues());
            	
            $model->save($item);

            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::SUCCESS, 'Save ok');
            	
        } else {
            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::ERROR, 'Save failed');
            $msg->errors = $form->getMessages();

        }

        $this->view->message = $msg;

    }


    public function addItemAction()
    {
        $filters = array();

        $validators = array(
			'id' => array('Int', 'presence' => 'required'),
        );

        try {
            	
            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
            $input->process();

            $channelModel = new EmCore_Model_NewsChannel();
            $channel = $channelModel->find($input->id);

            $page = $this->_pageFromPageId($channel->page_id);
            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
                throw new Emerald_Exception('Forbidden', 403);
            }

            $form = new EmCore_Form_NewsItem();
            $this->view->form = $form;
            	
            $form->news_channel_id->setValue($input->id);
            	
            $now = new DateTime();
            	
            $form->valid_start_date->setValue($now->format('Y-m-d'));
            $form->valid_start_time->setValue($now->format('H:i:s'));
            	
            $now->modify("+ {$channel->default_months_valid} months");
            	
            $form->valid_end_date->setValue($now->format('Y-m-d'));
            $form->valid_end_time->setValue($now->format('H:i:s'));
            	
            $form->status->setValue(1);
            	
        } catch(Exception $e) {
            throw $e;
        }
    }





}