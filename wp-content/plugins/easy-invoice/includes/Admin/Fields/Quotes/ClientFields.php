<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

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
				'allowed_html' => array(
					'a' => array(
						'href' => array(),
						'target' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
					'hr' => array(),
					'p' => array(),
					'h1' => array(),
					'h2' => array(),
					'h3' => array(),
					'h4' => array(),
					'h5' => array(),
					'h6' => array(),
				)
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
