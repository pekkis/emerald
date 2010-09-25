<?php
class EmAdmin_ActivityController extends Emerald_Controller_Action
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
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_activities")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        $activityModel = new EmAdmin_Model_Activity();

        $form = new EmAdmin_Form_Activities();

        $this->view->form = $form;
    }


    public function saveAction()
    {
        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___edit_activities")) {
            throw new Emerald_Exception('Forbidden', 403);
        }
        	

        $form = new EmAdmin_Form_Activities();
        if($form->isValid($this->getRequest()->getPost())) {

            try {
                $activityModel = new EmAdmin_Model_Activity();
                $activityModel->updatePermissions($form->getValues());

                $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::SUCCESS, 'Save ok.');
            } catch(Exception $e) {
                $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::ERROR, 'Save failed.');
            }
            	
            	
            	
        } else {
            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::ERROR, 'Save failed.');
        }


        $this->view->message = $msg;





    }

}