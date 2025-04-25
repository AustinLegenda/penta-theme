<?php

namespace MatrixAddons\EasyInvoice;

class Assets
{
	public static function init()
	{
		$self = new self();

		add_action('easy_invoice_head', [$self, 'scripts']);
	}

	public function scripts()
	{

		if (!is_singular(Constant::INVOICE_POST_TYPE) && !is_singular(Constant::QUOTE_POST_TYPE)) {
			return;
		}

		wp_register_style(
			'easy-invoice-style',
			EASY_INVOICE_ASSETS_URI . 'css/easy-invoice.css',
			array(),
			EASY_INVOICE_VERSION
		);
		wp_register_script(
			'easy-invoice-common-script',
			EASY_INVOICE_ASSETS_URI . 'js/common.js',
			array('jquery'),
			EASY_INVOICE_VERSION
		);

		wp_register_script(
			'easy-invoice-script',
			EASY_INVOICE_ASSETS_URI . 'js/easy-invoice.js',
			array('jquery', 'easy-invoice-common-script'),
			EASY_INVOICE_VERSION
		);

		wp_localize_script('easy-invoice-script', 'easy_invoice', $this->get_localize_data());


		wp_print_styles(array('easy-invoice-style'));

		wp_print_scripts(array('easy-invoice-script'));

		do_action('easy_invoice_frontend_script_loaded');

	}

	public function get_localize_data()
	{

		return [
			'ajax_url' => admin_url('admin-ajax.php'),
			'download_as_pdf_action' => 'download_as_pdf',
			'download_as_pdf_nonce' => wp_create_nonce('download_as_pdf'),
			'invoice_id' => get_the_ID(),
			'quote_id' => get_the_ID(),
			'send_email_action' => 'send_email',
			'send_email_nonce' => wp_create_nonce('send_email'),
			'proceed_to_payment_action' => 'proceed_to_payment',
			'proceed_to_payment_nonce' => wp_create_nonce('proceed_to_payment'),
		];
	}
}
