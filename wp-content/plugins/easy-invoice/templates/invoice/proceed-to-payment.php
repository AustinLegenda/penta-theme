<div class="ei-flex ei-component-wrap ei-no-print">
	<div class="ei-flex-1 ei-empty-div">
	</div>
	<div class="ei-flex-1 ei-proceed-to-payment-button-wrap right-text">
		<?php
		$payment_gateways = easy_invoice_get_active_payment_gateways();
		if (count($payment_gateways) > 0) {
			?>
			<button class="ei-button ei-proceed-to-payment-button"
			><?php echo esc_html(easy_invoice_get_text('proceed_to_payment_button')); ?></button>
			<?php
		} else {
			?>
			<a class="ei-button" target="_blank"
			   href="<?php echo esc_url(easy_invoice_proceed_to_payment_button_link()) ?>"><?php echo esc_html(easy_invoice_get_text('proceed_to_payment_button')); ?></a>
		<?php } ?>
	</div>
</div>
