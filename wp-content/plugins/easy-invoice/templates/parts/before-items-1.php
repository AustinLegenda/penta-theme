<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $ei_invoice;

// Ensure $ei_invoice is not null before calling methods
if (!isset($ei_invoice) || !is_object($ei_invoice)) {
    return; // Stop execution if $ei_invoice is not available
}
?>

<div class="lei-before-items-one | lei-section">
	<div class="ei-title">
		<?php if ($ei_invoice->get_deposit_amount() > 0) { ?>
			<h3 class="ei-highlight-text"> <?php echo esc_html('Advance Retainer ' . $title); ?> </h3>
		<?php } else { ?>
			<h3 class="ei-main-title"> <?php echo esc_html($title); ?> </h3>
		<?php } ?>
	</div>
	<div class="ei-from-address">
		<?php easy_invoice_get_from_address(); ?>
	</div>
</div>