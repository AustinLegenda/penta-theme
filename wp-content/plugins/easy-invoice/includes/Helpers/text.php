<?php

if (!function_exists('easy_invoice_get_all_texts')) {
	function easy_invoice_get_all_texts()
	{
		return apply_filters('easy_invoice_translatable_texts', [
			'invoice' => __('Invoice', 'easy-invoice'),
			'invoices' => __('Invoices', 'easy-invoice'),
			'from' => __('From', 'easy-invoice'),
			'to' => __('To', 'easy-invoice'),
			'invoice_number' => __('Invoice Number', 'easy-invoice'),
			'order_number' => __('Job Number', 'easy-invoice'),
			'invoice_date' => __('Invoice Date', 'easy-invoice'),
			'due_date' => __('Due Date', 'easy-invoice'),
			'total_due' => __('Total Due', 'easy-invoice'),
			'quantity' => __('Qty', 'easy-invoice'),
			'service' => __('Service', 'easy-invoice'),
			'rate' => __('Rate/Price', 'easy-invoice'),
			'adjust' => __('Adjust', 'easy-invoice'),
			'sub_total' => __('Sub Total', 'easy-invoice'),
			'total' => __('Total', 'easy-invoice'),
			'tax' => __('Tax', 'easy-invoice'),
			'discount' => __('Discount', 'easy-invoice'),
			'page' => __('Page', 'easy-invoice'),
			'print' => __('Print', 'easy-invoice'),
			'download_as_pdf' => __('Download as PDF', 'easy-invoice'),
			'send_email' => __('Send Email', 'easy-invoice'),
			'pay_now_button' => __('Pay Now', 'easy-invoice'),
			'proceed_to_payment_button' => __('Proceed to payment', 'easy-invoice'),
			'payment_gateway_information' => __('Invoice Payment Gateway', 'easy-invoice'),
			'quote' => __('Estimate', 'easy-invoice'),
			'quotes' => __('Estimates', 'easy-invoice'),
			'quote_number' => __('Estimate Number', 'easy-invoice'),
			'accept_quote' => __('Accept Estimate', 'easy-invoice'),
			'decline_quote' => __('Decline Estimate', 'easy-invoice'),
			'reason_for_decline_quote' => __('Reason for declining', 'easy-invoice'),
			'quote_amount' => __('Estimate Amount', 'easy-invoice'),
			'valid_until_date' => __('Valid Until Date', 'easy-invoice'),
			'quote_date' => __('Estimate Date', 'easy-invoice'),
			'deposit_required' => __('Advance Retainer', 'easy-invoice'),
			'paid' => __('Paid', 'easy-invoice')
		]);

	}
}


if (!function_exists('easy_invoice_get_text')) {

	function easy_invoice_get_text($text_id)
	{
		$all_texts = easy_invoice_get_all_texts();

		if (isset($all_texts[$text_id])) {

			$option_id = 'easy_invoice_text_' . sanitize_text_field($text_id);

			return get_option($option_id, $all_texts[$text_id]);
		}
		return '';
	}
}


