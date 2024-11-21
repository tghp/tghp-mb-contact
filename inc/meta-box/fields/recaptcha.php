<?php

if (class_exists('RWMB_Field') && !class_exists('RWMB_Recaptcha_Field')) {

    class RWMB_Recaptcha_Field extends RWMB_Field
    {

        public static function admin_enqueue_scripts()
        {
            parent::admin_enqueue_scripts();
            RWMB_File_Upload_Field::admin_enqueue_scripts();
            // wp_enqueue_script('tghpcontact-rwmb-recaptcha-v3', TGHP_PLUGIN_URL . 'js/recaptcha-v3.js', ['jquery'], TGHP_CONTACT_VERSION, true);
        }

        public static function html($meta, $field)
        {

            if (is_admin()) {
                return '';
            }

            $randomId = substr(uniqid('', true), -5);

            if ($field['version'] === 3) {

                wp_enqueue_script('tghpcontact-rwmb-recaptcha-v3', TGHP_PLUGIN_URL . 'js/recaptcha-v3.js', ['jquery'], TGHP_CONTACT_VERSION, true);

                return sprintf(
                    '<div class="rwmb-recaptcha-v3" id="%1$s" data-key="%2$s" data-callback="tghpmbcontactOnRecaptchaSuccess%3$s" data-expired-callback="tghpmbcontactOnRecaptchaExpire%3$s"></div>%4$s',
                    $field['id'],
                    esc_attr($field['site_key']),
                    $randomId,
                    '<input type="hidden" name="recaptcha_fake" required>'
                );
            }

            wp_enqueue_script('tghpcontact-rwmb-recaptcha', TGHP_PLUGIN_URL . 'js/recaptcha.js', ['jquery'], TGHP_CONTACT_VERSION, true);

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
