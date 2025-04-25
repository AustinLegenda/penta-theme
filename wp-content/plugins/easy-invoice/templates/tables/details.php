<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<div class="ei-flex-1 ei-invoice-detail">
	<table class="right-text">

		<tbody>
		<?php foreach ($details_data as $details) {
			?>
			<tr <?php if (isset($details['is_total'])) { ?> class="table-active" <?php } ?>>
				<td <?php if (isset($details['is_total'])) { ?> class="title" <?php } ?>>
					<?php echo isset($details['is_total']) ? '<strong>' . esc_html($details['label']) . '</strong>' : esc_html($details['label']); ?>
				</td>
				<td <?php if (isset($details['is_total'])) { ?> class="title total" <?php } ?>>
					<?php echo isset($details['is_total']) ? '<strong>' . esc_html($details['value']) . '</strong>' : esc_html($details['value']); ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
