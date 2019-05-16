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
        if($_field['type'] === 'file') {
            if(isset($_FILES["_file_{$_field['id']}"])) {
                $file = $_FILES["_file_{$_field['id']}"];

                if(isset($_field['attributes']) && isset($_field['attributes']['accept'])) {
                    $acceptedMimeTypes = array_map('trim', explode(',', $_field['attributes']['accept']));
                    if(!in_array($file['type'][0], $acceptedMimeTypes)) {
                        return new WP_Error('invalid', __('File type not allowed', 'tghpcontact'));
                    }
                }

                if(isset($_field['max_file_size'])) {
                    $fileSize = $file['size'][0] / 1000;

                    if($fileSize > $_field['max_file_size']) {
                        return new WP_Error('invalid', __('File too large', 'tghpcontact'));
                    }
                }
            }
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