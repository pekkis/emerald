<?php
/**
 * Validates a given context field with a given validator
 */
class Emerald_Validate_Context extends Emerald_Validate_Outer
{
    /**
     * @var string
     */
    protected $_field;

    /**
     * @param string $field
     * @param Zend_Validate_Interface $validator
     */
    public function __construct($field, $validator)
    {
        $this->_field = $field;
        parent::__construct($validator);
    }

    public function isValid($value, $context = null)
    {
        if (!is_array($context)) {
            return false;
        }

        $value = isset($context[$this->_field]) ? $context[$this->_field] : null;
        
        return parent::isValid($value);
    }
}