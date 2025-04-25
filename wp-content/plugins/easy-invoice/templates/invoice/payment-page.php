<div class="ei-popup-page-content-wrap">
	<div class="ei-popup-page-content" id="ei-popup-page-content">
		<h2><?php echo esc_html(easy_invoice_get_text('payment_gateway_information')); ?> <span
					class="ei-close">X</span></h2>
		<div class="ei-flex ei-component-wrap">
			<div class="ei-flex-1 ei-invoice-to-address">
				<?php easy_invoice_get_to_address(); ?>
			</div>
			<div class="ei-flex-1 ei-invoice-detail">
				<?php
				easy_invoice_load_template('tables.details', array('details_data' => easy_invoice_get_invoice_details_data()));
				?>
			</div>
			
		</div>

		<form method="post" class="easy-invoice-checkout-form"
			  data-action="<?php echo esc_url(get_permalink($invoice_id)) ?>">
			<div id="easy-invoice-payment-gateway-wrap" class="easy-invoice-payment-gateway-wrap">
				<?php easy_invoice_payment_gateway_fields(); ?>
				<div class="right-text">

				</div>
				<div class="ei-flex ei-component-wrap">
					<div class="ei-flex-3 <?php echo $error !== '' ? 'ei-invoice-error' : ''; ?>">
						<?php
						if ($error !== '') {
							echo '<p><strong>Error:</strong> ' . esc_html($error) . '</p>';
						}
						?>
					</div>

					
					<div class="ei-flex-1 ei-invoice-pay-now-wrap right-text">
						<button type="submit" class="ei-button" name="easy_invoice_proceed_to_payment"
								id="easy_invoice_proceed_to_payment"
								value="<?php echo esc_attr(easy_invoice_get_text('pay_now_button')); ?>"
								data-value="<?php echo esc_attr(easy_invoice_get_text('pay_now_button')); ?>"><?php echo esc_html(easy_invoice_get_text('pay_now_button')); ?>
						</button>
					</div>
				</div>
			</div>
			<input type="hidden" name="ei_nonce" value="<?php echo esc_attr(wp_create_nonce('ei_pay_now_nonce')) ?>"/>
			<input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice_id) ?>"/>
			<input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice_id) ?>"/>
			<input type="hidden" name="action" value="ei_pay_now_action"/>
		</form>

	</div>
</div>
