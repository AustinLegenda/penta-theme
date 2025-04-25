<?php use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\InvoiceDescriptionFields; ?>
<div class="postbox">
	<div class="postbox-header"><h2>
			<?php
			echo __('Invoice Description', 'easy-invoice') ?></h2>
	</div>
	<div class="inside">
		<?php
		$invoice_description = new InvoiceDescriptionFields();

		$invoice_description->render();
		?>
	</div>
</div>
