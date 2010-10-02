<?php
class EmCore_LanglibController extends Emerald_Controller_Action
{

    public $contexts = array(
        'index'     => array('js', 'json'),
    );

    public function init()
    {
        $this->getHelper('contextSwitch')
        ->addContext('js', array('suffix' => 'js', 'headers' => array('Content-Type' => 'text/javascript; charset=UTF-8')))
        ->initContext();
    }


    public function indexAction()
    {

        $filters = array();
        $validators = array(
			'locale' => array(new Zend_Validate_Regex("/([a-z]{2,3}(_[A-Z]{2})?)/"), 'required' => true, 'allowEmpty' => false),
        );

        try {
            	
            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();
            	
            $translate = Zend_Registry::get('Zend_Translate');

            $locale = new Zend_Locale($input->locale);
            	
            $this->view->messages = $translate->getMessages($input->locale);
            $this->view->locale = $input->locale;
            $this->view->language = $locale->getLanguage();
            	
            $this->getResponse()->setHeader('Cache-Control', 'public, max-age=3600');
            	
        } catch(Exception $e){
            throw new Emerald_Common_Exception('Translations not found');
        }





    }



}