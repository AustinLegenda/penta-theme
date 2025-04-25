<?php

namespace MatrixAddons\EasyInvoice\Gateways\PayPal;

use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;

class PayPalRequest
{

	public function get_request_url($payment_id, $gateway_id)
	{

		$args = $this->get_paypal_args($payment_id, $gateway_id);

		$redirect_uri = esc_url(home_url('/'));

		if ($args) {

			$paypal_args = http_build_query($args, '', '&');

			$redirect_uri = esc_url(easy_invoice_get_paypal_api_endpoint()) . '?' . $paypal_args;
		}

		return $redirect_uri;
	}

	protected function limit_length($string, $limit = 127)
	{
		$str_limit = $limit - 3;
		if (function_exists('mb_strimwidth')) {
			if (mb_strlen($string) > $limit) {
				$string = mb_strimwidth($string, 0, $str_limit) . '...';
			}
		} else {
			if (strlen($string) > $limit) {
				$string = substr($string, 0, $str_limit) . '...';
			}
		}
		return $string;
	}

	private function get_paypal_args($payment_id, $gateway_id)
	{
		$paypal_email = get_option('easy_invoice_payment_gateway_paypal_email', '');

		if ('' == $paypal_email || empty($paypal_email)) {
			easy_invoice_redirect_with_error(1300, 'PayPal email is not setup. Please contact your site administrator.');
			return;
		}

		$payment = new PaymentRepository($payment_id);

		$invoice_id = $payment->get_invoice_id();

		$currency_code = $payment->get_currency_code();

		$amount = $payment->get_payable_amount();

		$thank_you_page_id = get_option('easy_invoice_thankyou_page');

		$cancel_page_url = home_url();

		$thank_you_page = 'publish' == get_post_status($thank_you_page_id) ? get_permalink($thank_you_page_id) : home_url();

		$discount_amount = 0;

		$payment_type = $payment->get_payment_type();

		$args_index = 1;

		$args['cmd'] = '_cart';
		$args['upload'] = '1';
		$args['currency_code'] = $currency_code;
		$args['business'] = $paypal_email;
		$args['bn'] = '';
		$args['rm'] = '2';
		$args['discount_amount_cart'] = 0;
		$args['tax_cart'] = 0;
		$args['charset'] = get_bloginfo('charset');
		$args['cbt'] = get_bloginfo('name');
		$args['return'] = add_query_arg(
			array(
				'payment_id' => $payment_id,
				'invoice_id' => $invoice_id,
				'paid' => true,
				'status' => 'success',
			),
			$thank_you_page
		);
		$args['cancel'] = add_query_arg(
			array(
				'invoice_id' => $invoice_id,
				'payment_id' => $payment_id,
				'booked' => true,
				'status' => 'cancel',
			),
			$cancel_page_url
		);
		$args['handling'] = 0;
		$args['handling_cart'] = 0;
		$args['no_shipping'] = 0;
		$args['notify_url'] = esc_url(easy_invoice_get_payment_gateway_webhook_api_endpoint($gateway_id));

		$args['landing_page'] = 'Billing'; // Forces PayPal to show Credit/Debit option first

		if ($payment_type === 'partial') {

			//Add Line Items to PayPal Line Items
			$args['item_name_' . $args_index] = 'Partial Payment for Invoice #' . $invoice_id;

			$args['quantity_' . $args_index] = 1;

			$args['amount_' . $args_index] = sanitize_text_field(wp_unslash($amount));

			$args['item_number_' . $args_index] = $invoice_id;

			//End of Line Items

		} else {
			$args['amount'] = $amount;

			$args['discount_amount_cart'] = $discount_amount;

			//Add Line Items to PayPal Line Items
			$item_name = get_the_title($invoice_id);

			$args['item_name_' . $args_index] = $this->limit_length($item_name, 127);

			$args['quantity_' . $args_index] = 1;

			$args['amount_' . $args_index] = $amount;

			$args['item_number_' . $args_index] = $invoice_id;

			$args['on2_' . $args_index] = __('Total Price', 'easy-invoice');

			$args['os2_' . $args_index] = $amount;

			//End of Line Items

		}


		$args['option_index_0'] = $args_index;

		$args['custom'] = json_encode(array('invoice_id' => $invoice_id, 'payment_id' => $payment_id));

		return apply_filters('easy_invoice_paypal_args', $args);
	}
}
