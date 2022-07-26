<?php

function tghpcontact_remove_wp_seo_meta_box()
{
    remove_meta_box('wpseo_meta', 'contact_submission', 'normal');
}
add_action('add_meta_boxes', 'tghpcontact_remove_wp_seo_meta_box', 100);