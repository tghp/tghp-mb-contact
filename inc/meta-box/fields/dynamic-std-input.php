<?php

if (class_exists('RWMB_Field') && !class_exists('RWMB_Dynamic_Std_Input_Field')) {

    class RWMB_Dynamic_Std_Input_Field extends RWMB_Input_Field
    {

        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html( $meta, $field ) {
            $output = '';

            if ( $field['prepend'] || $field['append'] ) {
                $output = '<div class="rwmb-input-group">';
            }

            if ( $field['prepend'] ) {
                $output .= '<span class="rwmb-input-group-text">' . $field['prepend'] . '</span>';
            }

            $attributes = self::call( 'get_attributes', $field, $meta );
            $output    .= sprintf( '<input %s>%s', self::render_attributes( $attributes ), self::datalist( $field ) );

            if ( $field['append'] ) {
                $output .= '<span class="rwmb-input-group-text">' . $field['append']. '</span>';
            }

            if ( $field['prepend'] || $field['append'] ) {
                $output .= '</div>';
            }

            return $output;
        }

        public static function get_attributes( $field, $value = null )
        {
            $attributes = parent::get_attributes($field, $value);

            if (isset($attributes['value']) && is_callable($attributes['value'])) {
                $attributes['value'] = call_user_func($attributes['value']);
            }

            return $attributes;
        }

    }

}