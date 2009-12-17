<?php
/**
 * Json message class with predefined message statuses and translation capability.
 * 
 * @package Emerald_Json
 * @author pekkis
 *
 */
class Emerald_Json_Message extends Emerald_Model_AbstractItem
{
	const SUCCESS = 1;
	const INFO = 2;
	const ERROR = 4;
	
	
    /**
     * @var Zend_Translate
     */
    protected $_translator;

    /**
     * Global default translation adapter
     * @var Zend_Translate
     */
    protected static $_translatorDefault;

    /**
     * is the translator disabled?
     * @var bool
     */
    protected $_translatorDisabled = false;
        
    public function __construct($type, $message)
    {
    	parent::__construct(array('type' => $type, 'message' => $message));
    }
    
    
    public function __toString()
    {
		return Zend_Json::encode($this->toArray());
    }
    
    
    public function toArray()
    {
    	$arr = parent::toArray();
    	if(isset($arr['message']) && $arr['message']) {
    		if($translator = $this->getTranslator()) {
				$arr['message'] = $this->getTranslator()->translate($arr['message']);    			
    		}
    	}
    	return $arr;
    }
    
    
    

    /**
     * Set translator object
     *
     * @param  Zend_Translate|Zend_Translate_Adapter|null $translator
     * @return Zend_Form
     */
    public function setTranslator($translator = null)
    {
        if (null === $translator) {
            $this->_translator = null;
        } elseif ($translator instanceof Zend_Translate_Adapter) {
            $this->_translator = $translator;
        } elseif ($translator instanceof Zend_Translate) {
            $this->_translator = $translator->getAdapter();
        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid translator specified');
        }

        return $this;
    }

    /**
     * Set global default translator object
     *
     * @param  Zend_Translate|Zend_Translate_Adapter|null $translator
     * @return void
     */
    public static function setDefaultTranslator($translator = null)
    {
        if (null === $translator) {
            self::$_translatorDefault = null;
        } elseif ($translator instanceof Zend_Translate_Adapter) {
            self::$_translatorDefault = $translator;
        } elseif ($translator instanceof Zend_Translate) {
            self::$_translatorDefault = $translator->getAdapter();
        } else {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid translator specified');
        }
    }

    /**
     * Retrieve translator object
     *
     * @return Zend_Translate|null
     */
    public function getTranslator()
    {
        if ($this->translatorIsDisabled()) {
            return null;
        }

        if (null === $this->_translator) {
            return self::getDefaultTranslator();
        }

        return $this->_translator;
    }

    /**
     * Get global default translator object
     *
     * @return null|Zend_Translate
     */
    public static function getDefaultTranslator()
    {
        if (null === self::$_translatorDefault) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $translator = Zend_Registry::get('Zend_Translate');
                if ($translator instanceof Zend_Translate_Adapter) {
                    return $translator;
                } elseif ($translator instanceof Zend_Translate) {
                    return $translator->getAdapter();
                }
            }
        }
        return self::$_translatorDefault;
    }

    /**
     * Indicate whether or not translation should be disabled
     *
     * @param  bool $flag
     * @return Zend_Form
     */
    public function setDisableTranslator($flag)
    {
        $this->_translatorDisabled = (bool) $flag;
        return $this;
    }

    /**
     * Is translation disabled?
     *
     * @return bool
     */
    public function translatorIsDisabled()
    {
        return $this->_translatorDisabled;
    }
    
    
}
?>