<?php

function tghpcontact_meta_boxes($meta_boxes)
{
    /**
     * Contact submission
     */
    $contactFields = array(
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
    );
    $contactFields = apply_filters('tghpcontact_fields', $contactFields);

    foreach($contactFields as &$field) {
        $field['id'] = TGHP_CONTACT_META_PREFIX . $field['id'];
    }

    $meta_boxes[] = array(
        'id' => 'contact_submission',
        'title' => __('Contact', 'tghpcontact'),
        'post_types' => 'contact_submission',
        'fields' => $contactFields,
    );

    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'tghpcontact_meta_boxes', 100);