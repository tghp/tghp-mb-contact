<?php

function tghpcontact_validate_request() {
    if(!$_POST['rwmb_submit']) {
        return false;
    }

    if(!$_GET['rwmb-form-submitted']) {
        return false;
    }

    $id = $_POST['rwmb_form_config']['id'];
    $metaBox = tghpcontact_get_contact_metabox($id);

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

    $filteredError = false;
    $filteredError = apply_filters('tghpcontact_during_process', $filteredError, $id);
    $filteredError = apply_filters("tghpcontact_during_process_{$id}", $filteredError);

    if(is_wp_error($filteredError)) {
        /** @var $filteredError WP_Error */
        return $filteredError;
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

    if(is_wp_error($validation)) {
        return $validation->get_error_message();
    } else {
        return $validation;
    }
}
add_filter('rwmb_frontend_validate', 'tghpcontact_rwmb_frontend_validate_file', 10, 2);