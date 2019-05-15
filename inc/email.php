<?php

function tghpcontact_email_notify($config, $post_id)
{
    if($config['id'] !== 'contact_submission') {
        return;
    }

    /** @var RWMB_Meta_Box_Registry $metaBox */
    $metaBoxRegistry = rwmb_get_registry('meta_box');

    /** @var RW_Meta_Box $metaBox */
    $metaBox = $metaBoxRegistry->get('contact_submission');

    $emailFields = array_filter($metaBox->fields, function ($field) {
        return !!$field['email'];
    });

    $title = tghpcontact_setting('title') ?: 'Contact Form Submission';

    $output = "<h1>{$title}</h1>";
    foreach($emailFields as $_emailField) {
        $label = $_emailField['name'];
        $value = rwmb_meta($_emailField['id'], null, $post_id);
        $output .= "<p><strong>{$label}</strong><br>{$value}</p>";
    }

    $title = apply_filters('tghpcontact_email_subject', $title, $config, $post_id);
    $html = apply_filters('tghpcontact_email_content', $output, $config, $post_id);

    wp_mail(tghpcontact_setting('to_email'), $title, $html, "Content-Type: text/html; charset=UTF-8");
}
add_action('rwmb_frontend_after_process', 'tghpcontact_email_notify', 10, 2);