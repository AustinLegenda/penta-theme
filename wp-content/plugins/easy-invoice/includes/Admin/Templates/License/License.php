<h1></h1>
<h2><?php echo __('License Manager', 'easy-invoice') ?></h2>
<?php if ($message != '') { ?>
	<div id="message" class="updated notice notice-success "><p><?php echo esc_html($message); ?></p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
<?php } ?>
<form method="post" class="easy-invoice-license-manager-form">
	<table class="easy-invoice-license-manager-table">
		<thead>
		<tr>
			<th><?php echo __('Addon Name', 'easy-invoice') ?></th>
			<th><?php echo __('License', 'easy-invoice') ?></th>
			<th><?php echo __('Expire Date', 'easy-invoice') ?></th>
			<th><?php echo __('Status', 'easy-invoice') ?></th>
			<th><?php echo __('Message', 'easy-invoice') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($addons as $addon_slug => $addon) {

			$addon_license = $license_details[$addon_slug] ?? array();

			$server_response = $addon_license['server_response'] ?? array();

			$server_response = is_object($server_response) ? (array)$server_response : $server_response;

			$expired_date = isset($server_response['expires']) ? sanitize_text_field($server_response['expires']) : '';

			$display_license = isset($addon_license['license_key']) ? sanitize_text_field($addon_license['license_key']) : '';

			$display_license = '' != $display_license ? '**********' . substr($display_license, 5, 10) : '';

			$status = isset($addon_license['status']) ? sanitize_text_field($addon_license['status']) : '';

			$status = $status == '' ? 'inactive' : $status;

			$button_label = __('Deactivate', 'easy-invoice');

			$constant = strtoupper(str_replace('-', '_', $addon_slug)) . '_VERSION';

			$version_text = defined($constant) ? constant($constant) : null;

			?>
			<tr class="ei-addon-row" data-addon-slug="<?php echo esc_attr($addon_slug) ?>">
				<td>
					<span class="product-name"><?php echo esc_html($addon['label']) ?><?php echo !is_null($version_text) ? ' - <span class="version">' . esc_html($version_text) . '</span>' : ''; ?></span>
				</td>
				<td class="license-column">
					<div class="license-column-inner"> <?php
						if ($display_license === '') {
							?>
							<input class="ei-license-field" type="text" name="<?php echo esc_attr($addon_slug) ?>_license"
								   placeholder="<?php echo __('Please enter your license key here', 'easy-invoice') ?>"
							/>
							<?php
						} else {
							echo '<span class="display-text">' . esc_html($display_license) . '</span>';

							echo '<span class="modify-license button button-secondary">' . __('Modify License', 'easy-invoice') . '</span>';
						}
						if ($status === 'active') {
							?>
							<button style="float:right;" type="button"
									class="button button-primary deactivate-license"><?php echo esc_html($button_label) ?></button>
						<?php } ?>
					</div>
				</td>
				<td>

					<?php echo isset($expired_date) ? esc_html($expired_date) : '' ?>

				</td>
				<td>
					<span class="status <?php echo esc_attr(strtolower($status)) ?>"><?php echo esc_html($status) ?></span>
				</td>
				<td style="max-width:250px;"><?php echo isset($addon_license['notice']) ? wp_kses($addon_license['notice'], array(
							'a' => array('href' => array(), 'target' => array()),
							'strong' => array()

					)) : '' ?></td>


			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="5">
				<button class="button-primary" type="submit"
						name="easy_invoice_license_save_button"><?php echo __('Update', 'easy-invoice'); ?></button>
			</td>
		</tr>
		</tfoot>
	</table>
	<?php
	wp_nonce_field('easy_invoice_license_save_nonce');
	?>
</form>
