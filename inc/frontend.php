<?php

/**
 * Shortcode
 */
function tghpcontact_form_shortcode($attr) {
    $atts = shortcode_atts(
        array(
            'id' => '',
            'title' => '',
            'hide' => false,
            'hide_button_label' => 'Show Form',
            'hide_button_class' => 'button button-secondary',
            'background' => ''
        ),
        $attr,
        'tghpcontact_form'
    );

    $atts['hide'] = ($atts['hide'] == 1);

    $id = $atts['id'];
    $title = $atts['title'];
    $hide = $atts['hide'] && !(isset($_GET['rwmb-form-submitted']) && $_GET['rwmb-form-submitted'] === $id);

    ob_start();

    if(!empty($atts['background'])): ?>
    <style type="text/css">
        #form_<?php echo $id ?> .rwmb-field label { background: transparent; }
        #form_<?php echo $id ?> .rwmb-field select:focus,
        #form_<?php echo $id ?> .rwmb-field select.has-value,
        #form_<?php echo $id ?> .rwmb-field input:focus,
        #form_<?php echo $id ?> .rwmb-field input.has-value { background: <?php echo $atts['background'] ?> }
        #form_<?php echo $id ?> .rwmb-field.has-value select,
        #form_<?php echo $id ?> .rwmb-field.has-value input { background: <?php echo $atts['background'] ?> }
    </style>
    <?php endif;

    if(!empty($id)) {
        if($hide):
            $hideButtonClass = $atts['hide_button_class'];
            $hideButtonLabel = $atts['hide_button_label'];
            $containerId = sprintf('container-%s', $id);
            $buttonId = sprintf('show-button-%s', $id);
            ?>
            <p class="tghpcontact-form-show">
                <button class="<?php echo $hideButtonClass ?>" id="<?php echo $buttonId ?>" data-target="#<?php echo $containerId ?>"><?php echo $hideButtonLabel ?></button>
            </p>
            <style type="text/css">
                .tghpcontact-form-hidden { display: none; }
            </style>
            <script type="text/javascript">
                (function () {
                    document.getElementById('<?php echo $buttonId ?>').addEventListener('click', function () {
                        this.parentNode.style.display = 'none';
                        document.getElementById('<?php echo $containerId ?>').style.display = 'block';
                    });
                })();
            </script>
            <div class="tghpcontact-form-hidden" id="<?php echo $containerId ?>">
        <?php endif;

        if($title): ?>
            <h2><?php echo $title ?></h2>
        <?php endif;

        // Output form
        tghpcontact_form($id);

        if($hide): ?>
            </div>
        <?php endif;
    }

    return ob_get_clean();
}
add_shortcode('tghpcontact_form', 'tghpcontact_form_shortcode');

/**
 * @param RW_Meta_Box $metaBox
 */
function tghpcontact_rwmb_before_wrapper($metaBox) {
    if(is_admin()) {
        return;
    }

    printf(
        '<div class="rwmb-form-fields%s" id="form_%s">',
        $metaBox->class ? " {$metaBox->class}" : '',
        $metaBox->id
    );
}
add_filter('rwmb_before', 'tghpcontact_rwmb_before_wrapper');

/**
 * @param $metaBox
 */
function tghpcontact_rwmb_after_wrapper($metaBox) {
    if(is_admin()) {
        return;
    }

    echo '</div>';
}
add_filter('rwmb_after', 'tghpcontact_rwmb_after_wrapper');

function tghpcontact_scroll_to_form() {
    ?>
    <script type="text/javascript">
        (function () {
            var checkAndScrollToForm = function () {
                if(!window.location.search) {
                    return
                }

                var submitted = window.location.search.match(/rwmb-form-submitted=([^&]*)/);

                if(submitted) {
                    var formFlag = document.querySelector('input[name="rwmb_form_config[id]"][value="' + submitted[1] + '"]');

                    if(formFlag) {
                        var form = formFlag.parentElement.parentElement;

                        setTimeout(function () {
                            window.scrollTo(0, form.offsetTop);
                        }, 0);
                    }
                }
            };

            if (document.attachEvent ? document.readyState === 'complete' : document.readyState !== 'loading'){
                checkAndScrollToForm();
            } else {
                document.addEventListener('DOMContentLoaded', checkAndScrollToForm);
            }
        })();
    </script>
    <?php
}
add_action('wp_footer', 'tghpcontact_scroll_to_form');