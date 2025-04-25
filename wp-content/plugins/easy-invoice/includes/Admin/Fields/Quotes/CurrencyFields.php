<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class CurrencyFields extends Base
{
	public function get_settings()
	{
		return [
			'currency' => [
				'type' => 'select',
				'title' => __('Currency', 'easy-invoice'),
				'default' => easy_invoice_get_currency(),
				'options' => easy_invoice_get_all_currency_with_symbol()
			],
			'currency_symbol' => [
				'type' => 'text',
				'title' => __('Currency Symbol', 'easy-invoice'),
				'default' => easy_invoice_get_currency_symbol(),
			],

		];
	}

	public function render()
	{
		$this->output();
	}


	public function nonce_id()
	{
		return 'easy_invoice_currency_fields';
	}
}
