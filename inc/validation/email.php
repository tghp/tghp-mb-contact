<?php

class TGHPContact_Validator_Email extends TGHPContact_Validator_Abstract
{
    public static function validate($field)
    {
        $emailValue = self::getFieldValue($field);

        if (isset($field['required']) && $field['required']) {
            // validate email address
            if (!filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email address.');
            }
        }

        return true;
    }
}