<?php
class Admin_FileController extends Emerald_Controller_Action
{
	public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	
	public function deleteAction()
	{
			
		$fl = Zend_Registry::get('Emerald_Filelib');
		$file = $fl->file()->find($this->_getParam('id'));
		
		try {
			$fl->file()->delete($file);
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success');
		} catch(Exception $e) {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail');
		}
		$this->view->message = $msg;
	}
	
	
	
		

}