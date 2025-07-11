<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class QuoteFields extends Base
{
	public function __construct()
	{
		add_action('easy_invoice_meta_fields_before_save', array($this, 'before_meta_save'), 10, 3);
		add_action('easy_invoice_meta_fields_after_save', array($this, 'after_meta_save'), 10, 3);
	}

	public function get_settings()
	{
		return [
			'quote_status' => [
				'type' => 'select',
				'title' => __('Estimate Status', 'easy-invoice'),
				'options' => easy_invoice_get_quote_statuses(),

			],
			'quote_number' => [
				'type' => 'text',
				'title' => __('Estimate Number', 'easy-invoice'),
				'default' => easy_invoice_get_quote_number(),
			],
			'job_number' => [
				'type' => 'text',
				'title' => __('Job Number', 'easy-invoice'),
			],
			'created_date' => [
				'type' => 'text',
				'title' => __('Created Date', 'easy-invoice'),
				'class' => 'easy-invoice-datepicker'
			],
			'valid_until' => [
				'type' => 'text',
				'title' => __('Valid Until Date', 'easy-invoice'),
				'class' => 'easy-invoice-datepicker'
			],

		];
	}

	public function render()
	{
		$this->output();
	}


	public function nonce_id()
	{
		return 'easy_invoice_fields';
	}

	public function before_meta_save($invoice_id, $valid_data, $nonce_id)
	{
		if ($nonce_id !== $this->nonce_id()) {
			return;
		}

		if (get_post_type($invoice_id) !== 'easy-invoice') {

			return;
		}
		$updated_invoice_status = $valid_data['invoice_status'] ? sanitize_text_field($valid_data['invoice_status']) : '';

		if ($updated_invoice_status == '') {
			return;
		}

		$invoice = new InvoiceRepository($invoice_id);

		delete_post_meta($invoice_id, 'easy_invoice_status_change_for_invoice');

		if ($invoice->get_invoice_status() !== $updated_invoice_status) {

			add_post_meta($invoice_id, 'easy_invoice_status_change_for_invoice', 'yes');
		}
	}

	public function after_meta_save($invoice_id, $valid_data, $nonce_id)
	{
		if ($nonce_id !== $this->nonce_id()) {
			return;
		}

		if (get_post_type($invoice_id) !== 'easy-invoice') {

			return;
		}
		$updated_invoice_status = $valid_data['invoice_status'] ? sanitize_text_field($valid_data['invoice_status']) : '';

		if ($updated_invoice_status == '') {
			return;
		}
		$is_status_changed = get_post_meta($invoice_id, 'easy_invoice_status_change_for_invoice', true) === 'yes';

		delete_post_meta($invoice_id, 'easy_invoice_status_change_for_invoice');

		if (!$is_status_changed) {
			return;
		}

		easy_invoice_update_invoice_status($invoice_id, $updated_invoice_status);

	}
}
