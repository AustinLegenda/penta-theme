<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;


use MatrixAddons\EasyInvoice\Admin\Emails\DefaultEmailMessages;
use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class QuoteSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_quote';
		$this->label = __('Estimates', 'easy-invoice');

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
				'title' => __('Quote Settings', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_quote_settings',
			),
			array(
				'title' => __('Quote Prefix', 'easy-invoice'),
				'id' => 'easy_invoice_quote_number_prefix',
				'type' => 'text',
				'default' => 'EIQN_'
			),
			array(
				'title' => __('Last Quote Number', 'easy-invoice'),
				'id' => 'easy_invoice_quote_number',
				'type' => 'number',
				'default' => 0
			),

			array(
				'title' => __('Show/Hide Adjust Field', 'easy-invoice'),
				'desc' => __('Enable/Disable Adjust field. Tick this to show adjust field', 'easy-invoice'),
				'id' => 'easy_invoice_quote_show_hide_adjust',
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
				'id' => 'easy_invoice_quote_footer_text',
				'desc' => __('You can modify your quote footer text from here.HTML tags supports: a, br, em, strong, hr, p, h1 to h4', 'easy-invoice'),
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
				'id' => 'easy_invoice_quote_settings',
			),
			array(
				'title' => __('Quote Acceptance', 'easy-invoice'),
				'type' => 'title',
				'desc' => '',
				'id' => 'easy_invoice_quote_acceptance_settings',
			),
			array(
				'title' => __('Accept quote button', 'easy-invoice'),
				'desc' => __('Show/hide accept quote button on quotes.', 'easy-invoice'),
				'id' => 'easy_invoice_quote_accept_quote_button',
				'type' => 'checkbox',
				'default' => 'yes'
			),
			array(
				'title' => __('Accept quote button action', 'easy-invoice'),
				'desc' => __('Upon the client clicking the "accept quote" button, the subsequent action will be activated.', 'easy-invoice'),
				'id' => 'easy_invoice_quote_accept_quote_button_action',
				'type' => 'select',
				'options' => array(
					'convert' => sprintf(
					/* translators: %1s is a placeholder for the word "Quote" (singular); %2s is a placeholder for "Invoice" (singular) */
						__('Convert %1s to %2s', 'easy-invoice'),
						'quote',
						'invoice'
					),
					'convert_send' => sprintf(
					/* translators: %1s is a placeholder for the word "Quote" (singular); %2s is a placeholder for "Invoice" (singular) */
						__('Convert %1s to %2s and send to client', 'easy-invoice'),
						'quote',
						'invoice'
					),
					'duplicate' => sprintf(
					/* translators: %1s is a placeholder for the word "Invoice" (singular); %2s is a placeholder for "Quote" (singular) */
						__('Create new %1s, keep %2s as-is', 'easy-invoice'),
						'invoice',
						'quote'
					),
					'duplicate_send' => sprintf(
					/* translators: %1s is a placeholder for the word "Invoice" (singular); %2s is a placeholder for "Quote" (singular) */
						__('Create new %1s and send to client, keep %2s as-is', 'easy-invoice'),
						'invoice',
						'quote'
					),
					'do_nothing' => __('Do nothing', 'easy-invoice'),
				),
				'default' => 'yes'
			),
			array(
				'title' => __('Accept Quote Text', 'easy-invoice'),
				'id' => 'easy_invoice_accept_quote_text',
				'desc' => __('This information tells your client what happens once they accept the Quote', 'easy-invoice'),
				'type' => 'textarea',
				'default' => __('Important: When you accept this Quote, an Invoice will be created automatically. This will form a legally binding contract.', 'yatra')
			),
			array(
				'title' => __('Accepted Quote Message', 'easy-invoice'),
				'id' => 'easy_invoice_accepted_quote_message',
				'desc' => __('If the client accepts the Quote, display this message.', 'easy-invoice'),
				'type' => 'textarea',
				'default' => __('You\'ve confirmed the Quote.<br>We\'ll get in touch with you shortly.', 'yatra')
			),

			array(
				'title' => __('Decline Reason Required', 'easy-invoice'),
				'desc' => __('Make the \'Reason for declining\' field mandatory when rejecting.', 'easy-invoice'),
				'id' => 'easy_invoice_decline_quote_reason_required',
				'type' => 'checkbox',
				'default' => 'no'
			),

			array(
				'title' => __('Declined Quote Message', 'easy-invoice'),
				'id' => 'easy_invoice_declined_quote_message',
				'desc' => __('Message to display if client declines the Quote', 'easy-invoice'),
				'type' => 'textarea',
				'default' => __('You\'ve declined the Quote.<br>We\'ll get in touch with you shortly', 'yatra')
			),
			array(
				'type' => 'sectionend',
				'id' => 'easy_invoice_quote_acceptance_settings',
			),

		);

		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
