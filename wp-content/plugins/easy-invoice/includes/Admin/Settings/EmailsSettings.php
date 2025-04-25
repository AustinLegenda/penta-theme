<?php

namespace MatrixAddons\EasyInvoice\Admin\Settings;


use MatrixAddons\EasyInvoice\Admin\Emails\DefaultEmailMessages;
use MatrixAddons\EasyInvoice\Admin\Setting_Base;
use MatrixAddons\EasyInvoice\Admin\Settings;

if (!defined('ABSPATH')) {
	exit;
}

class EmailsSettings extends Setting_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->id = 'ei_emails';
		$this->label = __('Emails', 'easy-invoice');

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
			'invoice_available' => __('Invoice Available', 'easy-invoice'),
			'quote_available' => __('Quote Available', 'easy-invoice'),
			'payment_received' => __('Payment Received', 'easy-invoice'),
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

	public static function email_content_allowed_html()
	{
		return array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'style' => array()
			),
			'br' => array(
				'style' => array()
			),
			'em' => array(
				'style' => array()
			),
			'i' => array(
				'style' => array()
			),
			'strong' => array(
				'style' => array()
			),
			'b' => array(
				'style' => array()
			),
			'img' => array(
				'src' => array(),
			),
			'hr' => array('style' => array()),
			'p' => array('style' => array()),
			'h1' => array('style' => array()),
			'h2' => array('style' => array()),
			'h3' => array('style' => array()),
			'h4' => array('style' => array()),
			'h5' => array('style' => array()),
			'h6' => array('style' => array()),
			'ol' => array('style' => array()),
			'ul' => array('style' => array()),
			'li' => array('style' => array())
		);

	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section name.
	 * @return array
	 */
	public function get_settings($current_section = '')
	{

		if ('invoice_available' === $current_section) {
			$settings = array(
				array(
					'title' => __('Invoice available email to client', 'easy-invoice'),
					'type' => 'title',
					'desc' => '',
					'id' => 'easy_invoice_email_invoice_available',
				),
				array(
					'title' => __('Email Subject', 'easy-invoice'),
					'id' => 'easy_invoice_email_invoice_available_email_subject_to_client',
					'type' => 'text',
					'default' => DefaultEmailMessages::get_invoice_available_subject_to_client(),
				),
				array(
					'title' => __('Email Message', 'easy-invoice'),
					'id' => 'easy_invoice_email_invoice_available_email_message_to_client',
					'type' => 'textarea',
					'editor' => true,
					'allow-html' => true,
					'editor_settings' => array(
						'tinymce' => array(
							'toolbar1' => 'bold,italic,underline,link,unlink,undo,redo',
						),
					),
					'allowed_html' => self::email_content_allowed_html(),
					'custom_attributes' => array(
						'size' => 70
					),
					'default' => DefaultEmailMessages::get_invoice_available_message_to_client(),
				),
				array(
					'title' => '',
					'id' => 'easy_invoice_smart_tags',
					'type' => 'content',
					'content' => easy_invoice_get_available_invoice_smart_tags_html_content(),
					'allowed_html' => array(
						'div' => array('style'),
						'p' => array(),
						'span' => array(),
						'strong' => array(),
						'h2' => array(),
					),
				),
				array(
					'type' => 'sectionend',
					'id' => 'easy_invoice_email_invoice_available',
				),

			);

		} else if ('quote_available' === $current_section) {
			$settings = array(
				array(
					'title' => __('Quote available email to client', 'easy-invoice'),
					'type' => 'title',
					'desc' => '',
					'id' => 'easy_invoice_email_quote_available',
				),
				array(
					'title' => __('Email Subject', 'easy-invoice'),
					'id' => 'easy_invoice_email_quote_available_email_subject_to_client',
					'type' => 'text',
					'default' => DefaultEmailMessages::get_quote_available_subject_to_client(),
				),
				array(
					'title' => __('Email Message', 'easy-invoice'),
					'id' => 'easy_invoice_email_quote_available_email_message_to_client',
					'type' => 'textarea',
					'editor' => true,
					'allow-html' => true,
					'editor_settings' => array(
						'tinymce' => array(
							'toolbar1' => 'bold,italic,underline,link,unlink,undo,redo',
						),
					),
					'allowed_html' => self::email_content_allowed_html(),
					'custom_attributes' => array(
						'size' => 70
					),
					'default' => DefaultEmailMessages::get_quote_available_message_to_client(),
				),
				array(
					'title' => '',
					'id' => 'easy_invoice_smart_tags',
					'type' => 'content',
					'content' => easy_invoice_get_available_invoice_smart_tags_html_content(),
					'allowed_html' => array(
						'div' => array('style'),
						'p' => array(),
						'span' => array(),
						'strong' => array(),
						'h2' => array(),
					),
				),
				array(
					'type' => 'sectionend',
					'id' => 'easy_invoice_email_invoice_available',
				),

			);

		} else if ('payment_received' === $current_section) {
			$settings = array(
				array(
					'title' => __('Email to client when they make payment', 'easy-invoice'),
					'type' => 'title',
					'desc' => '',
					'id' => 'easy_invoice_email_payment_received',
				),
				array(
					'title' => __('Email Subject', 'easy-invoice'),
					'id' => 'easy_invoice_email_payment_received_email_subject_to_client',
					'type' => 'text',
					'default' => DefaultEmailMessages::get_payment_received_subject_to_client(),
				),
				array(
					'title' => __('Email Message', 'easy-invoice'),
					'id' => 'easy_invoice_email_payment_received_email_message_to_client',
					'type' => 'textarea',
					'editor' => true,
					'allow-html' => true,
					'editor_settings' => array(
						'tinymce' => array(
							'toolbar1' => 'bold,italic,underline,link,unlink,undo,redo',
						),
					),
					'allowed_html' => self::email_content_allowed_html(),
					'custom_attributes' => array(
						'size' => 70
					),
					'default' => DefaultEmailMessages::get_payment_received_message_to_client(),
				),
				array(
					'title' => '',
					'id' => 'easy_invoice_smart_tags',
					'type' => 'content',
					'content' => easy_invoice_get_available_invoice_smart_tags_html_content(),
					'allowed_html' => array(
						'div' => array('style'),
						'p' => array(),
						'span' => array(),
						'strong' => array(),
						'h2' => array(),
					),
				),
				array(
					'type' => 'sectionend',
					'id' => 'easy_invoice_email_invoice_available',
				),

			);

		} else {

			$settings = array(
				array(
					'title' => __('Email Options', 'easy-invoice'),
					'type' => 'title',
					'desc' => '',
					'id' => 'easy_invoice_email_options',
				),
				array(
					'title' => __('From Email Address', 'easy-invoice'),
					'id' => 'easy_invoice_email_from_address',
					'type' => 'text',
					'default' => get_option('admin_email'),
				),

				array(
					'title' => __('From Name', 'easy-invoice'),
					'id' => 'easy_invoice_email_from_name',
					'type' => 'text',
					'default' => get_bloginfo('name')
				),

				array(
					'title' => __('Send a copy to admin?', 'easy-invoice'),
					'id' => 'easy_invoice_email_send_admin_copy',
					'type' => 'checkbox',
					'default' => 'yes',
					'desc' => 'Send a copy of client email to admin.'
				),

				array(
					'type' => 'sectionend',
					'id' => 'easy_invoice_email_options',
				),

			);

		}


		return apply_filters('easy_invoice_get_settings_' . $this->id, $settings, $current_section);
	}
}
