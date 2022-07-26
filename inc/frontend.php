<?php

/**
 * Enqueues
 */
function tghpcontact_frontend_scripts()
{
    if (is_admin()) {
        return;
    }

    wp_enqueue_script('tghpcontact', TGHP_PLUGIN_URL . '/js/tghpcontact.js', ['jquery'], '1.0.0', true);
    wp_enqueue_script('tghpcontact-scroll', TGHP_PLUGIN_URL . '/js/tghpcontact-scroll.js', ['jquery'], '1.0.0', true);
}
add_filter('wp_enqueue_scripts', 'tghpcontact_frontend_scripts');

/**
 * Shortcode
 */
function tghpcontact_form_shortcode($attr)
{
    $atts = shortcode_atts(
        [
            'id' => '',
            'title' => '',
            'hide' => false,
            'hide_button_label' => 'Show Form',
            'hide_button_class' => 'button button-secondary',
            'background' => '',
        ],
        $attr,
        'tghpcontact_form'
    );

    $atts['hide'] = ($atts['hide'] == 1);

    $id = $atts['id'];
    $title = $atts['title'];
    $hide = $atts['hide'] && !(isset($_GET['rwmb-form-submitted']) && $_GET['rwmb-form-submitted'] === $id);

    ob_start();

    if (!empty($atts['background'])): ?>
        <style type="text/css">
          #form_<?php echo $id ?> .rwmb-field label {
            background: transparent;
          }

          #form_<?php echo $id ?> .rwmb-field select:focus,
          #form_<?php echo $id ?> .rwmb-field select.has-value,
          #form_<?php echo $id ?> .rwmb-field input:focus,
          #form_<?php echo $id ?> .rwmb-field input.has-value {
            background: <?php echo $atts['background'] ?>
          }

          #form_<?php echo $id ?> .rwmb-field.has-value select,
          #form_<?php echo $id ?> .rwmb-field.has-value input {
            background: <?php echo $atts['background'] ?>
          }
        </style>
    <?php endif;

    if (!empty($id)) {
    if ($hide):
        $hideButtonClass = $atts['hide_button_class'];
        $hideButtonLabel = $atts['hide_button_label'];
        $containerId = sprintf('container-%s', $id);
        $buttonId = sprintf('show-button-%s', $id);
        ?>
        <p class="tghpcontact-form-show">
            <button class="<?php echo $hideButtonClass ?>" id="<?php echo $buttonId ?>"
                    data-target="#<?php echo $containerId ?>"><?php echo $hideButtonLabel ?></button>
        </p>
        <style type="text/css">
          .tghpcontact-form-hidden {
            display: none;
          }
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

            if ($title): ?>
                <h2><?php echo $title ?></h2>
            <?php endif;

            // Output form
            tghpcontact_form($id);

            if ($hide): ?>
        </div>
    <?php endif;
    }

    return ob_get_clean();
}

add_shortcode('tghpcontact_form', 'tghpcontact_form_shortcode');

function tghpcontact_rwmb_shortcode_filter($output, $tag)
{
    if ($tag !== 'mb_frontend_form') {
        return $output;
    }
    return preg_replace('/<form /', '<form autocomplete="off" ', $output);
}
add_filter('do_shortcode_tag', 'tghpcontact_rwmb_shortcode_filter', 10, 2);

/**
 * @param RW_Meta_Box $metaBox
 */
function tghpcontact_rwmb_before_wrapper($metaBox)
{
    if (is_admin()) {
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
function tghpcontact_rwmb_after_wrapper($metaBox)
{
    if (is_admin()) {
        return;
    }

    echo '</div>';
}
add_filter('rwmb_after', 'tghpcontact_rwmb_after_wrapper');
