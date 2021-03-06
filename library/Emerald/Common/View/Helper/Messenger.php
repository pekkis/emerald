<?php
/**
 * Messaging view helper
 * 
 * @author Jorma Tuomainen <jorma.tuomainen@brainalliance.com>
 * @package Emerald_Common_View
 *
 */
class Emerald_Common_View_Helper_Messenger extends Zend_View_Helper_Abstract
{
    protected $_messenger = null;

    public function init()
    {
        $this->_messenger = new Zend_Session_Namespace('messenger');
        if(!isset($this->_messenger->messages)) {
            $this->_messenger->messages = array();
        }
    }

    public function messenger() {
        $this->init();
        if(empty($this->_messenger->messages)) {
            return '';
        }
        $retval = "<script type=\"text/javascript\">\n";
        $retval.= "$(document).ready(function() {\n";
        foreach($this->_messenger->messages as $val) {
            switch($val->type) {
                case 1:
                    $retval.="Emerald.Messenger.publishMessage('{$val->message}','success');\n";
                    break;
                case 2:
                    $retval.="Emerald.Messenger.publishMessage('{$val->message}','info');\n";
                    break;
                case 4:
                    $retval.="Emerald.Messenger.publishMessage('{$val->message}','error');\n";
                    break;
            }
        }
        $retval.="});\n";
        $retval.='</script>';
        return $retval;
    }
}
