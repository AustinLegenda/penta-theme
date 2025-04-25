<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class QuoteDescriptionFields extends Base
{
	public function get_settings()
	{
		return [
			'description' => [
				'type' => 'editor',
			],

		];
	}

	public function render()
	{
		$this->output();
	}


	public function nonce_id()
	{
		return 'easy_invoice_description_fields';
	}

}
