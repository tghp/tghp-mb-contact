<?php

function tghpcontact_custom_post_types_and_taxonomies()
{
    register_post_type('contact_submission', [
        'label' => __('Contact Submission'),
        'description' => __('Contact Submissions'),
        'labels' => [
            'name' => __('Contact Submissions'),
            'singular_name' => __('Contact Submission'),
            'menu_name' => __('Contact'),
            'parent_item_colon' => __('Parent Contact Submission'),
            'all_items' => __('All Contact Submissions'),
            'view_item' => __('View Contact Submission'),
            'add_new_item' => __('Add New Contact Submission'),
            'add_new' => __('Add New'),
            'edit_item' => __('Edit Contact Submission'),
            'update_item' => __('Update Contact Submission'),
            'search_items' => __('Search Contact Submission'),
            'not_found' => __('Not Found'),
            'not_found_in_trash' => __('Not found in Trash'),
        ],
        'supports' => false,
        'taxonomies' => [],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 50,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'rewrite' => false,
        'menu_icon' => 'dashicons-email',
    ]);

    register_taxonomy(
        'contact_submission_form',
        'contact_submission',
        [
            'hierarchical' => false,
            'labels' => [
                'name' => __('Form', 'tghpcontact'),
                'singular_name' => __('Form', 'tghpcontact'),
                'search_items' => __('Search Forms', 'tghpcontact'),
                'all_items' => __('All Forms', 'tghpcontact'),
                'parent_item' => __('Parent Form', 'tghpcontact'),
                'parent_item_colon' => __('Parent Form:', 'tghpcontact'),
                'edit_item' => __('Edit Form', 'tghpcontact'),
                'update_item' => __('Update Form', 'tghpcontact'),
                'add_new_item' => __('Add New Form', 'tghpcontact'),
                'new_item_name' => __('New Form Name', 'tghpcontact'),
                'menu_name' => __('Form', 'tghpcontact'),
            ],
            'show_ui' => false,
            'show_in_menu' => false,
            'show_admin_column' => true,
            'query_var' => false,
            'rewrite' => false,
        ]
    );
}
add_action('init', 'tghpcontact_custom_post_types_and_taxonomies', 0);