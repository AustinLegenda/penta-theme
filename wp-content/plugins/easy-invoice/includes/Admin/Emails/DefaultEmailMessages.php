<?php

namespace MatrixAddons\EasyInvoice\Admin\Emails;

class DefaultEmailMessages
{
	public static function get_invoice_available_subject_to_client()
	{
		return __(
			'New invoice {{invoice_number}} available',
			'easy-invoice'
		);
	}

	public static function get_invoice_available_message_to_client()
	{

		return __(
			'Hi {{client_name}},
			 You have a new invoice available ( {{invoice_number}} ) which can be viewed at <a href="{{invoice_permalink}}" target="_blank">{{invoice_number}}</a>.',
			'easy-invoice'
		);
	}

	public static function get_quote_available_subject_to_client()
	{
		return __(
			'New quote {{quote_number}} available',
			'easy-invoice'
		);
	}

	public static function get_quote_available_message_to_client()
	{

		return __(
			'Hi {{client_name}},
			 You have a new quote available ( {{quote_number}} ) which can be viewed at <a href="{{quote_permalink}}" target="_blank">{{quote_number}}</a>.',
			'easy-invoice'
		);
	}

	public static function get_invoice_available_subject_to_admin()
	{
		return __(
			'The New invoice {{invoice_number}} available for {{client_name}}',
			'easy-invoice'
		);
	}

	public static function get_invoice_available_message_to_admin()
	{
		return __(
			'Hi Admin,
			 The New invoice ( {{invoice_number}} ) is ready for {{client_name}} which can be viewed at <a href="{{invoice_permalink}}" target="_blank">{{invoice_number}}</a>.',
			'easy-invoice'
		);
	}

	public static function get_quote_available_subject_to_admin()
	{
		return __(
			'The New quote {{quote_number}} available for {{client_name}}',
			'easy-invoice'
		);
	}

	public static function get_quote_available_message_to_admin()
	{
		return __(
			'Hi Admin,
			 The New quote ( {{quote_number}} ) is ready for {{client_name}} which can be viewed at <a href="{{quote_permalink}}" target="_blank">{{quote_number}}</a>.',
			'easy-invoice'
		);
	}

	//invoice paid
	public static function get_payment_received_subject_to_client()
	{
		return __(
			'Thank you for the payment',
			'easy-invoice'
		);
	}

	public static function get_payment_received_message_to_client()
	{

		return __(
			'Hi {{client_name}},
			 Your recent payment for ( {{invoice_number}} ) has been successfully completed.',
			'easy-invoice'
		);
	}


	public static function get_payment_received_subject_to_admin()
	{
		return __(
			'{{invoice_number}} has been paid',
			'easy-invoice'
		);
	}

	public static function get_payment_received_message_to_admin()
	{

		return __(
			'Hi Admin,
			 Your have just received payment for invoice ( {{invoice_number}} ) from {{client_name}}.',
			'easy-invoice'
		);
	}

}
