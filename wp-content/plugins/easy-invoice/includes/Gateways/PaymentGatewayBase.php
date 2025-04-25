<?php

namespace MatrixAddons\EasyInvoice\Gateways;

abstract class PaymentGatewayBase
{
	protected $settings = array();

	protected $id;

	abstract function admin_setting_tab();

	public function __construct($configuration)
	{
		$this->settings = $configuration['settings'] ?? array();

		add_filter('easy_invoice_payment_gateways', array($this, 'register_setting'), 10, 1);
		add_filter('easy_invoice_get_sections_ei_payment_gateways', array($this, 'subtab'), 10, 1);
		add_filter('easy_invoice_get_settings_ei_payment_gateways', array($this, 'payment_settings'), 10, 2);

		add_action('easy_invoice_validate_payment_gateway_request_' . $this->id, array($this, 'validate_payment_request'), 10, 1);
		add_action('easy_invoice_payment_checkout_payment_gateway_' . $this->id, array($this, 'process_payment'), 10, 2);

		//WebHook Register

		add_action('rest_api_init', array($this, 'register_webhook_api'));

	}

	function register_setting($gateways)
	{
		$settings = $this->settings ?? array();

		if (count($settings) > 0) {

			$gateways[] = $settings;
		}

		return $gateways;
	}

	public function subtab($section)
	{

		$gateway_config = $this->settings ?? '';

		if (isset($gateway_config['title'])) {

			$section[$this->id] = $gateway_config['title'];
		}
		return $section;

	}

	public function payment_settings($settings = array(), $current_section = '')
	{

		if ($current_section == $this->id) {

			return apply_filters('easy_invoice_settings_payment_gateways_' . $this->id, $this->admin_setting_tab());
		}
		return $settings;
	}

	abstract function validate_payment_request($validation_status);

	abstract function process_payment($payment_id);

	public function register_webhook_api()
	{

	}
}
