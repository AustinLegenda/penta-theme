<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class ClientFields extends Base
{
public function get_settings()
	{
		return [
			'business_name' => [
				'type' => 'text',
				'title' => __('Business Name', 'easy-invoice'),
			],
			'business_address' => [
				'type' => 'text',
				'title' => __('Business Address', 'easy-invoice'),
			],
			'client_url' => [
				'type' => 'text',
				'title' => __('Website URL', 'easy-invoice'),
			],
			'client_name' => [
				'type' => 'text',
				'title' => __('Client Name', 'easy-invoice'),
			],
			'client_email' => [
				'type' => 'email',
				'title' => __('Client Email', 'easy-invoice'),
			],
			'client_number' => [
				'type' => 'text',
				'title' => __('Client Phone', 'easy-invoice'),
			],
			'additional_info' => [
				'type' => 'textarea',
				'title' => __('Additional Info', 'easy-invoice'),
			],

		];
	}

	public function render()
	{
		$this->output();
	}


	public function nonce_id()
	{
		return 'easy_invoice_client_fields';
	}
}
