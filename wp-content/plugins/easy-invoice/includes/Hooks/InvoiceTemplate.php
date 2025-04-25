<?php

namespace MatrixAddons\EasyInvoice\Hooks;

use MatrixAddons\EasyInvoice\Constant;

class InvoiceTemplate
{
	public function __construct()
	{

		add_action('template_redirect', array($this, 'invoice_hooks'));

	}

	public function invoice_hooks()
	{
		if (get_post_type(get_the_ID()) !== Constant::INVOICE_POST_TYPE) {
			return;
		}

		add_action('easy_invoice_before_container', array($this, 'top_bar'));

		add_action('easy_invoice_content', array($this, 'header'), 15);

		add_action('easy_invoice_content', array($this, 'after_header'), 20);

		add_action('easy_invoice_content', array($this, 'before_items_1'), 25);

		add_action('easy_invoice_content', array($this, 'before_items_2'), 30);

		add_action('easy_invoice_content', array($this, 'before_items_3'), 35);

		add_action('easy_invoice_content', array($this, 'items'), 40);

		add_action('easy_invoice_content', array($this, 'after_items'), 45);

		add_action('easy_invoice_content', array($this, 'before_footer'), 50);

		add_action('easy_invoice_content', array($this, 'footer'), 55);

		add_action('easy_invoice_footer', array($this, 'payment_popup'));


	}

	public function top_bar()
	{
		easy_invoice_load_template('invoice.top-bar');
	}

	public function header()
	{
		$business_logo = absint(get_option('easy_invoice_business_logo', ''));

		$logo_src = $business_logo > 0 ? easy_invoice_get_attachment_image_url($business_logo) : '';

		easy_invoice_load_template('parts.header', array(
			'logo_src' => $logo_src,
			
		));
	}

	public function after_header()
	{
		echo '<div class="easy-invoice-content-area">';
	}

	public function before_items_1()
	{
		easy_invoice_load_template('parts.before-items-1', array(
			'details_data' => easy_invoice_get_invoice_details_data(),
				'title' => easy_invoice_get_invoice_label()
		));
	}

	public function before_items_2()
	{
		easy_invoice_load_template('parts.before-items-2', array(
			'details_data' => easy_invoice_get_invoice_details_data()
		));
	}

	public function before_items_3()
	{
		$description = easy_invoice_get_invoice_description();

		if ($description) {

			easy_invoice_load_template('parts.before-items-3', array('description' => $description));
		}
	}

	public function items()
	{
		easy_invoice_load_template('parts.items');
	}

	public function after_items()
	{
		$terms = easy_invoice_terms_conditions();

		easy_invoice_load_template('parts.after-items', array('terms' => $terms));
	}

	public function before_footer()
	{
		echo '</div><!-- .easy-invoice-content-area-->';
	}

	public function footer()
	{

		easy_invoice_load_template('parts.footer', array(
			'footer_text' => easy_invoice_footer_text(),
			'page' => easy_invoice_get_text('page')
		));
	}

	public function payment_popup()
	{
		global $easy_invoice_error;

		$error = '';

		if (!is_wp_error($easy_invoice_error)) {
			return;
		}

		$error = $easy_invoice_error->get_error_message();

		echo '<div class="ei-popup-page-main-container active">';

		easy_invoice_load_template('invoice.payment-page', array('invoice_id' => get_the_ID(), 'error' => $error));

		echo '</div>';
	}

}
