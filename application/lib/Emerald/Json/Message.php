<?php
class Emerald_Json_Message
{
	const SUCCESS = 1;
	const INFO = 2;
	const ERROR = 4;
		
	
	public $type;
	public $message;
        
    public function __construct($type, $message)
    {
        $this->type = $type;
        
        if(is_string($message)) {
	        if(preg_match('/^l:([a-z_\/]+)/', $message, $matches)) {
	        	$message = Emerald_Application::getInstance()->getTranslate()->_($matches[1]);
	        }
        }
                
        $this->message = $message;
    }

    
    
    public function __toString()
    {
		return Zend_Json::encode($this);
    }
    
}
?>