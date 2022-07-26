<?php

function tghpcontact_email_get_field_label($field)
{
    if (!empty($field['name'])) {
        return $field['name'];
    }

    return ucwords(
        implode(
            ' ',
            explode('_', $field['id'])
        )
    );
}

function tghpcontact_email_format_value($field, $value)
{
    switch ($field['type']) {
        case 'checkbox':
            $value = ($value == 1) ? 'yes' : 'no';
            break;
        case 'file':
            $value = wp_get_attachment_url(array_keys($value)[0]);
            break;
        case 'select':
        case 'radio':
            if (isset($field['options']) && isset($field['options'][$value])) {
                $value = sprintf('%s (%s)', $value, $field['options'][$value]);
            }
            break;
        case 'group':
            if (is_array($value)) {
                $newValue = '';
                foreach ($field['fields'] as $_subField) {
                    $subLabel = tghpcontact_email_get_field_label($_subField);
                    $subValue = tghpcontact_email_format_value($_subField, $value[$_subField['id']]);
                    $newValue .= "{$subLabel}: {$subValue}<br>";
                }
                $value = $newValue;
            }
            break;
    }

    switch ($field['type']) {
        case 'file':
            $value = sprintf('<a href="%1$s">%1$s</a>', $value);
            break;
    }

    if (!$value) {
        $value = '-';
    }

    return $value;
}

function tghpcontact_email_notify($config, $post_id)
{
    $metaBox = tghpcontact_get_contact_metabox($config['id']);

    if (!$metaBox) {
        return;
    }

    if (!$metaBox->tghp_contact) {
        return;
    }

    if (!$metaBox->tghp_send_email) {
        return;
    }

    if ($metaBox->email && isset($metaBox->email['title'])) {
        $title = $metaBox->email['title'];
    } elseif (tghpcontact_setting('title')) {
        $title = tghpcontact_setting('title');
    } else {
        $title = 'Contact Form Submission';
    }

    if ($metaBox->email && isset($metaBox->email['email'])) {
        $to = $metaBox->email['email'];
    } else {
        $to = tghpcontact_setting('to_email');
    }


    if (isset($to)) {
        $emailFields = array_filter($metaBox->fields, function ($field) {
            return !!$field['email'];
        });

        $output = "<h1>{$title}</h1>";
        foreach ($emailFields as $_emailField) {
            $label = tghpcontact_email_get_field_label($_emailField);
            $value = tghpcontact_email_format_value($_emailField, rwmb_meta($_emailField['id'], null, $post_id));
            $output .= "<p><strong>{$label}</strong><br>{$value}</p>";
        }

        $to = apply_filters('tghpcontact_email_to', $to, $config, $post_id);
        $title = apply_filters('tghpcontact_email_subject', $title, $config, $post_id);
        $html = apply_filters('tghpcontact_email_content', $output, $config, $post_id);

        wp_mail($to, $title, $html, "Content-Type: text/html; charset=UTF-8");
    }
}
add_action('rwmb_frontend_after_process', 'tghpcontact_email_notify', 100, 2);