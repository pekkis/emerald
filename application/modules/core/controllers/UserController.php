<?php
class Core_UserController extends Emerald_Controller_Action
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

		$form = new Core_Form_Login();
		if(!$form->isValid($this->getRequest()->getPost())) {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Check fields');
			$msg->errors = $form->getMessages(); 
		} else {
			
			$auth = Zend_Auth::getInstance();
			$adapter = new Zend_Auth_Adapter_DbTable($this->getDb(), 'user', 'email', 'passwd', 'MD5(?) and status = 1');			
						
			$adapter->setIdentity($form->tussi->getValue());
			$adapter->setCredential($form->loso->getValue());

			$result = $auth->authenticate($adapter);
			if($result->isValid()) {
				$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Login OK');
				$userModel = new Core_Model_User();
				$user = $userModel->find($adapter->getResultRowObject()->id);
				$auth->getStorage()->write($user);
			} else {
				$msg = new Emerald_Message(Emerald_Message::ERROR, 'Login failed.');
			}
		}
		$this->view->message = $msg;
	}	
	
	
	public function logoutAction()
	{
		Zend_Session::destroy(true);
		Zend_Session::forgetMe();
		
		$this->getHelper('redirector')->gotoRouteAndExit(array('module' => 'core', 'controller' => 'index', 'action' => 'index'));
	}	
	
	
	
}