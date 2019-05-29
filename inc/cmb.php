<?php

function tghpcontact_meta_boxes($meta_boxes)
{
    $contactForms = array(
        'contact_submission' => array (
            'title' => __('Contact', 'tghpcontact'),
            'fields' => array(
                array(
                    'name' => 'Name',
                    'id' => 'name',
                    'type' => 'input',
                    'required' => true,
                    'email' => true,
                ),
                array(
                    'name' => 'Email',
                    'id' => 'email',
                    'type' => 'email',
                    'required' => true,
                    'email' => true,
                ),
                array(
                    'name' => 'Telephone',
                    'id' => 'telephone',
                    'type' => 'input',
                    'email' => true,
                ),
                array(
                    'name' => 'Message',
                    'id' => 'message',
                    'type' => 'textarea',
                    'email' => true,
                ),
            )
        )
    );

    $contactForms = apply_filters('tghpcontact_forms', $contactForms);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    foreach($contactForms as $_formID => $_form) {
        $contactFields = $_form['fields'];
        $contactFields = apply_filters('tghpcontact_fields', $contactFields, $_form);
        $contactFields = apply_filters("tghpcontact_fields_{$_form}", $contactFields);

        foreach($contactFields as &$field) {
            $field['id'] = TGHP_CONTACT_META_PREFIX . $field['id'];
            $field['class'] = $field['class'] . sprintf(' field-%s', str_replace(TGHP_CONTACT_META_PREFIX, '', $field['id']));

            if($field['required']) {
                if($field['type'] === 'select') {
                    if(isset($field['options'][''])) {
                        $field['options'][''] .= '*';
                    } else {
                        $field['options'][array_keys($field['options'])[0]] .= '*';
                    }
                } else {
                    $field['name'] .= '*';
                }
            }

            if($field['type'] === 'recaptcha' || $field['type'] === 'file') {
                $field['populate_after_error'] = false;
            }

            if($field['type'] === 'recaptcha') {
                $field['site_key'] = getenv(sprintf('RECAPTCHA_KEY_SITE_%s', strtoupper($_formID)));
                $field['secret_key'] = getenv(sprintf('RECAPTCHA_KEY_SECRET_%s', strtoupper($_formID)));
            }

            if($field['populate_after_error'] !== false && isset($_SESSION['rwmb_frontend_post']) && isset($_SESSION['rwmb_frontend_post'][$field['id']])) {
                $field['std'] = $_SESSION['rwmb_frontend_post'][$field['id']];
            }
        }

        $metaBox = array(
            'id' => $_formID,
            'title' => $_form['title'],
            'class' => 'form',
            'post_types' => 'contact_submission',
            'tghp_contact' => true,
            'include' => array(
                'relation' => 'OR',
                'contact_submission_form' => array($_formID),
                'custom' => 'tghpcontact_include_form_frontend'
            ),
            'fields' => $contactFields,
        );

        if(isset($_form['options'])) {
            $metaBox = array_merge($metaBox, $_form['options']);
        }

        $meta_boxes[] = $metaBox;
    }

    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'tghpcontact_meta_boxes', 100);

function tghpcontact_include_form_frontend() {
    return !is_admin();
}


/**
 * Get specific metabox
 *
 * @param $id
 * @return RW_Meta_Box|bool
 */
function tghpcontact_get_contact_metabox($id) {
    /** @var RWMB_Meta_Box_Registry $metaBox */
    $metaBoxRegistry = rwmb_get_registry('meta_box');

    /** @var RW_Meta_Box $metaBox */
    $metaBox = $metaBoxRegistry->get($id);

    return $metaBox;
}