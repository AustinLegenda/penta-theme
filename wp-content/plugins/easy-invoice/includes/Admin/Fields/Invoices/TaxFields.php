<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;
use MatrixAddons\EasyInvoice\Admin\Fields\Base;
class TaxFields extends Base
{
	public function get_settings()
	{
		return [
			'tax_type' => [
				'type' => 'select',
				'title' => __('Prices entered with tax', 'easy-invoice'),
				'options' => array(
					'exclusive' => __('No, I will enter prices exclusive of tax (default)', 'easy-invoice'),
					'inclusive' => __('Yes, I will enter prices inclusive of tax', 'easy-invoice'),
				),
				'default' => get_option('easy_invoice_tax_type', 'exclusive')
			],
			'tax_rate' => [
				'type' => 'number',
				'title' => __('Tax Rate(%)', 'easy-invoice'),
				'default' => get_option('easy_invoice_tax_percentage', 10)
			],

		];
	}

	public function render()
	{
		$this->output();
	}


	public function nonce_id()
	{
		return 'easy_invoice_tax_fields';
	}
}
