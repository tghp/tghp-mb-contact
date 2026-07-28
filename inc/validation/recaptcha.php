<?php

class TGHPContact_Validator_Recaptcha extends TGHPContact_Validator_Abstract
{

    public static function validate($field)
    {
        $recaptcha = new \ReCaptcha\ReCaptcha($field['secret_key']);

        if (isset($field['version']) && (int) $field['version'] === 3) {
            // MB Frontend Submission's JS executes grecaptcha with a hardcoded 'mbfs' action
            $recaptcha->setExpectedAction('mbfs')
                ->setScoreThreshold((float) $field['score_threshold']);
        }

        $response = $recaptcha->verify(self::getFieldValue($field), $_SERVER['REMOTE_ADDR'] ?? null);

        if ($response->isSuccess()) {
            return true;
        } else {
            throw new Exception('Invalid recaptcha');
        }
    }

    public static function getFieldValue($field)
    {
        if (isset($field['version']) && (int) $field['version'] === 3) {
            // Token posted by MB Frontend Submission's native v3 support
            $field['id'] = 'mbfs_recaptcha_token';
        } else {
            $field['id'] = 'g-recaptcha-response';
        }

        return parent::getFieldValue($field);
    }

}