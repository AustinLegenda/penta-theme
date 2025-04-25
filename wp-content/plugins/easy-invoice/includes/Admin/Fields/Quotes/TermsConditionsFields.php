<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class TermsConditionsFields extends Base
{
	public function get_settings()
	{
		return [
			'terms_and_conditions' => [
				'type' => 'textarea',
				'title' => __('Terms & Conditions', 'easy-invoice'),
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
				),
				'default' => get_option('easy_invoice_quote_terms_conditions', 'This estimate has a fixed price. Upon acceptance, we kindly ask for a 25% deposit prior to initiating the work.')
			],

		];
	}

	public function render()
	{
		$this->output();
	}

	public function nonce_id()
	{
		return 'easy_invoice_terms_conditions_fields';
	}

}
