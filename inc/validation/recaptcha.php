<?php

class TGHPContact_Validator_Recaptcha extends TGHPContact_Validator_Abstract {

    public static function validate($field)
    {
        $recaptcha = new \ReCaptcha\ReCaptcha($field['secret_key']);
        $response = $recaptcha->verify(self::getFieldValue($field), $_SERVER['remote_addr']);

        if($response->isSuccess()) {
            return true;
        } else {
            throw new Exception('Invalid recaptcha');
        }
    }

    public static function getFieldValue($field)
    {
        $field['id'] = 'g-recaptcha-response';
        return parent::getFieldValue($field);
    }

}