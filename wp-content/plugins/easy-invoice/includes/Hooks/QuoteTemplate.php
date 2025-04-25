<?php

namespace MatrixAddons\EasyInvoice\Hooks;

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

class QuoteTemplate
{
	public function __construct()
	{

		add_action('template_redirect', array($this, 'quote_hooks'));
	}

	public function quote_hooks()
	{
		if (get_post_type(get_the_ID()) !== Constant::QUOTE_POST_TYPE) {
			return;
		}

		add_action('easy_invoice_before_container', array($this, 'top_bar'));

		add_action('easy_invoice_content', array($this, 'header'), 15);

		add_action('easy_invoice_content', array($this, 'after_header'), 20); //is here

		add_action('easy_invoice_content', array($this, 'before_items_1'), 25);

		//add_action('easy_invoice_content', array($this, 'contact_items'), 25);

		add_action('easy_invoice_content', array($this, 'before_items_2'), 30);

		add_action('easy_invoice_content', array($this, 'before_items_3'), 35);

		add_action('easy_invoice_content', array($this, 'items'), 40);

		add_action('easy_invoice_content', array($this, 'after_items'), 45);

		add_action('easy_invoice_content', array($this, 'before_footer'), 50);

		add_action('easy_invoice_content', array($this, 'footer'), 55);

		add_action('easy_invoice_footer', array($this, 'accept_decline_popup'));
	}

	public function top_bar()
	{
		$quote_id = get_the_ID();

		$can_quote_accept = easy_invoice_quote_can_accept($quote_id);

		$can_quote_decline = easy_invoice_quote_can_decline($quote_id);

		easy_invoice_load_template('quote.top-bar', array(
			'accept_quote_class' => !$can_quote_accept ? 'disabled' : '',
			'decline_quote_class' => !$can_quote_decline ? 'disabled' : '',
			'accept_quote_title' => !$can_quote_accept ? __('You can\'t accept this quote. Please contact site administrator', 'easy-invoice') : '',
			'decline_quote_title' => !$can_quote_decline ? __('You can\'t decline this quote. Please contact site administrator', 'easy-invoice') : '',

		));
	}

	public function header()
	{
		$business_logo = absint(get_option('easy_invoice_business_logo', ''));

		$logo_src = $business_logo > 0 ? easy_invoice_get_attachment_image_url($business_logo) : '';

		easy_invoice_load_template('parts.header', array(
			'logo_src' => $logo_src,
			'details_data' => easy_invoice_get_quote_details_data() //load details data here instead
		));
	}

	public function after_header()
	{
		echo '<div class="easy-invoice-content-area">';
	}

	//load contact info together instead
	public function contact_items()
	{
		easy_invoice_load_template('parts.contact-items');
	}

	public function before_items_1() //before-items-1
	{
		easy_invoice_load_template('parts.before-items-1', array(
			'title' => easy_invoice_get_quote_label(),
		));
	}

	public function before_items_2() //before-items-2
	{
		easy_invoice_load_template('parts.before-items-2', array(
			'details_data' => easy_invoice_get_quote_details_data()
		));
	}

	public function before_items_3() //before-items-3
	{
		$description = easy_invoice_get_quote_description();

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
			'footer_text' => easy_invoice_quote_footer_text(),
			'page' => easy_invoice_get_text('page')
		));
	}

	public function accept_decline_popup()
	{

		$accept_quote_button = easy_invoice_quote_accept_quote_button();

		if (!$accept_quote_button) {

			return;
		}

		global $easy_invoice_error;

		$error = '';

		if (is_wp_error($easy_invoice_error)) {

			$error = $easy_invoice_error->get_error_message();
		}
		$quote_id = get_the_ID();

		$quote = new QuoteRepository($quote_id);

		$quote_amount = $quote->get_due_amount();

		$quote_number = $quote->get_quote_number();

		$is_required = easy_invoice_decline_quote_reason_required();

		echo '<div class="ei-popup-page-main-container accept-quote">';

		easy_invoice_load_template('quote.accept-quote', array('quote_id' => $quote_id, 'error' => $error, 'quote_number' => $quote_number, 'quote_amount' => $quote_amount));

		echo '</div>';

		echo '<div class="ei-popup-page-main-container decline-quote">';

		easy_invoice_load_template(
			'quote.decline-quote',
			array(
				'quote_id' => $quote_id,
				'error' => $error,
				'quote_number' => $quote_number,
				'quote_amount' => $quote_amount,
				'is_required_reason' => $is_required
			)
		);


		echo '</div>';
	}
}
