<?php
/*
Plugin Name: TGHP Metabox Contact
Description: Utilise MB Frontend Submission for contact forms
Author: TGHP
Version: 1.0.0
Network: False
*/

define('TGHP_CONTACT_VERSION', '1.0.0');
define('TGHP_CONTACT_META_PREFIX', '_tghpcontact_');
define('TGHP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TGHP_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once 'vendor/google/recaptcha/autoload.php';
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

function tghpcontact_load_recaptcha_type() {
    require 'inc/meta-box/fields/recaptcha.php';
}
add_action('init', 'tghpcontact_load_recaptcha_type');

function tghpcontact_form($id = 'contact_submission', $args = [])
{
    $args['id'] = $id;

    $shortcodeArgs = '';
    foreach ($args as $key => $val) {
        $shortcodeArgs .= "{$key}=\"{$val}\"";
    }

    echo do_shortcode("[mb_frontend_form {$shortcodeArgs}]");
}