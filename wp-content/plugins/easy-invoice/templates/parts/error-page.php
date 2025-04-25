<div class="ei-popup-page-content-wrap">
	<div class="ei-popup-page-content" id="ei-popup-page-content ei-popup-error">
		<h2><?php echo esc_html__('Something went wrong', 'easy-invoice'); ?> <span class="ei-close">X</span></h2>

		<div class="ei-flex ei-component-wrap">
			<div class="ei-flex-1 ei-invoice-error" style="padding:15px;">
				<?php
				if ($error_text !== '') {
					echo '<p><strong>Error:</strong> ' . esc_html($error_text) . '</p>';
				}
				?>
			</div>
		</div>


	</div>
</div>
