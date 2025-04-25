<?php

namespace MatrixAddons\EasyInvoice;

use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Admin\Emails\AdminEmail;
use MatrixAddons\EasyInvoice\Admin\Emails\ClientEmail;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

class Email
{

	public function is_email_enabled()
	{
		return true;

	}

	public static function init()
	{
		$self = new self;

		if (!$self->is_email_enabled()) {
			return;
		}

		// Before Email & After Email Hooks
		add_action('easy_invoice_email_send_before', array($self, 'email_before'));
		add_action('easy_invoice_email_send_after', array($self, 'email_after'));


		add_action('easy_invoice_on_invoice_available', array($self, 'invoice_available_email'), 10);
		add_action('easy_invoice_on_quote_available', array($self, 'quote_available_email'), 10);

		add_action('easy_invoice_after_invoice_status_change', array($self, 'invoice_status_change'), 10, 2);
	}

	public function email_before()
	{
		add_filter('wp_mail_from', array($this, 'sender_email'));
		add_filter('wp_mail_from_name', array($this, 'sender_name'));
	}


	public function email_after()
	{
		remove_filter('wp_mail_from', array($this, 'sender_email'));
		remove_filter('wp_mail_from_name', array($this, 'sender_name'));
	}

	public function sender_email()
	{
		return get_option('easy_invoice_email_from_address', 'wordpress@' . site_url());
	}

	public function reply_to_email()
	{
		return get_option('admin_email');
	}


	public function sender_name()
	{
		return get_option('easy_invoice_email_from_name', esc_attr(get_bloginfo('name', 'display')));
	}

	public function invoice_available_email($invoice_id)
	{
		if (!$this->is_email_enabled()) {
			return;
		}

		/* @var $invoice_details InvoiceRepository */
		$invoice_details = $this->get_invoice_details($invoice_id);

		$client_email = $invoice_details->get_client_email();

		// end of User Parameters

		$easy_invoice_smart_tags = easy_invoice_smart_tags_for_invoice($invoice_id);

		if (!empty($client_email)) {

			$client_message = ClientEmail::get_invoice_available_message();

			$client_subject = ClientEmail::get_invoice_available_subject();

			$this->send(array($client_email), $client_subject, $client_message, $easy_invoice_smart_tags, array());
		}

		if (get_option('easy_invoice_email_send_admin_copy', 'yes') === 'yes') {

			$admin_message = AdminEmail::get_invoice_available_message();

			$admin_subject = AdminEmail::get_invoice_available_subject();

			$admin_emails = $this->get_admin_emails();

			$this->send($admin_emails, $admin_subject, $admin_message, $easy_invoice_smart_tags, array(), true);

		}
	}
	public function quote_available_email($quote_id)
	{
		if (!$this->is_email_enabled()) {
			return;
		}

		/* @var $invoice_details QuoteRepository */
		$quote_details = $this->get_quote_details($quote_id);

		$client_email = $quote_details->get_client_email();

		// end of User Parameters

		$easy_invoice_smart_tags = easy_invoice_smart_tags_for_invoice($quote_id);

		if (!empty($client_email)) {

			$client_message = ClientEmail::get_quote_available_message();

			$client_subject = ClientEmail::get_quote_available_subject();

			$this->send(array($client_email), $client_subject, $client_message, $easy_invoice_smart_tags, array());
		}

		if (get_option('easy_invoice_email_send_admin_copy', 'yes') === 'yes') {

			$admin_message = AdminEmail::get_quote_available_message();

			$admin_subject = AdminEmail::get_quote_available_subject();

			$admin_emails = $this->get_admin_emails();

			$this->send($admin_emails, $admin_subject, $admin_message, $easy_invoice_smart_tags, array(), true);

		}
	}

	public function invoice_status_change($params)
	{
		$invoice_id = $params['invoice_id'] ?? 0;

		$status = $params['status'] ?? '';

		if (!$this->is_email_enabled()) {
			return;
		}

		if ($status !== 'paid' || absint($invoice_id) < 1) {
			return;
		}
		/* @var $invoice_details InvoiceRepository */

		$invoice_details = $this->get_invoice_details($invoice_id);

		$client_email = $invoice_details->get_client_email();

		// end of User Parameters

		$easy_invoice_smart_tags = easy_invoice_smart_tags_for_invoice($invoice_id);

		if (!empty($client_email)) {

			$client_message = ClientEmail::get_payment_received_message();

			$client_subject = ClientEmail::get_payment_received_subject();

			$this->send(array($client_email), $client_subject, $client_message, $easy_invoice_smart_tags, array());
		}

		if (get_option('easy_invoice_email_send_admin_copy', 'yes') === 'yes') {

			$admin_message = AdminEmail::get_payment_received_message();

			$admin_subject = AdminEmail::get_payment_received_subject();

			$admin_emails = $this->get_admin_emails();

			$this->send($admin_emails, $admin_subject, $admin_message, $easy_invoice_smart_tags, array(), true);

		}

	}

	protected function get_admin_emails()
	{
		$admin_emails = apply_filters('easy_invoice_admin_email_recipient_lists', get_option('admin_email'));

		$admin_emails = str_replace(',', PHP_EOL, $admin_emails);

		$admin_emails_array = explode(PHP_EOL, $admin_emails);

		return array_map('trim', $admin_emails_array);
	}


	public function get_header($is_admin = false)
	{
		$header = '';

		if (!$is_admin) {

			$header = 'From: ' . $this->sender_name() . ' <' . $this->sender_email() . ">\r\n";
		}

		$header .= 'Reply-To: ' . $this->reply_to_email() . "\r\n";
		$header .= "Content-Type: text/html; charset=UTF-8\r\n";

		return $header;
	}


	public function send($emails, $subject, $message, $all_smart_tags = array(), $attachment = array(), $is_admin_email = false)
	{

		$message = easy_invoice_maybe_parse_smart_tags($all_smart_tags, $message);

		$subject = easy_invoice_maybe_parse_smart_tags($all_smart_tags, $subject);

		do_action('easy_invoice_email_send_before');


		foreach ($emails as $email) {

			wp_mail($email, $subject, $message, $this->get_header($is_admin_email));

		}
		do_action('easy_invoice_email_send_after');


	}


	public function get_invoice_details($invoice_id)
	{

		return new InvoiceRepository($invoice_id);
	}
	public function get_quote_details($quote_id)
	{

		return new QuoteRepository($quote_id);
	}


}


