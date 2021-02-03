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
        $shortcodeArgs .= "{$key}=\"{$val}\" ";
    }

    ob_start();
    ?>
    <div class="tghpform tghpform--<?= $id ?>">
        <?= do_shortcode("[mb_frontend_form {$shortcodeArgs}]"); ?>
    </div>
    <?php
    
    $output = ob_get_clean();

    $metabox = tghpcontact_get_contact_metabox($id);

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