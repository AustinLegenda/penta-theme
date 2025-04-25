<?php

use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\TermsConditionsFields; ?>
<div class="postbox">
	<div class="postbox-header"><h2>
			<?php
			echo __('Terms & Conditions', 'easy-invoice') ?></h2>
	</div>
	<div class="inside">
		<div class="easy-invoice-terms-conditions-wrap">
			<?php
			$terms_conditions_fields = new TermsConditionsFields();

			$terms_conditions_fields->render();
			?>

		</div>
		<div class="clear"></div>

	</div>
</div>
