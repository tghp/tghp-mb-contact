<?php

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