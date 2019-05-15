<?php

/**
 * Post modification
 */
function tghpcontact_pre_process ($data, $config)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    $data['post_title'] = sprintf('%s - %s', $metaBox->title, date('d m Y'));

    $id = $config['id'];
    $data['tax_input'] = array(
        'contact_submission_form' => array($id)
    );

    return $data;
}
add_filter('rwmb_frontend_insert_post_data', 'tghpcontact_pre_process', 10, 2);