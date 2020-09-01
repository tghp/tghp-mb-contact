<?php

/**
 * Post modification
 */
function tghpcontact_pre_process ($data, $config)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    $data['post_title'] = sprintf('%s - %s', $metaBox->title, date('d m Y'));

    return $data;
}
add_filter('rwmb_frontend_insert_post_data', 'tghpcontact_pre_process', 10, 2);

/**
 * After creation
 */
function tghpcontact_after_process ($config, $postId)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    wp_set_object_terms($postId, [$metaBox->id], 'contact_submission_form');

    do_action('tghpcontact_after_process', $metaBox->id, $postId);
    do_action("tghpcontact_after_process_{$metaBox->id}", $postId);
}
add_filter('rwmb_frontend_after_process', 'tghpcontact_after_process', 10, 2);

function tghpcontact_get_submission_data ($postId, $key)
{
    return rwmb_meta('_tghpcontact_' . $key, [], $postId);
}