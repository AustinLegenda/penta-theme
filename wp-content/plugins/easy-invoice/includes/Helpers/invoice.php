<?php

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;


if (!function_exists('easy_invoice_get_invoice_options')) {

	function easy_invoice_get_invoice_options($invoice_id = null)
	{
		$invoice_id = is_null($invoice_id) ? get_the_ID() : absint($invoice_id);

		return new InvoiceRepository($invoice_id);
	}
}


if (!function_exists('easy_invoice_get_invoice_label')) {
	function easy_invoice_get_invoice_label()
	{
		return easy_invoice_get_text('invoice');
	}
}


if (!function_exists('easy_invoice_get_invoice_details_data')) {
	function easy_invoice_get_invoice_details_data()
	{
		global $ei_invoice;

		return array(
			array(
				'label' => easy_invoice_get_text('invoice_number'),
				'value' => $ei_invoice->get_invoice_number(),
			),
			array(
				'label' => easy_invoice_get_text('order_number'),
				'value' => $ei_invoice->get_order_number(),
			),
			array(
				'label' => easy_invoice_get_text('invoice_date'),
				'value' => $ei_invoice->get_created_date(),
			),
			array(
				'label' => easy_invoice_get_text('due_date'),
				'value' => $ei_invoice->get_due_date(),
			),
			array(
				'label' => easy_invoice_get_text('total_due'),
				'value' => easy_invoice_get_price($ei_invoice->get_due_amount(), '', $ei_invoice->get_id()),
				'is_total' => true,
			),
		);
	}
}

if (!function_exists('easy_invoice_get_invoice_description')) {
	function easy_invoice_get_invoice_description()
	{
		global $ei_invoice;

		return $ei_invoice->get_description();
	}
}


if (!function_exists('easy_invoice_get_download_as_pdf_url')) {
	function easy_invoice_get_download_as_pdf_url($object_id)
	{
		return add_query_arg(array(
			'action' => 'download_as_pdf',
			'nonce' => wp_create_nonce('download_as_pdf'),
		), get_permalink($object_id));
	}
}


if (!function_exists('easy_invoice_get_invoice_number')) {
	function easy_invoice_get_invoice_number()
	{
		$pad_len = 4;

		$invoice_number = absint(get_option('easy_invoice_invoice_number', 0));

		$invoice_number = $invoice_number + 1;

		$invoice_number = $invoice_number < 1 ? 1 : $invoice_number;

		$prefix = get_option('easy_invoice_number_prefix', 'EIN_');

		//update_option('easy_invoice_invoice_number', absint($invoice_number));

		if (is_string($prefix)) {
			return sprintf("%s%s", $prefix, str_pad($invoice_number, $pad_len, "0", STR_PAD_LEFT));
		}

		return str_pad($invoice_number, $pad_len, "0", STR_PAD_LEFT);
	}
}
if (!function_exists('easy_invoice_update_invoice_status')) {

	function easy_invoice_update_invoice_status($invoice_id = 0, $status = '')
	{
		$easy_invoice_invoice_statuses = easy_invoice_get_invoice_statuses();

		if (absint($invoice_id) < 1 || !isset($easy_invoice_invoice_statuses[$status])) {

			return false;
		}

		do_action('easy_invoice_before_invoice_status_change', array(
			'invoice_id' => $invoice_id,
			'status' => $status
		));

		update_post_meta($invoice_id, 'invoice_status', sanitize_text_field($status));


		do_action('easy_invoice_after_invoice_status_change', array(
			'invoice_id' => $invoice_id,
			'status' => $status
		));

		return true;
	}
}


if (!function_exists('easy_invoice_get_invoice_statuses')) {
	function easy_invoice_get_invoice_statuses()
	{

		return [
			'draft' => __("Draft", "easy-invoice"),
			'available' => __("Available", "easy-invoice"),
			'pending_deposit' => __('Pending Deposit', 'easy-invoice'),
			'pending_final' => __('Pending Final', 'easy-invoice'),
			'overdue' => __("Overdue", "easy-invoice"),
			'paid' => __("Paid", "easy-invoice"),
			'unpaid' => __("Unpaid", "easy-invoice"),
			'cancelled' => __("Cancelled", "easy-invoice"),
		];
	}
}

//LEI Manual Payment


// LEI Only create a payment post when status is 'paid'
/*if ($status === 'paid') {
	
	$existing_payments = get_posts(array(
		'post_type'   => 'easy-invoice-payment',
		'meta_key'    => 'invoice_id',
		'meta_value'  => $invoice_id,
		'post_status' => 'any',
		'numberposts' => 1
	));

	if (!empty($existing_payments)) {
		error_log("Payment already exists for Invoice ID: " . $invoice_id . ". Skipping creation.");
		return true;
	}


	$invoice = new InvoiceRepository($invoice_id);
	$paid_amount = $invoice->get_due_amount(); 
	$payment_id = wp_insert_post(array(
		'post_title'   => 'Payment - #' . $invoice_id,
		'post_status'  => 'processing',  
		'post_type'    => 'easy-invoice-payment',
		'meta_input'   => array(
			'invoice_id'      => $invoice_id,
			'payment_gateway' => 'Digital, Check, or Cash Deposit',
			'paid_amount'     => $paid_amount,  
		)
	));

	if ($payment_id) {
		easy_invoice_update_payment_status($payment_id, 'publish', $paid_amount, '');
	} else {
		error_log("Failed to create payment post for Invoice ID: " . $invoice_id);
	}
}*/