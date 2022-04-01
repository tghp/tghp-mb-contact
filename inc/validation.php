<?php

function tghpcontact_validate_request() {
    $data = (array) $_POST;

    if($data['rwmb_submit'] || $data['action'] === 'mbfs_submit') {
        if (is_array($data['rwmb_form_config']) && isset($data['rwmb_form_config']['id'])) {
            $id = filter_var($data['rwmb_form_config']['id'], FILTER_SANITIZE_STRING);
        } else if (is_string($data['rwmb_form_config'])) {
            $config_key = filter_var($data['rwmb_form_config'], FILTER_SANITIZE_STRING);
            $config = \MBFS\ConfigStorage::get($config_key);
            $id = $config['id'];
        } else {
            $config = \MBFS\ConfigStorage::get($data['mbfs_key']);
            $id = $config['id'];
        }

        if (isset($id)) {
            $metaBox = tghpcontact_get_contact_metabox($id);
        }

        if (isset($id)) {
            $metaBox = tghpcontact_get_contact_metabox($id);
        }

        if (!isset($metaBox) || !$metaBox) {
            return false;
        }

        foreach ($metaBox->fields as $_field) {
            $class = implode('_', array_map('ucwords', explode('_', $_field['type'])));
            $validatorClass = "TGHPContact_Validator_{$class}";

            try {
                /** @var TGHPContact_Validator_Abstract $validatorClass */
                if (class_exists($validatorClass)) {
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

        if (is_wp_error($filteredError)) {
            /** @var $filteredError WP_Error */
            return $filteredError;
        }

        return true;
    }
    return false;
}

function tghpcontact_rwmb_error_frontend_redirect($url) {
    global $tghpcontact_rwmb_validation;

    if(is_wp_error($tghpcontact_rwmb_validation) || $tghpcontact_rwmb_validation === false) {
        $url = add_query_arg('rwmb-form-error', '1', $url);
        $_SESSION['rwmb_frontend_post'] = array_filter($_POST, 'tghpcontact_filter_input_values_from_post', ARRAY_FILTER_USE_KEY);
    } else {
        $url = remove_query_arg('rwmb-form-error', $url);
        unset($_SESSION['rwmb_frontend_post']);
    }

    return $url;
}
add_filter('rwmb_frontend_redirect', 'tghpcontact_rwmb_error_frontend_redirect');

function tghpcontact_filter_input_values_from_post($key) {
    return strpos($key, '_tghpcontact') === 0;
}

function tghpcontact_rwmb_frontend_validate_file($is_valid, $config) {
    global $tghpcontact_rwmb_validation;
    $tghpcontact_rwmb_validation = tghpcontact_validate_request();

    if(is_wp_error($tghpcontact_rwmb_validation)) {
        return $tghpcontact_rwmb_validation->get_error_message();
    } else {
        return $tghpcontact_rwmb_validation;
    }
}
add_filter('rwmb_frontend_validate', 'tghpcontact_rwmb_frontend_validate_file', 10, 2);