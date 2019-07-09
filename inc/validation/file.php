<?php

class TGHPContact_Validator_File extends TGHPContact_Validator_Abstract {

    public static function validate($field)
    {
        if(isset($_FILES["_file_{$field['id']}"])) {
            $file = $_FILES["_file_{$field['id']}"];

            if(isset($field['required']) && $field['required']) {
                if(count($file['name']) == 1 && empty($file['name'][0]) && count($file['type']) == 1 && empty($file['type'][0])) {
                    throw new Exception('File missing: ' . $field['name']);
                }
            }

            if(isset($field['attributes']) && isset($field['attributes']['accept'])) {
                $acceptedMimeTypes = array_map('trim', explode(',', $field['attributes']['accept']));
                if(!in_array($file['type'][0], $acceptedMimeTypes)) {
                    throw new Exception('File type not allowed');
                }
            }

            if(isset($field['max_file_size'])) {
                $fileSize = $file['size'][0] / 1000;

                if($fileSize > $field['max_file_size']) {
                    throw new Exception('File too large');
                }
            }
        }

        return true;
    }

}