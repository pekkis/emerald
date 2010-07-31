<?php
class EmCore_TagController extends Emerald_Controller_Action
{



    public function cloudAction()
    {
        $filters = array();
        $validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
        );

        try {
            $input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
            $input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);
            	
            $taggableModel = new EmCore_Model_Taggable();
            $cloud = $taggableModel->getTagCloud($page->id);

            $this->view->cloud = $cloud;

        } catch(Exception $e) {
            throw $e;
        }



    }



    public function tagAction()
    {
        $filters = array();
        $validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'tag' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required', 'allowEmpty' => false),
        );

        try {
            $input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
            $input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);
            	
            $taggableModel = new EmCore_Model_Taggable();
            	
            $descriptors = $taggableModel->findDescriptorsFor($input->tag);
            	
            $this->view->page_id = $page->id;
            $this->view->descriptors = $descriptors;

        } catch(Exception $e) {
            throw $e;
        }




    }



}


