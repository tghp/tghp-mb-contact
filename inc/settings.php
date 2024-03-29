<?php

function tghpcontact_setting($key)
{
    return rwmb_meta($key, ['object_type' => 'setting'], 'tghpcontact_options');
}

function tghpcontact_settings_page($settings_pages)
{

    $settings_pages[] = [
        'id' => 'tghpcontact-options',
        'menu_title' => 'Contact',
        'columns' => 1,
        'style' => 'no-boxes',
        'option_name' => 'tghpcontact_options',
        'parent' => 'options-general.php',
    ];

    return $settings_pages;
}
add_filter('mb_settings_pages', 'tghpcontact_settings_page');

function tghpcontact_settings_meta_boxes($meta_boxes)
{

    $meta_boxes[] = [
        'id' => 'general',
        'title' => 'General',
        'settings_pages' => 'tghpcontact-options',
        'fields' => [
            [
                'name' => 'Custom Email Subject/Title',
                'id' => 'title',
                'type' => 'input',
            ],
            [
                'name' => 'Email To Address',
                'id' => 'to_email',
                'type' => 'email',
            ],
        ],
    ];

    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'tghpcontact_settings_meta_boxes');