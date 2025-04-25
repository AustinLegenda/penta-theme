<?php

namespace MatrixAddons\EasyInvoice\Gateways;
final class PaymentGatewayLoader
{
	private static $instance;

	public static function instance()
	{
		if (empty(self::$instance)) {

			self::$instance = new self;
		}
		return self::$instance;
	}

	public function init()
	{
		$this->includes();

		add_action('init', array($this, 'register'));
	}

	public function includes()
	{
		include_once EASY_INVOICE_ABSPATH . 'includes/Gateways/Helpers/payment.php';

	}

	public function register()
	{
		$payment_gateways = apply_filters('easy_invoice_registered_payment_gateways', array(

			'\MatrixAddons\EasyInvoice\Gateways\PayPal\PayPalPaymentGateway'
		));

		foreach ($payment_gateways as $gateway) {

			if (class_exists($gateway)) {

				new $gateway;
			}
		}

	}


}
