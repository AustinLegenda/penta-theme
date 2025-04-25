<div class="ei-popup-page-content-wrap">
	<div class="ei-popup-page-content" id="ei-popup-page-content">
		<h2><?php echo esc_html(easy_invoice_get_text('accept_quote')); ?><span
					class="ei-close" data-action="hide">X</span></h2>
		<div class="ei-flex ei-component-wrap">
			<div class="ei-flex flex-column center-text">
				<p><?php echo esc_html(easy_invoice_get_text('quote_number')); ?>:
					<strong><?php echo esc_html($quote_number) ?></strong></p>
				<br/>
				<p><?php echo esc_html(easy_invoice_get_text('quote_amount')); ?>:
					<strong><?php echo esc_html(easy_invoice_get_price($quote_amount)) ?></strong></p>

				<form method="post" class="easy-invoice-accept-quote-form"
					  data-action="<?php echo esc_url(get_permalink($quote_id)) ?>">
					<br/>
					<button type="submit"
							class="success xl"><?php echo esc_html(easy_invoice_get_text('accept_quote')); ?></button>
					<input type="hidden" name="nonce"
						   value="<?php echo esc_attr(wp_create_nonce('accept_quote')) ?>"/>
					<input type="hidden" name="quote_id" value="<?php echo esc_attr($quote_id) ?>"/>
					<input type="hidden" name="action" value="accept_quote"/>
				</form>
			</div>

		</div>
		<em style="font-size:13px">
			<?php echo esc_html(easy_invoice_accept_quote_text()); ?>
		</em>
	</div>
</div>
