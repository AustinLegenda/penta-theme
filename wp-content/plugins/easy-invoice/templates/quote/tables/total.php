<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $ei_quote;

?>
<div class="ei-invoice-totals">
	<!-- <div class="ei-flex-1 ei-invoice-totals"> -->
	<table class="right-text totals-table">
		<tbody>
			<tr>
				<td colspan="2" style="padding:0" class="single-solid"></td>
			</tr>
			<tr>
				<td class="rate"><?php echo esc_html(easy_invoice_get_text('sub_total')) ?></td>
				<td class="total"><?php echo esc_html(easy_invoice_get_price($ei_quote->get_sub_total(), '', $ei_quote->get_id())); ?></td>
			</tr>
			<tr class="row-tax">
				<td class="rate"><?php echo esc_html(easy_invoice_get_text('tax')) ?></td>
				<td class="total"><?php echo esc_html(easy_invoice_get_price($ei_quote->get_tax_amount(), '', $ei_quote->get_id())); ?></td>
			</tr>
			<?php if ($ei_quote->get_discount_amount() > 0) { ?>
				<tr class="row-discount" style="color:#ff0000;">
					<td class="discount"><?php echo esc_html(easy_invoice_get_text('discount')) ?></td>
					<td class="total">
						-<?php echo esc_html(easy_invoice_get_price($ei_quote->get_discount_amount(), '', $ei_quote->get_id())); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="2" style="padding:1px;" class="double-solid"></td>
			</tr>
			<tr class="table-active row-total">
				<td class="title rate"><strong><?php echo esc_html(easy_invoice_get_text('total_due')) ?></strong></td>
				<td class="title total">
					<strong><?php echo esc_html(easy_invoice_get_price($ei_quote->get_due_amount(), '', $ei_quote->get_id())); ?></strong>
				</td>
			
			</tr>
		</tbody>
	</table>
</div>