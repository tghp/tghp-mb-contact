<?php

class TGHPContact_Validator_Abstract {

    public static function validate($field) {
        return true;
    }

    public static function getFieldValue($field) {
        $config = isset( $_POST['rwmb_form_config'] ) ? $_POST['rwmb_form_config'] : '';

        if (empty($config)) {
            $key = $_POST['mbfs_key'];
        }

        if (empty($config) && empty($key)) {
            return;
        }

        return $_POST[$field['id']];
    }

}