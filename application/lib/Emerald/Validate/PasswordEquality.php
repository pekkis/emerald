<?php
require_once 'Zend/Validate/Abstract.php';

/**
 * Validates if two passwords are equal
 */ 
class Emerald_Validate_PasswordEquality extends Zend_Validate_Abstract
{
	const INVALID = "passwordNotEqual";
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' fields do not equal"
    );

    public function isValid($value)
    {
        $this->_setValue($value);
        try
        {
			if(!is_array($value) || count($value) != 2) throw new Exception();
			
			$first = array_pop($value);
			$second = array_pop($value);
			
			if($first != $second)  throw new Exception();
			
		}catch(Exception $e)
		{
			$this->_error();
			return false;
		}
       return true;
    }
}

