<?php
class EmAdmin_GroupController extends Emerald_Controller_Action
{
    public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }

    public function indexAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_users")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $groupModel = new EmCore_Model_Group();
        $this->view->groups = $groupModel->findAll();

        $groupModel = new EmCore_Model_Group();
        $this->view->groups = $groupModel->findAll();


    }


    public function createAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_users")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $groupModel = new EmCore_Model_Group();

        $form = new EmAdmin_Form_Group();

        $this->view->form = $form;

    }


    public function deleteAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_users")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $groupModel = new EmCore_Model_Group();
        $group = $groupModel->find($this->_getParam('id'));

        try {
            $groupModel->delete($group);
            $this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
        } catch(Emerald_Exception $e) {
            $this->view->message = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
        }




    }


    public function editAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_users")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $groupModel = new EmCore_Model_Group();

        $group = $groupModel->find($this->_getParam('id'));

        $form = new EmAdmin_Form_Group();

        $form->setDefaults($group->toArray());

        $this->view->form = $form;

    }




    public function saveAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_users")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $form = new EmAdmin_Form_Group();

        if($form->isValid($this->_getAllParams())) {
            	
            $groupModel = new EmCore_Model_Group();
            if(!$form->id->getValue() || !$group = $groupModel->find($form->id->getValue())) {
                $group = new EmCore_Model_GroupItem();

            }

            $group->setFromArray($form->getValues());

            $groupModel->save($group);

            $this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
            $this->view->message->group_id = $group->id;
            	
        } else {
            $msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
            $msg->errors = $form->getMessages();
            $this->view->message = $msg;
        }



    }


}