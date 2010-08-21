<?php
/**
 * @author Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Emerald_Validate_Or extends Emerald_Validate_Composite
{
    public function isValid($value, $context = null)
    {
        foreach ($this->getValidators() as $validator) {
            if ($validator->isValid($value, $context)) {
                return true;
            }
        }
        return false;
    }
}
