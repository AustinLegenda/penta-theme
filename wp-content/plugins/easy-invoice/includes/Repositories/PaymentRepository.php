<?php

namespace MatrixAddons\EasyInvoice\Repositories;

use MatrixAddons\EasyInvoice\Constant;

class PaymentRepository
{
	private $payment_id = 0;

	public function __construct($payment_id)
	{
		$this->payment_id = $payment_id;
	}

	public static function payment_statuses()
	{
		return array(
			'processing' => __('Pending', 'easy-invoice'),
			'publish' => __('Completed', 'easy-invoice'),
			'hold' => __('On Hold', 'easy-invoice'),
			'refunded' => __('Refunded', 'easy-invoice'),
			'failed' => __('Failed', 'easy-invoice')
		);
	}

	public static function create($invoice_id, $payment_gateway)
	{

		if (absint($invoice_id < 1)) {
			return;
		}

		$invoice = new InvoiceRepository($invoice_id);

		if (!$invoice->is_invoice_payable()) {
			return;
		}

		$title = 'Payment - #' . $invoice_id;

		$paid_amount = self::get_total_paid_amount_by_invoice_id($invoice_id);

		$total_amount = $invoice->get_due_amount();

		$due_amount = ($total_amount - $paid_amount) > 0 ? ($total_amount - $paid_amount) : 0;

		$all_payment_info = self::get_all_payments_by_invoice_id($invoice_id);

		$installment = is_array($all_payment_info) ? (count($all_payment_info) + 1) : 1;

		$payment_type = floatval($paid_amount) > 0 ? 'partial' : 'full';

		$post_array = apply_filters('easy_invoice_before_payment_created', array(
			'post_title' => $title,
			'post_content' => '',
			'post_status' => 'processing',
			'post_slug' => uniqid(),
			'post_type' => CONSTANT::PAYMENT_POST_TYPE,
			'meta_input' => array(
				'payment_gateway' => $payment_gateway,
				'total_amount' => $total_amount,
				'currency_code' => $invoice->get_currency(),
				'paid_amount' => $paid_amount,
				'payable_amount' => $due_amount,
				'due_amount' => $due_amount,
				'payment_type' => $payment_type,
				'invoice_id' => $invoice_id,
				'installment' => $installment,
				'client_email' => $invoice->get_client_email(),
				'transaction_id' => '',
				'payment_note' => '',
			)
		));


		return wp_insert_post($post_array);

	}

	public static function get_total_paid_amount_by_invoice_id($invoice_id)
	{
		$total_paid_amount = 0;

		$all_payment = get_posts(array(
			'numberposts' => -1,
			'meta_key' => 'invoice_id',
			'meta_value' => $invoice_id,
			'post_type' => CONSTANT::PAYMENT_POST_TYPE,
			'post_status' => 'publish'
		));

		if (!is_wp_error($all_payment)) {

			foreach ($all_payment as $payment) {

				if ($payment->post_status === 'publish') {

					$id = $payment->ID;

					$total_paid = floatval(get_post_meta($id, 'paid_amount', true));

					$total_paid_amount += $total_paid;
				}

			}
		}
		return $total_paid_amount;
	}

	public static function get_all_payments_by_invoice_id($invoice_id, $payment_type = 'any')
	{
		$payments = get_posts(array(
			'numberposts' => -1,
			'meta_key' => 'invoice_id',
			'meta_value' => $invoice_id,
			'post_type' =>CONSTANT::PAYMENT_POST_TYPE,
			'post_status' => sanitize_text_field($payment_type),
			'order' => 'ASC',

		));

		$payment_info = array();

		foreach ($payments as $payment) {

			$payment_id = $payment->ID;

			$status = $payment->post_status;

			$payment_info[$payment_id] = [
				'title' => $payment->post_title . ' [ Payment ID: ' . $payment_id . '] ',
				'payment_gateway' => get_post_meta($payment_id, 'payment_gateway', true),
				'total_amount' => get_post_meta($payment_id, 'total_amount', true),
				'currency_code' => get_post_meta($payment_id, 'currency_code', true),
				'paid_amount' => get_post_meta($payment_id, 'paid_amount', true),
				'payable_amount' => get_post_meta($payment_id, 'payable_amount', true),
				'due_amount' => get_post_meta($payment_id, 'due_amount', true),
				'payment_type' => get_post_meta($payment_id, 'payment_type', true),
				'payment_note' => get_post_meta($payment_id, 'payment_note', true),
				'invoice_id' => get_post_meta($payment_id, 'invoice_id', true),
				'installment' => get_post_meta($payment_id, 'installment', true),
				'transaction_id' => get_post_meta($payment_id, 'transaction_id', true),
				'status' => $status,
				'payment_date' => $payment->post_date,
				'payment_id' => $payment_id
			];

		}
		return $payment_info;
	}

	public function get_title()
	{

		return get_the_title($this->payment_id);
	}

	public function get_id()
	{
		return $this->payment_id;
	}

	public function get_net_due_amount()
	{
		$invoice_id = $this->get_invoice_id();

		$invoice = new InvoiceRepository($invoice_id);

		$paid_amount = self::get_total_paid_amount_by_invoice_id($invoice_id);

		$total_amount = $invoice->get_due_amount();

		return ($total_amount - $paid_amount) > 0 ? ($total_amount - $paid_amount) : 0;

	}

	public function get_gateway()
	{
		return get_post_meta($this->payment_id, 'payment_gateway', true);
	}

	public function get_total_amount()
	{
		return get_post_meta($this->payment_id, 'total_amount', true);
	}

	public function get_currency_code()
	{
		return get_post_meta($this->payment_id, 'currency_code', true);
	}

	public function get_paid_amount()
	{
		return get_post_meta($this->payment_id, 'paid_amount', true);
	}

	public function get_payable_amount()
	{
		return floatval(get_post_meta($this->payment_id, 'payable_amount', true));
	}

	public function get_due_amount()
	{
		return get_post_meta($this->payment_id, 'due_amount', true);
	}

	public function get_payment_type()
	{
		return get_post_meta($this->payment_id, 'payment_type', true);
	}

	public function get_invoice_id()
	{
		return get_post_meta($this->payment_id, 'invoice_id', true);
	}

	public function get_installment_id()
	{
		return get_post_meta($this->payment_id, 'installment', true);
	}

	public function get_status()
	{
		return get_post_status($this->payment_id);
	}

	public function get_transaction_id()
	{
		return get_post_meta($this->payment_id, 'transaction_id', true);
	}

	public function get_note()
	{
		return get_post_meta($this->payment_id, 'payment_note', true);

	}

	public function get_payment_date()
	{
		return get_post_meta($this->payment_id, 'payment_date', true);

	}

	public function get_client_email()
	{
		return get_post_meta($this->payment_id, 'client_email', true);

	}

	public function update_status($status)
	{
		$all_status = $this->payment_statuses();

		if (isset($all_status[$status])) {

			$arg['ID'] = $this->payment_id;

			$arg['post_status'] = $status;

			wp_update_post($arg);
		}

	}

	public function update_transaction_id($transaction_id = '')
	{
		if ($transaction_id != '') {

			update_post_meta($this->payment_id, 'transaction_id', sanitize_text_field($transaction_id));
		}
	}

	public function add_note($payment_note = '')
	{
		update_post_meta($this->payment_id, 'payment_note', sanitize_text_field($payment_note));

	}

	public function update_paid_amount($paid_amount)
	{
		return update_post_meta($this->payment_id, 'paid_amount', floatval($paid_amount));
	}

	public function update_due_amount($due_amount)
	{
		return update_post_meta($this->payment_id, 'due_amount', floatval($due_amount));
	}

	public function update_payment_date($date)
	{
		return update_post_meta($this->payment_id, 'payment_date', sanitize_text_field($date));
	}

	public function update_payment_gateway($payment_gateway)
	{
		return update_post_meta($this->payment_id, 'payment_gateway', sanitize_text_field($payment_gateway));
	}


}
