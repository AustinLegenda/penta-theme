<?php

namespace MatrixAddons\EasyInvoice\Admin\Emails;

class ClientEmail
{

	public static function get_invoice_available_message()
	{
		return apply_filters(
			'easy_invoice_email_invoice_available_message_to_client',

			get_option('easy_invoice_email_invoice_available_email_message_to_client',
				DefaultEmailMessages::get_invoice_available_message_to_client()
			)
		);
	}

	public static function get_invoice_available_subject()
	{
		return apply_filters(
			'easy_invoice_email_invoice_available_subject_to_client',

			get_option('easy_invoice_email_invoice_available_email_subject_to_client',
				DefaultEmailMessages::get_invoice_available_subject_to_client()
			)
		);
	}
	public static function get_quote_available_message()
	{
		return apply_filters(
			'easy_invoice_email_quote_available_message_to_client',

			get_option('easy_invoice_email_quote_available_email_message_to_client',
				DefaultEmailMessages::get_quote_available_message_to_client()
			)
		);
	}

	public static function get_quote_available_subject()
	{
		return apply_filters(
			'easy_invoice_email_quote_available_subject_to_client',

			get_option('easy_invoice_email_quote_available_email_subject_to_client',
				DefaultEmailMessages::get_quote_available_subject_to_client()
			)
		);
	}

	public static function get_payment_received_message()
	{
		return apply_filters(
			'easy_invoice_email_payment_received_message_to_client',

			get_option('easy_invoice_email_payment_received_email_message_to_client',
				DefaultEmailMessages::get_payment_received_message_to_client()
			)
		);
	}

	public static function get_payment_received_subject()
	{
		return apply_filters(
			'easy_invoice_email_payment_received_subject_to_client',

			get_option('easy_invoice_email_payment_received_email_subject_to_client',
				DefaultEmailMessages::get_payment_received_subject_to_client()
			)
		);
	}

}
