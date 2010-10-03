<?php
class EmAdmin_FormController extends Emerald_Cms_Controller_Action
{
    public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
		'create-post' => array('json'),
		'field-create' => array('json'),
		'field-delete' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }


    public function indexAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        $formModel = new EmCore_Model_Form();
        $forms = $formModel->findAll();
        $this->view->forms = $forms;
    }


    public function deleteAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        $model = new EmCore_Model_Form();
        $item = $model->find($this->_getParam('id'));

        try {
            $model->delete($item);
            $this->view->message = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::SUCCESS, 'Delete ok');
        } catch(Emerald_Common_Exception $e) {
            $this->view->message = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::ERROR, 'Delete failed');
        }

    }


    public function createAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $this->view->form = new EmAdmin_Form_FormCreate();

    }


    public function createPostAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        $form = new EmAdmin_Form_FormCreate();
        $model = new EmCore_Model_Form();

        if($form->isValid($this->_getAllParams())) {
            $item = new EmCore_Model_FormItem();
            $item->setFromArray($form->getValues());
            $model->save($item);
            	
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::SUCCESS, 'Form was created.');
            $msg->form_id = $item->id;
        } else {
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::ERROR, 'Form creation failed.');
            $msg->errors = $form->getMessages();
        }

        $this->view->message = $msg;

    }


    public function fieldDeleteAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $model = new EmCore_Model_FormField();

        try {
            $item = $model->find($this->_getParam('id'));
            $model->delete($item);
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::SUCCESS, 'Field was deleted.');
        } catch(Exception $e) {
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::ERROR, 'Field delete failed.');
        }
        $this->view->message = $msg;
    }




    public function fieldCreateAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $form = new EmAdmin_Form_FormFieldCreate();

        if($form->isValid($this->_getAllParams())) {
            	
            $model = new EmCore_Model_Form();
            	
            $formObj = $model->find($form->form_id->getValue());

            $field = new EmCore_Model_FormFieldItem($form->getValues());
            	
            $field->order_id = $model->getOrderIdForNewField($formObj);
            $model->saveField($field);
            	
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::SUCCESS, 'A field was created.');
        } else {
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::ERROR, 'Field creation failed.');
        }

        $this->view->message = $msg;

    }




    public function saveAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $filters = array();
        $validators = array(
			'form_id' => array('Int', 'presence' => 'required'),
			'id' => array('Int', 'presence' => 'required'),
			'type' => array(new Zend_Validate_InArray(array(1, 2, 3, 4, 5, 6)), 'presence' => 'required'),
			'title' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'optional', 'allowEmpty' => true),
			'mandatory' => array(new Zend_Validate_InArray(array(0, 1)), 'presence' => 'required'),
			'options' => array(new Zend_Validate_StringLength(0, 255), 'presence' => 'optional', 'allowEmpty' => true),
        );


        $db = $this->getDb();
        $db->beginTransaction();


        try {

            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();

            	
            $fieldModel = new EmCore_Model_FormField();

            $order = 0;
            	
            foreach($input->id as $key => $id) {
                $field = $fieldModel->find($id);
                $field->order_id = $order++;
                $field->type = $input->type[$key];
                $field->title = $input->title[$key];
                $field->mandatory = $input->mandatory[$key];
                $field->options = $input->options[$key];
                $fieldModel->save($field);
            }
            	
            	
            $db->commit();
            	
            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::SUCCESS, 'Save ok.');
            	

        } catch(Exception $e) {

            echo $e;
            	
            $db->rollBack();

            $msg = new Emerald_Base_Messaging_Message(Emerald_Base_Messaging_Message::ERROR, 'Save failed.');
            $msg->errors = array_keys($input->getMessages());
            	
        }

        $this->view->message = $msg;


    }



    public function editAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_forms")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $filters = array();
        $validators = array(
			'id' => array('Int', 'presence' => 'required'),
        );

        try {

            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());
            $input->process();

            $formModel = new EmCore_Model_Form();

            if(!$form = $formModel->find($input->id)) {
                throw new Emerald_Common_Exception('Invalid form', 404);
            }
            	
            	
            $this->view->form = $form;

            $createForm = new EmAdmin_Form_FormFieldCreate();
            $createForm->form_id->setValue($form->id);
            	
            $this->view->fieldCreateForm = $createForm;

            	
            $opts = array(
			'1' => 'Text',
			'2' => 'Textarea',
			'3' => 'Select',
			'4' => 'Multiselect',
			'5' => 'Radio',
			'6' => 'Checkbox',
            );
            	
            $this->view->opts = $opts;
            	

            	
            	
        } catch(Exception $e) {
            throw $e;
        }
        	

    }





}