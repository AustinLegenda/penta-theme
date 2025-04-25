<?php

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
			'payment_section_start' => [
				'type'  => 'wrap',
				'class' => 'easy-invoice-payment-section-wrap',
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
					'ach'    => __('ACH', 'easy-invoice'),
					'cash'   => __('Cash', 'easy-invoice'),
					'check'  => __('Check', 'easy-invoice'),
					'venmo'  => __('Venmo', 'easy-invoice'),
					'zelle'  => __('Zelle', 'easy-invoice'),
				],
			],
			"{$this->field_prefix}[transaction_id]" => [
				'type'    => 'text',
				'title'   => __('Transaction ID', 'easy-invoice'),
				'class'   => 'easy-invoice-payment-transaction-id',
				'default' => '',
			],
			"{$this->field_prefix}[status]" => [
				'type'    => 'select',
				'title'   => __('Payment Status', 'easy-invoice'),
				'class'   => 'easy-invoice-payment-status',
				'default' => '',
				'options' => PaymentRepository::payment_statuses(),
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
		];
	}

	public function render()
	{
		error_log('[PaymentFields] render() triggered');
		$this->output();
	}

	public function nonce_id()
	{
		$nonce = 'easy_invoice_payment_fields_nonce';
		error_log('[PaymentFields] nonce_id() returns: ' . $nonce);
		return $nonce;
	}

	public function save($post_data, $post_id)
	{
		error_log('[PaymentFields] save() triggered');
	
		if (empty($post_data) || !check_admin_referer($this->nonce_id(), $this->nonce_id() . '_nonce')) {
			error_log('[PaymentFields] Nonce check failed or post_data is empty.');
			return;
		}
	
		// Extract payment data
		$payment_data = $post_data['easy_invoice_payment_items'] ?? [];
		if (empty($payment_data)) {
			error_log('[PaymentFields] No payment data submitted. Exiting.');
			return;
		}
	
		// Check if paid_amount is greater than 0
		$paid_amount = floatval($payment_data['paid_amount'] ?? 0);
		if ($paid_amount <= 0) {
			error_log('[PaymentFields] paid_amount is 0 or not set. Skipping payment creation.');
			return;
		}
	
		error_log('[PaymentFields] Payment detected. Proceeding with payment creation.');
	
		// Retrieve invoice information
		$invoice = new InvoiceRepository($post_id);
		$due_amount = $invoice->get_due_amount();
		$total_paid = $invoice->get_total_paid();
	
		if ($total_paid >= $due_amount) {
			error_log('[PaymentFields] Invoice is already fully paid.');
			return;
		}
	
		// Create or update the payment
		$payment_id = isset($payment_data['payment_id']) ? absint($payment_data['payment_id']) : 0;
		if ($payment_id > 0) {
			error_log('[PaymentFields] Updating existing payment: ' . $payment_id);
			$payment = new PaymentRepository($payment_id);
		} else {
			error_log('[PaymentFields] Creating new payment for invoice: ' . $post_id);
			$payment_gateway = $payment_data['payment_gateway'] ?? '';
			$new_payment_id = PaymentRepository::create($post_id, $payment_gateway);
			if (!$new_payment_id) {
				error_log('[PaymentFields] Payment creation failed.');
				return;
			}
			$payment = new PaymentRepository($new_payment_id);
		}
	
		// Update payment details
		$payment->update_paid_amount($paid_amount);
		$payment->update_payment_date($payment_data['payment_date']);
		$payment->update_payment_gateway($payment_data['payment_gateway']);
		$payment->update_transaction_id($payment_data['transaction_id']);
		$payment->update_status($payment_data['status']);
	
		error_log('[PaymentFields] Payment successfully processed.');
	}
		}
