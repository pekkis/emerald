<?php
/**
 * Message class with predefined message statuses and translation capability.
 *
 * @package Emerald_Message
 * @author pekkis
 *
 */
class Emerald_Message
{
    const SUCCESS = 1;
    const INFO = 2;
    const ERROR = 4;

    /**
     * Translator container
     * @var Emerald_TranslatorContainer
     */
    protected $_translatorContainer = null;

    /**
     * Type(s)
     * @var integer
     */
    public $type;

    /**
     * Message
     * @var string
     */
    public $message;

    
    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }


    /**
     * Returns translator container
     * 
     * @return Emerald_TranslatorContainer
     */
    public function getTranslatorContainer()
    {
        if(!$this->_translatorContainer) {
            $this->_translatorContainer = new Emerald_TranslatorContainer();
        }
        return $this->_translatorContainer;
    }


    /**
     * Sets translator container
     * 
     * @param Emerald_TranslationContainer $translationContainer
     */
    public function setTranslatorContainer(Emerald_TranslationContainer $translationContainer)
    {
        $this->_translatorContainer = $translationContainer;
    }

}
?>