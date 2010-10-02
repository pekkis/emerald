<?php
class EmCore_SearchController extends Emerald_Cms_Controller_Action
{

    public function indexAction()
    {
        $filters = array();
        $validators = array(
    		'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
        );
         
        $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
        $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
        try {
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);
            $this->view->page = $page;
        } catch(Exception $e) {
            throw new Exception("Multifail", 404);
        }

    }


    public function resultsAction()
    {

        $filters = array(
			'q' => array(new Zend_Filter_Alnum(true)),
        );
        $validators = array(
    		'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
    		'q' => array(new Zend_Validate_StringLength(3, 255), 'presence' => 'required'),
    		'btnX' => array('presence' => 'optional', 'allowEmpty' => true),
    		'btnY' => array('presence' => 'optional', 'allowEmpty' => true),
        );
         
        $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
        $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
        try {
            $input->process();

            $page = $this->_pageFromPageId($input->page_id);
            $this->view->page = $page;
            	
            $this->view->q = $input->q;

            $customer = $this->getCustomer();
            $indexPath = $customer->getRoot() . '/data/index';
            	
            $index = Zend_Search_Lucene::open($indexPath);
            	
            $query = Zend_Search_Lucene_Search_QueryParser::parse($input->q);

            $results = $index->find($query);

            if($input->btnY !== null && sizeof($results)) {
                return $this->getResponse()->setRedirect($results[0]->path);
            }

        } catch(Zend_Filter_Exception $e) {
            $results = array();
        } catch(Exception $e) {
            	
            echo $e;
            	
            $results = array();
            	
        }

        $this->view->results = $results;

    }
}