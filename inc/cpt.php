<?php

function tghpcontact_custom_post_types()
{
    register_post_type('contact_submission', array(
        'label' => __('Contact Submission'),
        'description' => __('Contact Submissions'),
        'labels' => array(
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
        ),
        'supports' => false,
        'taxonomies' => array(),
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
    ));
}

add_action('init', 'tghpcontact_custom_post_types', 0);