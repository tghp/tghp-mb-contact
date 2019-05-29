<?php

function tghpcontact_rwmb_frontend_redirect_form($url, $config) {
    $metabox = tghpcontact_get_contact_metabox($config['id']);

    if(strpos($url, 'rwmb-form-error') === false && strpos($url, 'rwmb-form-submitted') !== false &&
            $metabox && $metabox->redirect) {
        $url = $metabox->redirect;
    }

    return $url;
}
add_filter('rwmb_frontend_redirect', 'tghpcontact_rwmb_frontend_redirect_form', 10, 2);