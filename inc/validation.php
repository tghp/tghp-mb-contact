<?php

function tghpcontact_validate_request() {
    if(!$_POST['rwmb_submit']) {
        return false;
    }

    if(!$_GET['rwmb-form-submitted']) {
        return false;
    }

    $metaBox = tghpcontact_get_contact_metabox($_POST['rwmb_form_config']['id']);

    if(!$metaBox) {
        return false;
    }

    foreach($metaBox->fields as $_field) {
        $class = implode('_', array_map('ucwords', explode('_', $_field['type'])));
        $validatorClass = "TGHPContact_Validator_{$class}";

        try {
            /** @var TGHPContact_Validator_Abstract $validatorClass */
            if(class_exists($validatorClass)) {
                $validatorClass::validate($_field);
            } else {
                continue;
            }
        } catch (Exception $e) {
            return new WP_Error('form_invalid', $e->getMessage());
        }
    }

    return true;
}

function tghpcontact_rwmb_frontend_redirect($url) {
    $validation = tghpcontact_validate_request();

    if(is_wp_error($validation)) {
        $url = add_query_arg('rwmb-form-error', urlencode($validation->get_error_message()), $url);
    } else {
        $url = remove_query_arg('rwmb-form-error', $url);
    }

    return $url;
}

add_filter('rwmb_frontend_redirect', 'tghpcontact_rwmb_frontend_redirect');

function tghpcontact_rwmb_frontend_validate_file($is_valid, $config) {
    $validation = tghpcontact_validate_request();

    return $validation === true || !is_wp_error(tghpcontact_validate_request());
}
add_filter('rwmb_frontend_validate', 'tghpcontact_rwmb_frontend_validate_file', 10, 2);