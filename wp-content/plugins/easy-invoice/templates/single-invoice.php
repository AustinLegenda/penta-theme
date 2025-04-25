<?php
if (!defined('ABSPATH')) {
	exit;
}
do_action('easy_invoice_before_invoice_display'); ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<title><?php wp_title() ?></title>
	<meta charset="<?php bloginfo('charset'); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">

	<?php do_action('easy_invoice_head'); ?>
</head>

<?php do_action('easy_invoice_before_body'); ?>

<body>

<?php do_action('easy_invoice_before_container'); ?>

<div class="ei-container">
	<div class="ei-row">
		<div class="<?php echo esc_attr(apply_filters('easy_invoice_wrapper_class_for_invoice', 'easy-invoice-wrap')); ?>"
			 id="easy-invoice-wrap">
			<?php do_action('easy_invoice_content'); ?>
		</div>

	</div>
</div> <!-- END easy-invoice-wrap -->

<?php do_action('easy_invoice_after_container'); ?>

<?php do_action('easy_invoice_footer'); ?>

</body>

<?php do_action('easy_invoice_after_body'); ?>

</html>
