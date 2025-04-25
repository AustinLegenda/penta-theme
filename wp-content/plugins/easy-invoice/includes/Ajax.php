<?php

namespace MatrixAddons\EasyInvoice;

use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

class Ajax
{
	public static function init()
	{
		$self = new self();
		add_action('wp_ajax_send_email', array($self, 'send_email'));
		add_action('wp_ajax_nopriv_send_email', array($self, 'send_email'));


		add_action('wp_ajax_accept_quote', array($self, 'accept_quote'));
		add_action('wp_ajax_nopriv_accept_quote', array($self, 'accept_quote'));

		add_action('wp_ajax_decline_quote', array($self, 'decline_quote'));
		add_action('wp_ajax_nopriv_decline_quote', array($self, 'decline_quote'));

		add_action('wp_ajax_proceed_to_payment', array($self, 'proceed_to_payment'));
		add_action('wp_ajax_nopriv_proceed_to_payment', array($self, 'proceed_to_payment'));
	}

	public function send_email()
	{
		if (!current_user_can('manage_options')) {

			wp_send_json_error('Permission denied');

			exit;
		}
		$action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		$object_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

		$post_type = get_post_type($object_id);

		if (!wp_verify_nonce($nonce, $action) || $object_id < 0 || ($post_type !== Constant::INVOICE_POST_TYPE && $post_type !== Constant::QUOTE_POST_TYPE)) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Something went wrong, please try again.'));

		}

		if ($post_type === Constant::QUOTE_POST_TYPE) {

			global $ei_quote;

			$ei_quote = new QuoteRepository($object_id);

			$client_email = $ei_quote->get_client_email();

		} else {

			global $ei_invoice;

			$ei_invoice = new InvoiceRepository($object_id);

			$client_email = $ei_invoice->get_client_email();
		}


		if ($client_email == '') {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Client email address not found.'));

			exit;
		}

		if ($post_type === Constant::QUOTE_POST_TYPE) {

			do_action('easy_invoice_on_quote_available', $object_id);

		} else {

			do_action('easy_invoice_on_invoice_available', $object_id);

		}


		wp_send_json_success('Email successfully send.');

	}


	public function proceed_to_payment()
	{

		$action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;

		if (!wp_verify_nonce($nonce, $action) || $invoice_id < 0 || get_post_type($invoice_id) !== 'easy-invoice') {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Something went wrong, please try again.'));

			exit;
		}

		if (!easy_invoice_enable_proceed_to_payment($invoice_id)) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Unable to proceed payment for this invoice'));

			exit;
		}
		global $ei_invoice;

		$ei_invoice = new InvoiceRepository($invoice_id);

		$client_email = $ei_invoice->get_client_email();

		if ($client_email == '') {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Client email not found.'));

			exit;
		}

		do_action('easy_invoice_payment_page', $invoice_id);

		exit;

	}

	public function accept_quote()
	{

		$action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		$quote_id = isset($_POST['quote_id']) ? absint($_POST['quote_id']) : 0;

		if (!wp_verify_nonce($nonce, $action) || $quote_id < 0 || get_post_type($quote_id) !== Constant::QUOTE_POST_TYPE) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Something went wrong, please try again.'));

			exit;
		}
		$quote_accept = easy_invoice_quote_accept_quote_button();

		if (!$quote_accept) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Unable to accept this quote. Please contact site administrator.'));

			exit;
		}
		if (!easy_invoice_quote_can_accept($quote_id)) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'You can\'t accept this quote. Please contact site administrator'));

			exit;
		}
		easy_invoice_change_quote_to_invoice($quote_id);

		easy_invoice_update_quote_status($quote_id, 'accepted');


		easy_invoice_load_template('parts.message-page',
			array(
				'message_text' => easy_invoice_accepted_quote_message(),
				'message_title' => __('Success', 'easy-invoice')
			)
		);
		exit;
	}

	public function decline_quote()
	{
		$action = isset($_POST['action']) ? sanitize_text_field($_POST['action']) : '';

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		$quote_id = isset($_POST['quote_id']) ? absint($_POST['quote_id']) : 0;

		$decline_reason = isset($_POST['decline_reason']) ? sanitize_text_field($_POST['decline_reason']) : '';

		if (!wp_verify_nonce($nonce, $action) || $quote_id < 0 || get_post_type($quote_id) !== Constant::QUOTE_POST_TYPE) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'Something went wrong, please try again.'));

			exit;
		}
		$decline_reason_required = easy_invoice_decline_quote_reason_required();

		if ($decline_reason_required && $decline_reason == '') {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'You have to provide decline reason to decline this quote.'));

			exit;
		}

		if (!easy_invoice_quote_can_decline($quote_id)) {

			easy_invoice_load_template('parts.error-page', array('error_text' => 'You can\'t decline this quote.'));

			exit;
		}
		$decline_reason = $decline_reason == '' ? 'N/A' : $decline_reason;

		$log_text = 'Quote was declined. Reason: ' . $decline_reason;

		easy_invoice_add_quote_log($quote_id, $log_text);

		easy_invoice_update_quote_status($quote_id, 'declined');

		easy_invoice_load_template('parts.message-page',
			array(
				'message_text' => easy_invoice_declined_quote_message(),
				'message_title' => __('Declined', 'easy-invoice')
			)
		);
		exit;
	}


}
