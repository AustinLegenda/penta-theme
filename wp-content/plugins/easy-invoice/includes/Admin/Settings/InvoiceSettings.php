<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;


use MatrixAddons\EasyInvoice\Admin\Emails\DefaultEmailMessages;
use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class InvoiceSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_invoice';
		$this->label = __('Invoice', 'easy-invoice');

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

		$footer_text = 'Thanks for choosing <a href="' . esc_url(site_url()) . '">' . esc_html(get_bloginfo('name')) . '</a> | <a href="mailto:' . esc_attr(get_option('admin_email')) . '">' . esc_html(get_option('admin_email')) . '</a>';
		$settings = array(
			array(
				'title' => __('Invoice Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_general_options',
			),
			array(
				'title' => __('Invoice Prefix', 'easy-invoice'),
				'id' => 'easy_invoice_number_prefix',
				'type' => 'text',
				'default' => 'EIN_'
			),

			array(
				'title' => __('Last Invoice Number', 'easy-invoice'),
				'id' => 'easy_invoice_invoice_number',
				'type' => 'number',
				'default' => 0
			),
			array(
				'title' => __('Show/Hide Adjust Field', 'easy-invoice'),
				'desc' => __('Enable/Disable Adjust field. Tick this to show adjust field', 'easy-invoice'),
				'id' => 'easy_invoice_show_hide_adjust',
				'type' => 'checkbox',
				'default' => 'yes'
			),
			array(
				'title' => __('Terms & Conditions', 'easy-invoice'),
				'id' => 'easy_invoice_quote_terms_conditions',
				'desc' => __('Terms and conditions that will be displayed on your quote!', 'easy-invoice'),
				'type' => 'textarea',
				'allowed_html' => array(
					'a' => array(
						'href' => array(),
						'target' => array()
					),
					'br' => array(),
					'strong' => array(),
					'hr' => array(),
					'p' => array(),
				),
				'default' => __('This quote has a fixed price. Upon acceptance, we kindly ask for a 25% deposit prior to initiating the work.', 'yatra')
			),
			array(
				'title' => __('Footer Text', 'easy-invoice'),
				'id' => 'easy_invoice_footer_text',
				'desc' => __('You can modify your invoice footer text from here.HTML tags supports: a, br, em, strong, hr, p, h1 to h4', 'easy-invoice'),
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
				'default' => $footer_text
			),
			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_general_options',
			),

		);

		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
