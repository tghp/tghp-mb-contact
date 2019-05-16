<?php
/**
 * The template file for the confirmation message.
 *
 * @package    Meta Box
 * @subpackage MB Frontend Submission
 */

$error = filter_input(INPUT_GET, 'rwmb-form-error');
?>
<?php if($error): ?>
<div class="rwmb-error tghpcontact-error"><?php echo esc_html( $error ); ?></div>
<?php else: ?>
<div class="rwmb-confirmation tghpcontact-confirmaton"><?php echo esc_html( $data->confirmation ); ?></div>
<?php endif ?>