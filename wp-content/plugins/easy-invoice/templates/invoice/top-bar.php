<div class="easy-invoice-top-bar ei-no-print">
	<div class="ei-container">
		<div class="ei-row">
			<div class="ei-flex">
				<div class="ei-flex-1 empty-div">
				</div>
				<div class="ei-flex-1 ei-buttons right-text">
					<button type="button"
							class="ei-print-button"
							onclick="window.print()"><?php echo esc_html(easy_invoice_get_text('print')) ?></button>
					<button type="button"
							data-url="<?php echo esc_url(easy_invoice_get_download_as_pdf_url(get_the_ID())); ?>"
							class="ei-download-pdf-button"><?php echo esc_html(easy_invoice_get_text('download_as_pdf')) ?></button>
					<?php if (current_user_can('manage_options')) { ?>
						<button type="button"
								class="ei-send-email-button"><?php echo esc_html(easy_invoice_get_text('send_email')) ?></button>
					<?php } ?>
				</div>
			</div>
		</div>

	</div>
</div>
