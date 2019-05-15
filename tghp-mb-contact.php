<?php
/*
Plugin Name: TGHP Metabox Contact
Description: Utilise MB Frontend Submission for contact forms
Author: TGHP
Version: 1.0.0
Network: False
*/

const TGHP_CONTACT_META_PREFIX = '_tghpcontact_';

include_once 'inc/cpt.php';
include_once 'inc/cmb.php';
include_once 'inc/settings.php';
include_once 'inc/posts.php';
include_once 'inc/email.php';
include_once 'inc/frontend.php';
include_once 'inc/admin.php';

function tghpcontact_form()
{
    echo do_shortcode('[mb_frontend_form id="contact_submission"]');
}