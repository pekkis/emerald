<?php

require_once 'Zend/Validate/Abstract.php';


/**
 * Validates a datetime (for db, from select box combo)
 * 
 * @todo: Validates in name only... Cant do it now :(
 *
 */
class Emerald_Validate_Time extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value does not appear to be a valid date
     */
    const INVALID        = 'datetimeInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID        => "'%value%' does not appear to be a valid datetime"
    );

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
    	$valueString = (string) $value;

        $this->_setValue($valueString);

        if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $valueString)) {
            $this->_error();
            return false;
        }

        return true;
    }

}
