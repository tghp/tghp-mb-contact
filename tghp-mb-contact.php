<?php
/*
Plugin Name: TGHP Metabox Contact
Description: Utilise MB Frontend Submission for contact forms
Author: TGHP
Version: 1.3.0
Network: False
*/

define('TGHP_CONTACT_VERSION', '1.3.0');
define('TGHP_CONTACT_META_PREFIX', '_tghpcontact_');
define('TGHP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TGHP_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once 'vendor/google/recaptcha/autoload.php';
include_once 'inc/graphql.php';
include_once 'inc/autoloader.php';
include_once 'inc/cpt.php';
include_once 'inc/cmb.php';
include_once 'inc/settings.php';
include_once 'inc/validation.php';
include_once 'inc/posts.php';
include_once 'inc/redirect.php';
include_once 'inc/email.php';
include_once 'inc/frontend.php';
include_once 'inc/admin.php';

function tghpcontact_load_types()
{
    require 'inc/meta-box/fields/recaptcha.php';
    require 'inc/meta-box/fields/dynamic-std-input.php';
}

add_action('init', 'tghpcontact_load_types');

function tghpcontact_form($id = 'contact_submission', $args = [])
{
    $args['id'] = $id;

    $shortcodeArgs = '';
    foreach ($args as $key => $val) {
        $shortcodeArgs .= "{$key}=\"{$val}\" ";
    }

    $metabox = tghpcontact_get_contact_metabox($id);

    foreach (['ajax', 'confirmation'] as $copyFromOptionsToMetaboxKey) {
        if ($metabox->$copyFromOptionsToMetaboxKey) {
            $val = $metabox->$copyFromOptionsToMetaboxKey;

            if ($val === true || $val === false) {
                $val = $val ? 'true' : 'false';
            }

            $val = esc_attr($val);

            $shortcodeArgs .= "{$copyFromOptionsToMetaboxKey}=\"{$val}\" ";
        }
    }

    // For v3 recaptcha fields, hand the client side to MB Frontend Submission's
    // native support (recaptcha_secret is deliberately not passed — verification
    // stays with our validator, and siteverify tokens are single-use)
    $recaptchaV3SiteKey = null;

    foreach ($metabox->fields as $_field) {
        if ($_field['type'] === 'recaptcha' && isset($_field['version']) && (int) $_field['version'] === 3) {
            $recaptchaV3SiteKey = $_field['site_key'];
            $shortcodeArgs .= sprintf('recaptcha_key="%s" ', esc_attr($recaptchaV3SiteKey));
            break;
        }
    }

    ob_start();
    ?>
    <div class="tghpform tghpform--<?= $id ?>">
        <?= do_shortcode("[mb_frontend_form {$shortcodeArgs}]"); ?>
    </div>
    <?php

    $output = ob_get_clean();

    // Every MBFS form on the page re-localizes the shared mbFrontendForm global,
    // so a later form without recaptcha clobbers recaptchaKey. The MBFS JS reads
    // it at submit time, so restore it after load. Must run after do_shortcode —
    // the mbfs handle is only registered once a form has rendered.
    if ($recaptchaV3SiteKey) {
        wp_add_inline_script(
            'mbfs',
            sprintf(
                'if (window.mbFrontendForm) { window.mbFrontendForm.recaptchaKey = window.mbFrontendForm.recaptchaKey || %s; window.mbFrontendForm.captchaExecuteError = window.mbFrontendForm.captchaExecuteError || %s; }',
                json_encode($recaptchaV3SiteKey),
                json_encode(__('Error trying to execute grecaptcha.', 'tghpcontact'))
            )
        );
    }

    if ($metabox->button_class) {
        $output = preg_replace('/(<button.*?rwmb-button)/', "$1 {$metabox->button_class}", $output);
    }

    if ($metabox->submit_class) {
        $output = preg_replace('/(<button.*rwmb-button[^"]*)(".*rwmb_submit)/', "$1 {$metabox->submit_class}$2", $output);
    }

    if ($metabox->submit_text_sr_only) {
        $output = preg_replace('/("rwmb_submit"[^>]*>)([^<]*?)<\/button>/', "$1<span class=\"sr-only\">$2</span></button>", $output);
    }

    echo $output;
}