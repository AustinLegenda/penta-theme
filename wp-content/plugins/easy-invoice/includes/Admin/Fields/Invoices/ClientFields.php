<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class ClientFields extends Base
{
	public function get_settings()
	{
		return [
			'client_email' => [
				'type' => 'email',
				'title' => __('Client Email', 'easy-invoice'),
			],
			'client_name' => [
				'type' => 'text',
				'title' => __('Client Name', 'easy-invoice'),
			],
			'client_url' => [
				'type' => 'text',
				'title' => __('Website URL', 'easy-invoice'),
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
