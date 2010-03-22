<?php
class Emerald_Controller_Action_Helper_Messenger extends Zend_Controller_Action_Helper_Abstract
{
	protected $_messenger = null;

	public function init()
	{
		$this->_messenger = new Zend_Session_Namespace('messenger');
		if(!isset($this->_messenger->messages)) {
			$this->_messenger->messages = array();
		}
	}
	
	private function _addMessage(Emerald_Message $message)
	{
		$this->_messenger->messages[] = $message;
	}

	public function addMessage($message)
	{
		$this->_addMessage(new Emerald_Message(Emerald_Message::SUCCESS,$message));
	}
	public function addNotification($message)
	{
		$this->_addMessage(new Emerald_Message(Emerald_Message::INFO,$message));
	}
	public function addError($message)
	{
		$this->_addMessage(new Emerald_Message(Emerald_Message::ERROR,$message));
	}
	public function getMessages()
	{
		$retval = $this->_messenger->messages;
		$this->_messenger->messages = array();
		return $retval;
	}
	public function postDispatch()
	{
		Zend_Layout::getMvcInstance()->getView()->messages = $this->getMessages();
	}
}