<div class="ei-popup-page-content-wrap">
	<div class="ei-popup-page-content" id="ei-popup-page-content">
		<h2><?php echo esc_html(easy_invoice_get_text('decline_quote')); ?><span
					class="ei-close" data-action="hide">X</span></h2>
		<div class="ei-flex ei-component-wrap">
			<div class="ei-flex flex-column center-text">
				<form method="post" class="easy-invoice-decline-quote-form"
					  data-action="<?php echo esc_url(get_permalink($quote_id)) ?>">
					<label for="decline_reason"
						   class="left-text"><?php echo esc_html(easy_invoice_get_text('reason_for_decline_quote')); ?>
						<?php echo $is_required_reason ? '*' : ''; ?>
					</label>
					<br/>
					<textarea
							name="decline_reason" <?php echo $is_required_reason ? 'required="required"' : ""; ?>></textarea>
					<br/>
					<br/>
					<button type="submit"
							class="error xl"><?php echo esc_html(easy_invoice_get_text('decline_quote')); ?></button>
					<input type="hidden" name="nonce"
						   value="<?php echo esc_attr(wp_create_nonce('decline_quote')) ?>"/>
					<input type="hidden" name="quote_id" value="<?php echo esc_attr($quote_id) ?>"/>
					<input type="hidden" name="action" value="decline_quote"/>
				</form>
			</div>

		</div>
	</div>
</div>
