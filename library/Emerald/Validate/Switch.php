<?php
/**
 * Given a switch validator and two target validators, chooses a target
 * validator based on the switch validator's return value
 *
 * @author Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @package Emerald_Validate
 */
class Emerald_Validate_Switch extends Emerald_Validate_Outer
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_success;

    /**
     * @var Zend_Validate_Interface
     */
    protected $_failure;

    /**
     * @var boolean
     */
    protected $_valid;

    /**
     * @param Zend_Validate_Interface $validator
     * @param Zend_Validate_Interface $success
     * @param Zend_Validate_Interface $failure
     * @return void
     */
    public function __construct($validator, $success, $failure)
    {
        parent::__construct($validator);
        $this->_success = $success;
        $this->_failure = $failure;
    }

    /**
     * @return Zend_Validate_Interface
     */
    public function getSuccessValidator()
    {
        return $this->_success;
    }

    /**
     * @return Zend_Validate_Interface
     */
    public function getFailureValidator()
    {
        return $this->_failure;
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_valid = $this->getValidator()->isValid($value);
        return $this->getSelectedValidator()->isValid($value);
    }

    /**
     * @return Zend_Validate_Interface
     */
    public function getSelectedValidator()
    {
        return ($this->_valid) ? $this->getSuccessValidator() : $this->getFailureValidator();
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        if (null === $this->_valid) {
            return array();
        }

        return $this->getSelectedValidator()->getErrors();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        if (null === $this->_valid) {
            return array();
        }

        return $this->getSelectedValidator()->getMessages();
    }

}
