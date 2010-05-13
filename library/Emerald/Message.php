<?php
/**
 * Message class with predefined message statuses and translation capability.
 * 
 * @package Emerald_Json
 * @author pekkis
 *
 */
class Emerald_Message
{
	const SUCCESS = 1;
	const INFO = 2;
	const ERROR = 4;
	
	
	protected $_translatorContainer = null;

	
	public $type;
	
	public $message;
	
	
    public function __construct($type, $message)
    {
    	$this->type = $type;
    	$this->message = $message;
    }
    
    
    public function getTranslatorContainer()
    {
    	if(!$this->_translatorContainer) {
    		$this->_translatorContainer = new Emerald_TranslatorContainer();
    	}
    	return $this->_translatorContainer;
    }
    
    
    public function setTranslationContainer(Emerald_TranslationContainer $translationContainer)
    {
    	$this->_translatorContainer = $translationContainer;
    }
    
	/*    
    public function toArray()
    {
    	 $arr = parent::toArray();
    	if(isset($arr['message']) && $arr['message']) {
    		if($translator = $this->getTranslatorContainer()->getTranslator()) {
				$arr['message'] = $translator->translate($arr['message']);    			
    		}
    	}
    	return $arr;
    }
    */
   
    
}
?>