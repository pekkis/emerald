<?php
class EmAdmin_OptionsController extends Emerald_Cms_Controller_Action
{

    public $ajaxable = array(
		'save-application' => array('json'),
		'save-locale' => array('json')
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }



    public function indexAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_options")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $filters = array();
        $validators = array(
			'locale' => array(new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)'), 'presence' => 'optional'),
        );

        try {
            	
            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();
            	
            $localeModel = new EmCore_Model_Locale();
            $locales = $localeModel->findAll();

            $appForm = new EmAdmin_Form_ApplicationOptions();
            	
            $customer = $this->getCustomer();
            	
            $opts = $customer->getOptions();
            $appForm->setDefaults($opts);
            	
            	
            	
            $this->view->appForm = $appForm;


            $this->view->localeForms = array();

            foreach($locales as $locale) {

                $form = new EmAdmin_Form_LocaleOptions();

                $form->setDefaults($locale->getOptions());
                $form->locale->setValue($locale->locale);
                $form->setLocale($locale->locale);

                $this->view->localeForms[$locale->locale] = $form;

            }
            	

            	
        } catch(Exception $e) {
            throw new Emerald_Common_Exception($e->getMessage(), 500);
        }





    }


    public function saveApplicationAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_options")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $form = new EmAdmin_Form_ApplicationOptions();
        if($form->isValid($this->_getAllParams())) {
            	
            	
            foreach($form->getValues() as $key => $value) {
                $this->getCustomer()->setOption($key, $value);
            }
            	
            $this->view->message = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS, 'Save ok');
            	
            	
        } else {
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Save failed');
            $msg->errors = $form->getMessages();
            $this->view->message = $msg;
        }


    }


    public function saveLocaleAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_options")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        $form = new EmAdmin_Form_LocaleOptions();

        $form->setLocale($this->_getParam('locale'));
        if($form->isValid($this->_getAllParams())) {
            	
            $localeModel = new EmCore_Model_Locale();
            $locale = $localeModel->find($form->locale->getValue());
            	
            	
            foreach($form->getValues() as $key => $value) {
                $locale->setOption($key, $value);
            }
            	
            $this->view->message = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS, 'Save ok');
            	
        } else {
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Save failed');
            $msg->errors = $form->getMessages();
            $this->view->message = $msg;
        }

    }

}