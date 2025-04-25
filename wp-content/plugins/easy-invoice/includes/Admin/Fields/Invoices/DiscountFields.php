<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class DiscountFields extends Base
{
	public function get_settings()
	{
		return [
			'discount' => [
				'type' => 'text',
				'custom_attributes' => array(
					'placeholder' => __('Discount', 'easy-invoice'),
				)

			],
			'discount_type' => [
				'title' => __('Discount Type', 'easy-invoice'),
				'type' => 'select',
				'options' => array(
					'fixed' => __('Fixed', 'easy-invoice'),
					'percentage' => __('Percentage', 'easy-invoice')
				),
			],
			'discount_calculation_method' => [
				'title' => __('Calculation Method', 'easy-invoice'),
				'type' => 'select',
				'options' => array(
					'before_tax' => __('Before Tax', 'easy-invoice'),
					'after_tax' => __('After Tax', 'easy-invoice')
				),
			],

		];
	}

	public function render()
	{
		$this->output();
	}


	public function nonce_id()
	{
		return 'easy_invoice_discount_fields';
	}
}
