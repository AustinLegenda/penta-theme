<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;

use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class BusinessSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_business';
		$this->label = __('Business', 'easy-invoice');

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections()
	{
		$sections = array();

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

		$logo_id = absint(get_theme_mod('custom_logo'));

		$settings = array(
			array(
				'title' => __('Business Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_business_settings',
			),

			array(
				'title' => __('Business Logo', 'easy-invoice'),
				'id' => 'easy_invoice_business_logo',
				'type' => 'image',
				'default' => $logo_id,
			),
			array(
				'title' => __('Business Name', 'easy-invoice'),
				'desc' => __('Your business name goes here', 'easy-invoice'),
				'id' => 'easy_invoice_business_name',
				'type' => 'text',
				'default' => get_bloginfo('name')
			),
			array(
				'title' => __('Business Address', 'easy-invoice'),
				'id' => 'easy_invoice_business_address',
				'desc' => __('HTML tags supports: a, br, em, strong, hr, p, h1 to h4', 'easy-invoice'),
				'type' => 'textarea',
				'allowed_html' => array(
					'a' => array(
						'href' => array(),
						'target' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
					'hr' => array(),
					'p' => array(),
					'h1' => array(),
					'h2' => array(),
					'h3' => array(),
					'h4' => array(),
					'h5' => array(),
					'h6' => array(),
				),
				'default' =>
					'Your main address
123 Somewhere Street
Your City Address 12345'
			),

			array(
				'title' => __('Additional Business Info', 'easy-invoice'),
				'id' => 'easy_invoice_business_additional_info',
				'desc' => __('Extra business info such as Business Number, phone number or email address and format it anyway you like. You can add your VAT number or ABN here.HTML tags supports: a, br, em, strong, hr, p, h1 to h4', 'easy-invoice'),
				'type' => 'textarea',
				'allowed_html' => array(
					'a' => array(
						'href' => array(),
						'target' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
					'hr' => array(),
					'p' => array(),
					'h1' => array(),
					'h2' => array(),
					'h3' => array(),
					'h4' => array(),
					'h5' => array(),
					'h6' => array(),
				),
				'default' => get_option('admin_email')
			),
			array(
				'title' => __('Website URL', 'easy-invoice'),
				'desc' => __('Your business website URL', 'easy-invoice'),
				'id' => 'easy_invoice_business_website_url',
				'type' => 'url',
				'default' => site_url()
			),
			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_business_settings',
			),

		);
		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
