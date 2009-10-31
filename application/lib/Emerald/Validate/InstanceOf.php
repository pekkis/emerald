<?php

require_once 'Zend/Validate/Abstract.php';


/**
 * Validates a datetime (for db, from select box combo)
 * 
 * @todo: Validates in name only... Cant do it now :(
 *
 */
class Emerald_Validate_InstanceOf extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value does not appear to be a valid date
     */
    const INVALID        = 'classInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID        => "'%value%' does not appear to be an valid object instance"
    );

    
    protected $_class;
    
    
    public function __construct($class)
    {
    	$this->_class = $class;
    }
    
    
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid date of the format YYYY-MM-DD HH:MM:SS
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
		return ($value instanceof $this->_class) ? true : false;
        
    }

}
