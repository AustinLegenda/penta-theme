<div class="ei-popup-page-content-wrap">
	<div class="ei-popup-page-content" id="ei-popup-page-content ei-popup-message">
		<h2><?php echo esc_html($message_title) ?> <span class="ei-close">X</span></h2>

		<div class="ei-flex ei-component-wrap">
			<div class="ei-flex-1 ei-invoice-success center-text" style="padding:15px;">
				<?php
				if ($message_text !== '') {
					echo '<strong style="font-size:100px;">' . esc_html($message_emoji) . '</strong>';
					echo '<br/>';
					echo '<p>' . esc_html($message_text) . '</p>';
				}
				?>
			</div>
		</div>


	</div>
</div>
