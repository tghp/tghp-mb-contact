<?php

use MBFS\FormFactory;

function tghpcontact_graphql_label($input)
{
    $graphql_label = preg_replace('/[-_]/', ' ', $input);
    $graphql_label = ucwords($graphql_label);
    $graphql_label = preg_replace('/ /', '', $graphql_label);
    $graphql_label = lcfirst($graphql_label);

    return $graphql_label;
}

function tghpcontact_add_graphql_form_mutation($metaBox) {
    $mutationType = 'tghpcontactForm' . ucfirst(tghpcontact_graphql_label($metaBox['id']));

    $inputFields = [];
    $graphQlIdToMetaboxId = [];

    foreach ($metaBox['fields'] as $field) {
        if (empty($field['id']) || empty($field['type'])) {
            continue;
        }

        $graphQlId = tghpcontact_graphql_label($field['id']);
        $graphQlIdToMetaboxId[$graphQlId] = $field['id'];
        $required = isset($field['required']) && !!$field['required'];

        if ($field['clone'] !== true && $field['multiple'] !== true) {
            switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'email':
                    $inputFields[$graphQlId] = [
                        'type' => $required ? ['non_null' => 'String'] : 'String',
                    ];
                    break;
            }
        }
    }

    register_graphql_mutation($mutationType, [
        'inputFields' => $inputFields,

        'outputFields' => [
            'success' => [
                'type' => 'Boolean',
            ],
            'errorMessage' => [
                'type' => 'String',
            ]
        ],

        'mutateAndGetPayload' => function ($input, $context, $info) use ($metaBox, $graphQlIdToMetaboxId) {
            $_POST["nonce_{$metaBox['id']}"] = wp_create_nonce("rwmb-save-{$metaBox['id']}");
            $_POST['rwmb_submit'] = true;
            $_POST['rwmb_form_config'] = [
                'id' => $metaBox['id'],
            ];

            $form = FormFactory::make([
                'id' => $metaBox['id']
            ]);

            foreach ($graphQlIdToMetaboxId as $graphQlId => $metaBoxId) {
                if (isset($input[$graphQlId])) {
                    $_POST[$metaBoxId] = $input[$graphQlId];
                }
            }

            $form->process();

            if ($form->error->has_errors()) {
                return [
                    'success' => false,
                    'errorMessage' => implode(PHP_EOL, $form->error->get_error_messages()),
                ];
            }

            return [
                'success' => true,
            ];
        }
    ]);
}