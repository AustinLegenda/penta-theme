<?php

if (!function_exists('easy_invoice_get_paypal_api_endpoint')) {

	function easy_invoice_get_paypal_api_endpoint($ssl_check = false)
	{
		if (is_ssl() || !$ssl_check) {

			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}

		if (easy_invoice_payment_gateway_test_mode()) {

			$paypal_uri = $protocol . 'sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$paypal_uri = $protocol . 'paypal.com/cgi-bin/webscr';
		}

		return $paypal_uri;
	}
}
if (!function_exists('easy_invoice_get_payment_gateway_webhook_api_endpoint')) {

	function easy_invoice_get_payment_gateway_webhook_api_endpoint($gateway_id)
	{
		return get_rest_url(null, (EASY_INVOICE_REST_WEBHOOKS_NAMESPACE . '/' . $gateway_id));
	}
}
