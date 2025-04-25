<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;

use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class TaxSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_tax';
		$this->label = __('Tax', 'easy-invoice');

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

		$settings = array(
			array(
				'title' => __('Tax Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_tax_settings',
			),
			array(
				'title' => __('How do you enter tax?', 'easy-invoice'),
				'id' => 'easy_invoice_tax_type',
				'type' => 'radio',
				'options' => array(
					'inclusive' => __('I will enter price inclusive of tax', 'easy-invoice'),
					'exclusive' => __('I will enter price exclusive of tax', 'easy-invoice'),
				),
				'default' => 'exclusive'
			),

			array(
				'title' => __('Tax Percentage', 'easy-invoice'),
				'id' => 'easy_invoice_tax_percentage',
				'type' => 'number',
				'default' => 10,
				'custom_attributes' => array(
					'step' => 'any',

				)
			),
			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_tax_settings',
			),

		);
		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
