<?php
class Emerald_View_Helper_Messenger extends Zend_View_Helper_Abstract
{
	protected $_messenger = null;

	public function init()
	{
		$this->_messenger = new Zend_Session_Namespace('messenger');
		if(!isset($this->_messenger->messages)) {
			$this->_messenger->messages = array();
		}
	}
	
	public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
	
	public function Messenger() {
		if(empty($this->view->messages)) {
			return '';
		}
		$retval = "<script type=\"text/javascript\">\n";
		$retval.= "$(document).ready(function() {\n";
		foreach($this->view->messages as $val) {
			switch($val->type) {
				case 1:
					$retval.="Messenger.publishMessage('{$val->message}','success');\n";
					break;
				case 2:
					$retval.="Messenger.publishMessage('{$val->message}','info');\n";
					break;
				case 4:
					$retval.="Messenger.publishMessage('{$val->message}','error');\n";
					break;
			}
		}
		$retval.="});\n";
		$retval.='</script>';
		return $retval;
	}
}