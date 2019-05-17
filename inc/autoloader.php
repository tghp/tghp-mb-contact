<?php

function tghpcontact_autoload($class)
{
    $classParts = explode('_', $class);

    if(count($classParts) > 1) {
        $paths = [
            'TGHPContact_Validator' => 'validation'
        ];

        foreach ($paths as $_prefix => $_dir) {
            if (strpos($class, $_prefix) === 0) {
                $file = TGHP_PLUGIN_DIR . 'inc/' . $_dir . '/' . strtolower($classParts[count($classParts) - 1]) . ".php";

                if(file_exists($file)) {
                    require_once $file;
                }
            }
        }
    }
}
spl_autoload_register('tghpcontact_autoload');