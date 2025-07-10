<?php
//lei
namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;
use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class PaymentFields extends Base
{
	protected $field_prefix = 'easy_invoice_payment_items';

	public function get_settings()
	{
		return [
		'payment_item_1_start' => [
				'type' => 'wrap',
				'class' => 'easy-invoice-payment-item-1-wrap',
			],
			"{$this->field_prefix}[payment_date]" => [
				'type'  => 'text',
				'title' => __('Payment Date', 'easy-invoice'),
				'class' => 'easy-invoice-datepicker',
			],
			"{$this->field_prefix}[paid_amount]" => [
				'type'    => 'text',
				'title'   => __('Amount', 'easy-invoice'),
				'class'   => 'easy-invoice-payment-paid-amount',
				'default' => '',
			],
			"{$this->field_prefix}[payment_gateway]" => [
				'type'    => 'select',
				'title'   => __('Payment Method', 'easy-invoice'),
				'class'   => 'easy-invoice-payment-method',
				'default' => '',
				'options' => [
					'ACH'    => __('ACH', 'easy-invoice'),
					'Cash'   => __('Cash', 'easy-invoice'),
					'Check'  => __('Check', 'easy-invoice'),
					'Payment App'  => __('Payment App', 'easy-invoice'),
				],
			],
			// "{$this->field_prefix}[transaction_id]" => [
			//	'type'    => 'text',
			//	'title'   => __('Transaction ID', 'easy-invoice'),
			//	'class'   => 'easy-invoice-payment-transaction-id',
			//	'default' => '',
			//],
			"{$this->field_prefix}[status]" => [
				'type'    => 'select',
				'title'   => __('Payment Status', 'easy-invoice'),
				'class'   => 'easy-invoice-payment-status',
				'default' => '',
				'options' => PaymentRepository::payment_statuses(),
			],
			'payment_item_1_end' => [
				'type' => 'wrap_end',
				'class' => 'easy-invoice-payment-item-1-wrap-end',
			],
			'payment_item_2_start' => [
				'type' => 'wrap',
				'class' => 'easy-invoice-payment-item-2-wrap',
			],
			"{$this->field_prefix}[payment_note]" => [
				'title' => __('Payment Note', 'easy-invoice'),
				'type'  => 'textarea',
				'class' => 'easy-invoice-payment-note',
			],
			'payment_section_end' => [
				'type'  => 'wrap_end',
				'class' => 'easy-invoice-payment-section-wrap-end',
			],
				'payment_item_2_end' => [
				'type' => 'wrap_end',
				'class' => 'easy-invoice-payment-item-3-wrap-end',
			],
		];
	}

	public function render()
	{
		$this->output();
	}

	public function nonce_id()
	{
		$nonce = 'easy_invoice_payment_fields_nonce';
		return $nonce;
	}

	public function save($post_data, $post_id)
	{

		if (empty($post_data) || !check_admin_referer($this->nonce_id(), $this->nonce_id() . '_nonce')) {
			return;
		}

		// Extract payment data
		$payment_data = $post_data['easy_invoice_payment_items'] ?? [];
		if (empty($payment_data)) {
			return;
		}

		// Check if paid_amount is greater than 0
		$paid_amount = floatval($payment_data['paid_amount'] ?? 0);
		if ($paid_amount <= 0) {
			return;
		}

		// Retrieve invoice information
		$invoice = new InvoiceRepository($post_id);
		$due_amount = $invoice->get_due_amount();
		$total_paid = $invoice->get_total_paid();

		if ($due_amount <= 0) {
			return;
		}

		// Create or update the payment
		$payment_id = isset($payment_data['payment_id']) ? absint($payment_data['payment_id']) : 0;
		if ($payment_id > 0) {
			$payment = new PaymentRepository($payment_id);
		} else {
			$payment_gateway = $payment_data['payment_gateway'] ?? '';
			$new_payment_id = PaymentRepository::create($post_id, $payment_gateway);
			if (!$new_payment_id) {
				return;
			}
			$payment = new PaymentRepository($new_payment_id);
		}

		// Update payment details
		$payment->update_paid_amount($paid_amount);
		$payment->update_payment_gateway($payment_data['payment_gateway']);
		$payment->update_transaction_id($payment_data['transaction_id']);
		$payment->update_status($payment_data['status']);
		$payment->add_note($payment_data ['payment_note']);
		//lei payment date
		$raw_date = $payment_data['payment_date'] ?? '';
		$normalized_date = '';

		if (!empty($raw_date)) {
			$timestamp = strtotime($raw_date);
			if ($timestamp !== false) {
				$normalized_date = date('Y-m-d H:i:s', $timestamp);
			}
		}

		$payment->update_payment_date($normalized_date);
	}
}
