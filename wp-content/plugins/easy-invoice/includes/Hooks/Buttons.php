<?php

namespace MatrixAddons\EasyInvoice\Hooks;

use MatrixAddons\EasyInvoice\Constant;

class Buttons
{
	public function __construct()
	{

		add_action('easy_invoice_content', array($this, 'proceed_to_payment_button'), 31);
		add_action('easy_invoice_content', array($this, 'proceed_to_payment_button'), 44);
	}

	public function proceed_to_payment_button()
	{
		if (!easy_invoice_enable_proceed_to_payment() || get_post_type(get_the_ID()) !== Constant::INVOICE_POST_TYPE) {
			return;
		}
		easy_invoice_load_template('invoice.proceed-to-payment');

	}

}
