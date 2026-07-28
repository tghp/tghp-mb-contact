<?php

if (class_exists('RWMB_Field') && !class_exists('RWMB_Recaptcha_Field')) {

    class RWMB_Recaptcha_Field extends RWMB_Field
    {

        public static function admin_enqueue_scripts()
        {
            parent::admin_enqueue_scripts();
            RWMB_File_Upload_Field::admin_enqueue_scripts();
            wp_enqueue_script('tghpcontact-rwmb-recaptcha', TGHP_PLUGIN_URL . 'js/recaptcha.js', ['jquery'], TGHP_CONTACT_VERSION, true);
        }

        public static function html($meta, $field)
        {
            if (is_admin()) {
                return '';
            }

            if (isset($field['version']) && (int) $field['version'] === 3) {
                // Client side is handled by MB Frontend Submission's native v3
                // support — tghpcontact_form() passes recaptcha_key to the
                // [mb_frontend_form] shortcode, MBFS enqueues api.js, executes
                // grecaptcha on submit and posts the token as mbfs_recaptcha_token
                return '';
            }

            $randomId = substr(uniqid('', true), -5);

            return sprintf(
                '<div class="rwmb-recaptcha" id="%1$s" data-key="%2$s" data-callback="tghpmbcontactOnRecaptchaSuccess%3$s" data-expired-callback="tghpmbcontactOnRecaptchaExpire%3$s"></div>%4$s',
                $field['id'],
                esc_attr($field['site_key']),
                $randomId,
                '<input type="hidden" name="recaptcha_fake" required>'
            );
        }

    }

}