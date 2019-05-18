<?php

if (class_exists('RWMB_Field') && !class_exists('RWMB_Recaptcha_Field')) {

    class RWMB_Recaptcha_Field extends RWMB_Field {

        public static function admin_enqueue_scripts() {
            parent::admin_enqueue_scripts();
            RWMB_File_Upload_Field::admin_enqueue_scripts();
            wp_enqueue_script( 'tghpcontact-rwmb-recaptcha', TGHP_PLUGIN_URL . 'js/recaptcha.js', array( 'jquery' ), TGHP_CONTACT_VERSION, true );
        }

        public static function html($meta, $field) {
            return sprintf(
                '<div class="rwmb-recaptcha" id="%s" data-key="%s"></div>',
                $field['id'],
                esc_attr( $field['site_key'] )
            );
        }

    }

}