<?php

/**
 * Post modification
 */
function tghpcontact_pre_process ($data, $config)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    $data['post_title'] = sprintf('%s - %s', $metaBox->title, date('d m Y'));

    $data['tax_input'] = array(
        'contact_submission_form' => array($metaBox->id)
    );

    return $data;
}
add_filter('rwmb_frontend_insert_post_data', 'tghpcontact_pre_process', 10, 2);

/**
 * After creation
 */
function tghpcontact_after_process ($config, $postId)
{
    $id = $config['id'];
    do_action('tghpcontact_after_process', $id, $postId);
    do_action("tghpcontact_after_process_{$id}", $postId);
}
add_filter('rwmb_frontend_after_process', 'tghpcontact_after_process', 10, 2);

function tghpcontact_get_submission_data ($postId, $key)
{
    return rwmb_meta('_tghpcontact_' . $key, [], $postId);
}