<?php use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\DiscountFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\LineItemFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\PaymentFields; ?>
<div class="postbox">
	<div class="postbox-header"><h2>
			<?php
			echo __('Payments', 'easy-invoice') ?></h2>
	</div>
	<div class="inside">
		<div class="easy-invoice-payment-item-wrap">
			<?php
			$payment_fields = new PaymentFields();

			$payment_fields->render();


			/** @var \MatrixAddons\EasyInvoice\Repositories\InvoiceRepository $line_item */
			$invoice_repo = easy_invoice_get_invoice_options();
			?>

		</div>

		<div class="clear"></div>

	</div>
</div>
