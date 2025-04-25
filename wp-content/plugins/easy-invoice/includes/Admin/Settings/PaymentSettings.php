<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;


use MatrixAddons\EasyInvoice\Admin\Emails\DefaultEmailMessages;
use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class PaymentSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_payment_gateways';
		$this->label = __('Payment', 'easy-invoice');

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections()
	{
		$sections = array(
			'' => __('General', 'easy-invoice'),
		);

		return apply_filters('easy_invoice_get_sections_' . $this->id, $sections);
	}

	/**
	 * Output the settings.
	 */
	public function output()
	{
		global $current_section;

		$settings = $this->get_settings($current_section);

		Settings::output_fields($settings);
	}

	/**
	 * Save settings.
	 */
	public function save()
	{
		global $current_section;

		$settings = $this->get_settings($current_section);
		Settings::save_fields($settings);

		if ($current_section) {
			do_action('easy_invoice_update_options_' . $this->id . '_' . $current_section);
		}
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section name.
	 * @return array
	 */
	public function get_settings($current_section = '')
	{

		$settings = array(
			array(
				'title' => __('Currency Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_currency_options',
			),
			array(
				'title' => __('Currency', 'easy-invoice'),
				'id' => 'easy_invoice_currency',
				'type' => 'select',
				'default' => 'USD',
				'options' => easy_invoice_get_all_currency_with_symbol()
			),
			array(
				'title' => __('Currency Symbol Type', 'easy-invoice'),
				'desc' => __('Currency Symbol Type', 'easy-invoice'),
				'id' => 'easy_invoice_currency_symbol_type',
				'type' => 'select',
				'options' => array(
					'code' => __('Currency Code', 'easy-invoice'),
					'symbol' => __('Currency Symbol', 'easy-invoice')
				),
				'default' => 'symbol'
			),
			array(
				'title' => __('Currency symbol/code position', 'easy-invoice'),
				'id' => 'easy_invoice_currency_position',
				'default' => 'left',
				'type' => 'select',
				'options' => easy_invoice_get_currency_positions()
			),
			array(
				'title' => __('Thousand Separator', 'easy-invoice'),
				'desc' => __('Thousand separator for price.', 'easy-invoice'),
				'id' => 'easy_invoice_thousand_separator',
				'default' => ',',
				'type' => 'text',
			),
			array(
				'title' => __('Number of Decimals', 'easy-invoice'),
				'desc' => __('Number of decimals shown in price.', 'easy-invoice'),
				'id' => 'easy_invoice_price_number_decimals',
				'default' => 2,
				'type' => 'number',
			),
			array(
				'title' => __('Decimal Separator', 'easy-invoice'),
				'desc' => __('Decimal separator for price.', 'easy-invoice'),
				'id' => 'easy_invoice_decimal_separator',
				'default' => '.',
				'type' => 'text',
			),
			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_currency_options',
			),
			array(
				'title' => __('Payment Gateways', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_payment_general_options',
			),
			'test_mode' => array(
				'id' => 'easy_invoice_payment_gateway_test_mode',
				'name' => __('Test Mode', 'easy-invoice'),
				'desc' => __('While test mode is enabled, no live transactions are processed.<br>Use test mode in conjunction with the sandbox/test account for the payment gateways to test.', 'easy-invoice'),
				'type' => 'checkbox'
			),
			array(
				'title' => __('Enable proceed to payment button', 'easy-invoice'),
				'id' => 'easy_invoice_enable_proceed_to_payment',
				'type' => 'checkbox',
				'default' => 'yes',
				'desc' => 'Enable/disable proceed to payment button on invoice.'
			),
			array(
				'title' => __('Proceed to payment button link', 'easy-invoice'),
				'id' => 'easy_invoice_proceed_to_payment_button_link',
				'type' => 'text',
				'default' => '#',
				'desc' => 'Proceed to payment button link. This option will works only if you do not have any available payment gateways.'
			),
			array(
				'title' => __('Thank you page', 'easy-invoice'),
				'id' => 'easy_invoice_thankyou_page',
				'type' => 'single_select_page',
				'desc' => 'Thank you page.'
			),
			array(
				'title' => __('Payment Gateways', 'easy-invoice'),
				'id' => 'easy_invoice_payment_gateways',
				'type' => 'multicheckbox',
				'options' => easy_invoice_get_payment_gateways()

			),

			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_payment_general_options',
			),

		);


		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
