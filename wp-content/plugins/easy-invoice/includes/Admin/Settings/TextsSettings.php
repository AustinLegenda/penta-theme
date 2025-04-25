<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;

use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class TextsSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_texts';
		$this->label = __('Texts', 'easy-invoice');

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

		$translation_texts = easy_invoice_get_all_texts();

		$settings = array(
			array(
				'title' => __('Text Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_text_translation_settings',
			),
		);

		foreach ($translation_texts as $text_id => $text_label) {

			array_push($settings, array(
				'title' => sanitize_text_field($text_label),
				'id' => 'easy_invoice_text_' . sanitize_text_field($text_id),
				'type' => 'text',
				'default' => sanitize_text_field($text_label)
			));
		}

		array_push($settings, array(
			'type' => 'sectionend',
			'id' => 'easy_invoice_text_translation_settings',
		));


		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
