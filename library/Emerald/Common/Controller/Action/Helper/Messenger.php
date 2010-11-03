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

    private function _addMessage(Emerald\Base\Messaging\Message $message)
    {
        $this->_messenger->messages[] = $message;
    }

    public function addMessage($message)
    {
        $this->_addMessage(new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::SUCCESS,$message));
    }
    public function addNotification($message)
    {
        $this->_addMessage(new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::INFO,$message));
    }
    public function addError($message,$errors = false)
    {
        $tmp = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::ERROR,$message);
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