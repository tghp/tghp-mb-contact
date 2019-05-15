<?php

function tghpcontact_email_notify($config, $post_id)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    if(!$metaBox) {
        return;
    }

    if(!property_exists($metaBox, 'tghp_contact') || $metaBox->tghp_contact !== true) {
        return;
    }

    if(property_exists($metaBox, 'email') && property_exists($metaBox->email, 'title')) {
        $title = $metaBox->email->title;
    } elseif (tghpcontact_setting('title')) {
        $title = tghpcontact_setting('title');
    } else {
        $title = 'Contact Form Submission';
    }

    if(property_exists($metaBox, 'email') && property_exists($metaBox->email, 'email')) {
        $to = $metaBox->email->email;
    } else {
        $to = tghpcontact_setting('to_email');
    }

    if(isset($to)) {
        $emailFields = array_filter($metaBox->fields, function ($field) {
            return !!$field['email'];
        });

        $output = "<h1>{$title}</h1>";
        foreach($emailFields as $_emailField) {
            $label = $_emailField['name'];
            $value = rwmb_meta($_emailField['id'], null, $post_id);
            $output .= "<p><strong>{$label}</strong><br>{$value}</p>";
        }

        $title = apply_filters('tghpcontact_email_subject', $title, $config, $post_id);
        $html = apply_filters('tghpcontact_email_content', $output, $config, $post_id);

        wp_mail($to, $title, $html, "Content-Type: text/html; charset=UTF-8");
    }
}
add_action('rwmb_frontend_after_process', 'tghpcontact_email_notify', 10, 2);