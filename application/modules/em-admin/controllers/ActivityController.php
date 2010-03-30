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
		
		$activityModel = new EmAdmin_Model_Activity();
		
		$form = new EmAdmin_Form_Activities();
		
		$this->view->form = $form;
	}
	
	
	public function saveAction()
	{
		
		$form = new EmAdmin_Form_Activities();
		if($form->isValid($this->getRequest()->getPost())) {

			try {
				$activityModel = new EmAdmin_Model_Activity();
				$activityModel->updatePermissions($form->getValues());

				$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success.');
			} catch(Exception $e) {
				$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail.');	
			}
					
			
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail.');
		}
		
		
		$this->view->message = $msg;
		
		
		
		
		
	}
	
}