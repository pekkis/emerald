<?php
/**
 * @author Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Emerald_Validate_And extends Emerald_Validate_Composite
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
