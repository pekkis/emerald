<?php
class EmCore_UserController extends Emerald_Cms_Controller_Action
{

    public $ajaxable = array(
        'login'     => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }


    public function loginAction()
    {

        $form = new EmCore_Form_Login();
        if(!$form->isValid($this->getRequest()->getPost())) {
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Check fields');
            $msg->errors = $form->getMessages();
        } else {

            $model = new EmCore_Model_User();

            if($model->authenticate($form->tussi->getValue(), $form->loso->getValue())) {
                $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS, 'Login OK');
            } else {
                $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Login failed.');
            }
            	
        }
        $this->view->message = $msg;
    }


    public function logoutAction()
    {
        Zend_Session::destroy(true);
        Zend_Session::forgetMe();

        $this->getHelper('redirector')->gotoRouteAndExit(array('module' => 'em-core', 'controller' => 'index', 'action' => 'index'));
    }



}