<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $ei_invoice;
?>
<div class="ei-invoice-totals">
	<table class="right-text totals-table">
		<tbody>
			<tr>
				<td colspan="2" style="padding:0" class="single-solid"></td>
			</tr>
			<tr>
				<td class="rate"><?php echo esc_html(easy_invoice_get_text('sub_total')) ?></td>
				<td class="total"><?php echo esc_html(easy_invoice_get_price($ei_invoice->get_sub_total(), '', $ei_invoice->get_id())); ?></td>
			</tr>
			<tr class="row-tax">
				<td class="rate"><?php echo esc_html(easy_invoice_get_text('tax')) ?></td>
				<td class="total"><?php echo esc_html(easy_invoice_get_price($ei_invoice->get_tax_amount(), '', $ei_invoice->get_id())); ?></td>
			</tr>
			<?php if ($ei_invoice->get_discount_amount() > 0) { ?>
				<tr class="row-discount" style="color:#ff0000;">
					<td class="discount"><?php echo esc_html(easy_invoice_get_text('discount')) ?></td>
					<td class="total">
						-<?php echo esc_html(easy_invoice_get_price($ei_invoice->get_discount_amount(), '', $ei_invoice->get_id())); ?></td>
				</tr>
			<?php } ?>

			<?php if ($ei_invoice->get_total_paid() > 0) { ?>
				<tr class="row-paid" style="color:#ff0000;">
					<td class="rate"><?php echo esc_html(easy_invoice_get_text('paid')) ?></td>
					<td class="total">
						-<?php echo esc_html(easy_invoice_get_price($ei_invoice->get_total_paid(), '', $ei_invoice->get_id())); ?></td>
				</tr>
			<?php } ?>

			<?php if ($ei_invoice->get_deposit_amount() > 0) { ?>
				<tr class="row-deposit" style="color:#808080;">
					<td class="rate"><?php echo esc_html(easy_invoice_get_text('deposit_required')) ?> </td>
					<td class="total"><?php echo round(($ei_invoice->get_deposit_amount() / $ei_invoice->get_sub_total()) * 100, 2); ?>%</td>
				</tr>
			<?php } ?>

			<tr>
				<td colspan="2" style="padding:1px;" class="double-solid"></td>
			</tr>
			<tr class="table-active row-total">
				<td class="title rate"><strong><?php echo esc_html(easy_invoice_get_text('total_due')) ?></strong></td>
				<td class="title total">
					<strong><?php echo esc_html(easy_invoice_get_price($ei_invoice->get_due_amount(), '', $ei_invoice->get_id())); ?></strong>
				</td>
			</tr>
		</tbody>
	</table>
</div>
