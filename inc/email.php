<?php

function tghpcontact_email_notify($config, $post_id)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    if(!$metaBox) {
        return;
    }

    if(!$metaBox->tghp_contact) {
        return;
    }

    if($metaBox->email && isset($metaBox->email['title'])) {
        $title = $metaBox->email['title'];
    } elseif (tghpcontact_setting('title')) {
        $title = tghpcontact_setting('title');
    } else {
        $title = 'Contact Form Submission';
    }

    if($metaBox->email && isset($metaBox->email['email'])) {
        $to = $metaBox->email['email'];
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

            switch($_emailField['type']) {
                case 'checkbox':
                    $value = ($value == 1) ? 'yes' : 'no';
                    break;
                case 'file':
                    $value = wp_get_attachment_url(array_keys($value)[0]);
                    break;
                case 'select':
                    if(isset($_emailField['options']) && isset($_emailField['options'][$value])) {
                        $value = sprintf('%s (%s)', $value, $_emailField['options'][$value]);
                    }
                    break;
            }

            switch($_emailField['type']) {
                case 'file':
                    $value = sprintf('<a href="%1$s">%1$s</a>', $value);
                    break;
            }

            if(!$value) {
                $value = '-';
            }

            $output .= "<p><strong>{$label}</strong><br>{$value}</p>";
        }

        $title = apply_filters('tghpcontact_email_subject', $title, $config, $post_id);
        $html = apply_filters('tghpcontact_email_content', $output, $config, $post_id);

        wp_mail($to, $title, $html, "Content-Type: text/html; charset=UTF-8");
    }
}
add_action('rwmb_frontend_after_process', 'tghpcontact_email_notify', 100, 2);