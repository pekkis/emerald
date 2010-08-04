<?php
/**
 * Wraps a validator
 *
 * @author Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Emerald_Validate_Outer implements Zend_Validate_Interface
{
    /**
     * Inner validator
     *
     * @var Zend_Validate_Interface
     */
    protected $_validator;

    /**
     * @param Zend_Validate_Interface $validator
     * @return void
     */
    public function __construct($validator)
    {
        $this->_validator = $validator;
    }

    /**
     * Retrieve inner validator
     *
     * @return Zend_Validator_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }

    public function getMessages()
    {
        return $this->getValidator()->getMessages();
    }

    public function getErrors()
    {
        return $this->getValidator()->getErrors();
    }

    public function isValid($value)
    {
        return $this->getValidator()->isValid($value);
    }
}
