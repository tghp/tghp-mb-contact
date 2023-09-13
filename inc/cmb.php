<?php

function tghpcontact_meta_boxes($meta_boxes)
{
    $contactForms = [
        'contact_submission' => [
            'title' => __('Contact', 'tghpcontact'),
            'fields' => [
                [
                    'name' => 'Name',
                    'id' => 'name',
                    'type' => 'text',
                    'required' => true,
                    'email' => true,
                ],
                [
                    'name' => 'Email',
                    'id' => 'email',
                    'type' => 'email',
                    'required' => true,
                    'email' => true,
                ],
                [
                    'name' => 'Telephone',
                    'id' => 'telephone',
                    'type' => 'text',
                    'email' => true,
                ],
                [
                    'name' => 'Message',
                    'id' => 'message',
                    'type' => 'textarea',
                    'email' => true,
                ],
            ],
        ],
    ];

    $contactForms = apply_filters('tghpcontact_forms', $contactForms);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    foreach ($contactForms as $_formID => $_form) {
        $contactFields = $_form['fields'];
        $contactFields = apply_filters('tghpcontact_fields', $contactFields, $_form);
        $contactFields = apply_filters("tghpcontact_fields_{$_formID}", $contactFields);

        foreach ($contactFields as &$field) {
            if (isset($field['id'])) {
                $field['id'] = TGHP_CONTACT_META_PREFIX . $field['id'];
                $field['class'] = (isset($field['class']) ? $field['class'] : '') . sprintf(' field-%s', str_replace(TGHP_CONTACT_META_PREFIX, '', $field['id']));
            }

            if (defined('RWMB_VER') && version_compare(RWMB_VER, '5.2.4', '<')) {
                if (isset($field['required']) && $field['required']) {
                    if ($field['type'] === 'select') {
                        if (isset($field['options'][''])) {
                            $field['options'][''] .= '*';
                        } else {
                            $field['options'][array_keys($field['options'])[0]] .= '*';
                        }
                    } else {
                        $field['name'] .= '*';
                    }
                }
            }

            if ($field['type'] === 'recaptcha' || $field['type'] === 'file') {
                $field['populate_after_error'] = false;
            }

            if ($field['type'] === 'recaptcha') {
                $field['site_key'] = $_ENV[sprintf('RECAPTCHA_KEY_SITE_%s', strtoupper($_formID))];
                $field['secret_key'] = $_ENV[sprintf('RECAPTCHA_KEY_SECRET_%s', strtoupper($_formID))];
            }

            if (isset($field['id']) && isset($field['populate_after_error']) && $field['populate_after_error'] !== false &&
                isset($_SESSION['rwmb_frontend_post']) && isset($_SESSION['rwmb_frontend_post'][$field['id']])) {
                $field['std'] = $_SESSION['rwmb_frontend_post'][$field['id']];
            }

            if (!empty($field['std'])) {
                $field['class'] = $field['class'] . ' has-value';
            }
        }

        $metaBox = [
            'id' => $_formID,
            'title' => $_form['title'],
            'class' => 'form',
            'post_types' => 'contact_submission',
            'tghp_contact' => true,
            'tghp_send_email' => true,
            'include' => [
                'relation' => 'OR',
                'contact_submission_form' => [$_formID],
                'custom' => 'tghpcontact_include_form_frontend',
            ],
            'fields' => $contactFields,
        ];

        if (isset($_form['options'])) {
            $metaBox = array_merge($metaBox, $_form['options']);
        }

        if (isset($_form['tghp_send_email']) && $_form['tghp_send_email'] === false) {
            $metaBox['tghp_send_email'] = false;
        }

        $meta_boxes[] = $metaBox;

        if (is_admin()) {
            $actionFields = [];

            if (isset($_GET['post'])) {
                $resendEmailUrl = admin_url(
                    sprintf('admin.php?action=tghpcontact-resend-email&post=%s&config=%s', $_GET['post'], $_formID)
                );

                $actionFields = array_merge($actionFields, array_filter(
                    [
                        (isset($_GET['post']) && isset($metaBox['email'])) ? [
                            'type' => 'custom_html',
                            'std' => '<a href="' . $resendEmailUrl . '" class="button">' . __('Resend Email', 'tghpcontact') . '</a>',
                        ] : null,
                    ]
                ));
            }

            if (count($actionFields)) {
                $meta_boxes[] = [
                    'id' => "{$_formID}_actions",
                    'title' => __('Actions', 'tghpcontact'),
                    'post_types' => 'contact_submission',
                    'include' => [
                        'relation' => 'OR',
                        'contact_submission_form' => [$_formID],
                        'custom' => 'tghpcontact_include_form_frontend',
                    ],
                    'fields' => $actionFields,
                ];
            }
        }

        add_action('init_graphql_request', function () use ($metaBox) {
            add_action('graphql_register_types', function() use ($metaBox) {
                tghpcontact_add_graphql_form_mutation($metaBox);
            });
        });

    }

    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'tghpcontact_meta_boxes', 100);

function tghpcontact_include_form_frontend()
{
    return !is_admin();
}


/**
 * Get specific metabox
 *
 * @param $id
 * @return RW_Meta_Box|bool
 */
function tghpcontact_get_contact_metabox($id)
{
    /** @var RWMB_Meta_Box_Registry $metaBox */
    $metaBoxRegistry = rwmb_get_registry('meta_box');

    /** @var RW_Meta_Box $metaBox */
    $metaBox = $metaBoxRegistry->get($id);

    return $metaBox;
}