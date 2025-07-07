<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;


use MatrixAddons\EasyInvoice\Admin\Emails\DefaultEmailMessages;
use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class GeneralSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_general';
		$this->label = __('General', 'easy-invoice');

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
				'title' => __('General Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_general_settings',
			),
			array(
				'title' => __('Pre-Defined Line Items', 'easy-invoice'),
				'id' => 'easy_invoice_pre_defined_line_items',
				'desc' => __('Add one line item per line in this format: Qty | Title | Price | Description. Each field separated with a | symbol.
Price should be numbers only, no currency symbol.
If you prefer to have an item blank, you still need the | symbol like so: 1 | Software Development | | Software development cost', 'easy-invoice'),
				'type' => 'textarea',
				'default' => '1 | Software Development | 150 | Development cost
1 | Banner Design | 30 | Homepage Banner for the website'
			),
			array(
				'title' => __('Pre-Defined Section Headers', 'easy-invoice'),
				'id' => 'easy_invoice_pre_defined_section_titles',
				'desc' => __('Add one section header title per line', 'easy-invoice'),
				'type' => 'textarea',
				'default' => "Fees\nEquipment\nLocations\nCrew\nPost-Production"
			),
			array(
				'title'   => __('Description Templates', 'easy-invoice'),
				'id'      => 'easy_invoice_description_templates',
				'desc'    => __(
					'Add one template per line in the format:<br><code>Template Title | &lt;p&gt;Some HTML here&lt;/p&gt;</code>',
					'easy-invoice'
				),
				'type'    => 'textarea',
				'default' => '',
				'allowed_html' => array(
					'h1'     => array(),
					'h2'     => array(),
					'h3'     => array(),
					'h4'     => array(),
					'h5'     => array(),
					'p'      => array(),
					'strong' => array(),
					'em'     => array(),
					'br'     => array(),
					'a'      => array(
						'href'   => array(),
						'target' => array(),
					),
					'ul'     => array(),
					'ol'     => array(),
					'li'     => array(),
				),
			),
			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_general_settings',
			),

		);
		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
