<?php
class EmAdmin_LocaleController extends Emerald_Controller_Action
{

    public $ajaxable = array(
		'update' => array('json'),
		'delete' => array('json'),
		'save' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }


    public function indexAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_locales")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $localeModel = new EmCore_Model_Locale();

        $this->view->locales = $localeModel->findAll();
        	
        $this->view->form = new EmAdmin_Form_LocaleAdd();
    }


    public function updateAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_locales")) {
            throw new Emerald_Exception('Forbidden', 403);
        }


        $form = new EmAdmin_Form_LocaleAdd();
        if(!$form->isValid($this->_getAllParams())) {
            $msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
        } else {
            	
            $formLocales = array();
            if($form->locale->getValue()) {
                foreach($form->locale->getValue() as $key => $value) {
                    $formLocales[] = $value;
                }
            }
            	
            $localeModel = new EmCore_Model_Locale();
            	
            $success = $localeModel->updateSiteLocales($formLocales);
            	
            if($success) {
                $msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
            } else {
                $msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
            }
        }
        	
        $this->view->message = $msg;

    }


    public function editAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_locales")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $localeModel = new EmCore_Model_Locale();

        $locale = $localeModel->find($this->_getParam('id'));

        $form = new EmAdmin_Form_Locale();
        $form->setDefaults($locale->toArray());

        $permForm = $form->getSubForm('locale-permissions');
        $permissions = $localeModel->getPermissions($locale);
        $permForm->setDefaults($permissions);

        $this->view->form = $form;


    }


    public function deleteAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_locales")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $localeModel = new EmCore_Model_Locale();
        $locale = $localeModel->find($this->_getParam('id'));

        try {
            $localeModel->delete($locale);
            $this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Delete ok');
        } catch(Exception $e) {
            $this->view->message = new Emerald_Message(Emerald_Message::ERROR, 'Delete failed');
        }

    }



    public function saveAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_locales")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $localeModel = new EmCore_Model_Locale();

        $form = new EmAdmin_Form_Locale();

        $locale = $localeModel->find($this->_getParam('locale'));

        if(!$locale) {
            throw new Emerald_Exception('Locale not found', 404);
        }

        if(!$form->isValid($this->_getAllParams())) {
            $msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed.');
            $msg->errors = $form->getMessages();
        } else {
            $locale->setFromArray($form->getValues());
            $localeModel->save($locale, $form->getSubForm('locale-permissions')->getValues());
            $msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok.');
            $msg->locale_id = $locale->id;
        }

        $this->view->message = $msg;


    }




}