<?php
/**
 * @author Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @package Emerald_Common_Validate
 */
class Emerald_Common_Validate_And extends Emerald_Common_Validate_Composite
{
    public function isValid($value)
    {
        $valid = true;
        foreach ($this->_validators as $validator) {
            $valid = $validator->isValid($value) && $valid;
        }
        return $valid;
    }
}
