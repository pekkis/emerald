<?php
/**
 * Messenger helper
 * 
 * @author jorma.tuomainen
 * @package Emerald_Common_Messaging
 *
 */
class Emerald_Common_Controller_Action_Helper_Messenger extends Zend_Controller_Action_Helper_Abstract
{
    protected $_messenger = null;

    public function init()
    {
        $this->_messenger = new Zend_Session_Namespace('messenger');
        if(!isset($this->_messenger->messages)) {
            $this->_messenger->messages = array();
        }
    }

    private function _addMessage(Emerald_Common_Messaging_Message $message)
    {
        $this->_messenger->messages[] = $message;
    }

    public function addMessage($message)
    {
        $this->_addMessage(new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS,$message));
    }
    public function addNotification($message)
    {
        $this->_addMessage(new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::INFO,$message));
    }
    public function addError($message,$errors = false)
    {
        $tmp = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR,$message);
        if(!empty($errors)) $tmp->errors = $errors;
        $this->_addMessage($tmp);
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