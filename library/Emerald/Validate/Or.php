<?php
/**
 * @author Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @package Emerald_Validate
 */
class Emerald_Validate_Or extends Emerald_Validate_Composite
{
    public function isValid($value)
    {
        foreach ($this->getValidators() as $validator) {
            if ($validator->isValid($value)) {
                return true;
            }
        }
        return false;
    }
}
