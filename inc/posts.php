<?php

/**
 * Post modification
 */
function tghpcontact_pre_process ($data, $config)
{
    $data['post_title'] = sprintf('%s - %s', $_POST[TGHP_CONTACT_META_PREFIX . 'name'], date('d m Y'));

    return $data;
}
add_filter('rwmb_frontend_insert_post_data', 'tghpcontact_pre_process', 10, 2);