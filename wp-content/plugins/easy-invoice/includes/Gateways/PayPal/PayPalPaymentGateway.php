<?php

namespace MatrixAddons\EasyInvoice\Gateways\PayPal;

use MatrixAddons\EasyInvoice\Gateways\PaymentGatewayBase;

class PayPalPaymentGateway extends PaymentGatewayBase
{
	protected $id = 'paypal';

	public function __construct()
	{
		include_once 'functions.php';

		$configuration = array(

			'settings' => array(
				'title' => __('PayPal Standard', 'easy-invoice'),
				'default' => 'no',
				'id' => $this->id,
				'frontend_title' => get_option('easy_invoice_payment_gateway_paypal_label_on_checkout', __('PayPal Standard', 'easy-invoice')),

			),
		);

		add_action('easy_invoice_payment_checkout_payment_gateway_paypal', array($this, 'process_payment'));

		parent::__construct($configuration);

	}

	public function admin_setting_tab()
	{
		return array(
			array(
				'title' => __('PayPal Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_payment_gateways_paypal_options',
			),
			array(
				'title' => __('PayPal Email Address', 'easy-invoice'),
				'desc' => __(' Enter your PayPal account\'s email', 'easy-invoice'),
				'id' => 'easy_invoice_payment_gateway_paypal_email',
				'type' => 'text',
			),
			array(
				'title' => __('Gateway Label', 'easy-invoice'),
				'desc' => __('Gateway label', 'easy-invoice'),
				'id' => 'easy_invoice_payment_gateway_paypal_label_on_checkout',
				'type' => 'text',
				'default' => __('Paypal Standard', 'easy-invoice')
			),

			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_payment_gateways_paypal_options',
			),

		);
	}

	public function validate_payment_request($validation_status)
	{

		if (get_option('easy_invoice_payment_gateway_paypal_email', '') !== '') {
			return $validation_status;
		}
		easy_invoice_redirect_with_error(1300, 'PayPal email is not setup. Please contact your site administrator.');

		return false;
	}

public function process_payment($original_payment_id)
{
    // Load the original payment to extract invoice ID
    $payment = new \MatrixAddons\EasyInvoice\Repositories\PaymentRepository($original_payment_id);
    $invoice_id = $payment->get_invoice_id();

    if (!$invoice_id) {
        wp_die(__('Invalid invoice ID.', 'easy-invoice'));
    }

    // Create a fresh payment post with the correct payable amount
    $new_payment_id = \MatrixAddons\EasyInvoice\Repositories\PaymentRepository::create($invoice_id, 'paypal');

    if (!$new_payment_id || is_wp_error($new_payment_id)) {
        wp_die(__('Could not create payment.', 'easy-invoice'));
    }

    /**
     * Optional: trigger pre-processing hook for plugins/extensions
     */
    do_action('easy_invoice_before_payment_process', $new_payment_id);

    // Build the PayPal redirect
    $paypal_request = new PayPalRequest();
    $redirect_url = $paypal_request->get_request_url($new_payment_id, $this->id);

    // Redirect user to PayPal
    wp_redirect($redirect_url);
    exit;
}

	public function register_webhook_api()
	{
		$handler = new PayPalWebHooks($this->id);

		$handler->register_routes();
	}


}
