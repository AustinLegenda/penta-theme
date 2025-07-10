<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $ei_invoice, $ei_quote;

$is_quote = isset($ei_quote);
$is_invoice = isset($ei_invoice);

// Quote output
if ($is_quote) : ?>
	<div class="lei-before-items-one | lei-section">
		<div class="ei-title">
			<h3><?php echo esc_html($title); ?></h3>
		</div>
		<div class="ei-from-address">
			<?php easy_invoice_get_from_address(); ?>
		</div>
	</div>

<?php
// Invoice output
elseif ($is_invoice && is_object($ei_invoice)) :
	$deposit = $ei_invoice->get_deposit_amount();
	?>
	<div class="lei-before-items-one | lei-section">
		<div class="ei-title">
			<?php if ($deposit > 0) : ?>
				<h3 class="ei-highlight-text"><?php echo esc_html('Advance Retainer ' . $title); ?></h3>
			<?php else : ?>
				<h3 class="ei-main-title"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
		</div>
		<div class="ei-from-address">
			<?php easy_invoice_get_from_address(); ?>
		</div>
	</div>
<?php endif; ?>
