<?php

namespace MatrixAddons\EasyInvoice\Gateways\PayPal;

use MatrixAddons\EasyInvoice\API\PaymentWebhookBase;
use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;


class PayPalWebHooks extends PaymentWebhookBase
{
	protected $method = \WP_REST_Server::ALLMETHODS;

	public function validate_webhook_request(\WP_REST_Request $request)
	{
		include_once dirname(__FILE__) . '/php-paypal-ipn/IPNListener.php';

		$listener = new \IPNListener();

		$custom = isset($_POST['custom']) ? sanitize_text_field(stripslashes($_POST['custom'])) : "{}";

		$custom_array = json_decode($custom, true);

		$invoice_id = isset($custom_array['invoice_id']) ? absint($custom_array['invoice_id']) : 0;

		$payment_id = isset($custom_array['payment_id']) ? absint($custom_array['payment_id']) : 0;

		if ($invoice_id < 1 || $payment_id < 1) {

			return new \WP_Error('ei_invalid_request', __('invalid request. Invoice ID missing.', 'easy-invoice'));
		}

		$payment = new PaymentRepository($payment_id);

		$new_invoice_id = $payment->get_invoice_id();

		if (absint($new_invoice_id) !== absint($invoice_id)) {

			return new \WP_Error('ei_invalid_request', __('invalid request. Invoice ID missed matched.', 'easy-invoice'));
		}
		$listener->use_sandbox = easy_invoice_payment_gateway_test_mode();

		if ($listener->processIpn()) {

			return true;
		}

		return new \WP_Error('ei_invalid_request', __('invalid request.', 'easy-invoice'));
	}

	public function handle_webhook_request(\WP_REST_Request $request)
	{
		file_put_contents(ABSPATH . 'paypal_webhook_log.txt', print_r($_POST, true), FILE_APPEND);
		file_put_contents(ABSPATH . 'paypal_webhook_json_log.txt', file_get_contents("php://input"), FILE_APPEND);


		include_once dirname(__FILE__) . '/php-paypal-ipn/IPNListener.php';

		$listener = new \IPNListener();

		$custom = isset($_POST['custom']) ? stripslashes($_POST['custom']) : "{}";

		$custom_array = json_decode($custom, true);

		$payment_id = isset($custom_array['payment_id']) ? absint($custom_array['payment_id']) : 0;

		$payment = new PaymentRepository($payment_id);

		$payment_note = 'Proceed already done';

		$listener->use_sandbox = easy_invoice_payment_gateway_test_mode();

		if ($_POST['receiver_email'] != get_option('easy_invoice_payment_gateway_paypal_email')) {
			$payment_note .= "\nEmail seller email does not match email in settings\n";
		}

		if (trim($_POST['mc_currency']) != trim($payment->get_currency_code())) {
			$payment_note .= "\nCurrency does not match those assigned in settings\n";
		}

		$transaction_id = $payment->get_transaction_id();

		if (empty($transaction_id)) {
			$payment->update_transaction_id($transaction_id);
		} else {
			$payment_note .= "\nThis payment was already processed\n";
		}

		if (!empty($_POST['payment_status']) && $_POST['payment_status'] == 'Completed') {

			easy_invoice_update_payment_status($payment_id, 'publish', $_POST['mc_gross'], $transaction_id);

			$payment_note = 'PayPal Payment successfully completed';
		} else {

			$payment_note .= "\nPayment status not set to Completed\n";
		}

		$payment->update_payment_date(date('Y-m-d H:i:s'));

		$payment->add_note($payment_note);
	}
}
