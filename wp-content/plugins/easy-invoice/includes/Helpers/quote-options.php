<?php
if (!function_exists('easy_invoice_quote_show_hide_adjust')) {
	function easy_invoice_quote_show_hide_adjust()
	{
		return get_option('easy_invoice_quote_show_hide_adjust', 'yes') === 'yes';
	}
}
if (!function_exists('easy_invoice_quote_footer_text')) {
	function easy_invoice_quote_footer_text()
	{
		return get_option('easy_invoice_quote_footer_text', 'Thanks for choosing <a href="' . esc_url(site_url()) . '">' . esc_html(get_bloginfo('name')) . '</a> | <a href="mailto:' . esc_attr(get_option('admin_email')) . '">' . esc_html(get_option('admin_email')) . '</a>');
	}
}
if (!function_exists('easy_invoice_accept_quote_text')) {
	function easy_invoice_accept_quote_text()
	{
		return get_option('easy_invoice_accept_quote_text', 'Important: When you accept this Quote, an Invoice will be created automatically. This will form a legally binding contract.');
	}
}
if (!function_exists('easy_invoice_accepted_quote_message')) {
	function easy_invoice_accepted_quote_message()
	{
		return get_option('easy_invoice_accepted_quote_message', 'You\'ve confirmed the Quote.We\'ll get in touch with you shortly.');
	}
}
if (!function_exists('easy_invoice_quote_accept_quote_button')) {
	function easy_invoice_quote_accept_quote_button()
	{
		return get_option('easy_invoice_quote_accept_quote_button', 'yes') === 'yes';
	}
}

if (!function_exists('easy_invoice_decline_quote_reason_required')) {
	function easy_invoice_decline_quote_reason_required()
	{
		return get_option('easy_invoice_decline_quote_reason_required', 'yes') === 'no';
	}
}
if (!function_exists('easy_invoice_declined_quote_message')) {
	function easy_invoice_declined_quote_message()
	{
		return get_option('easy_invoice_declined_quote_message', 'You\'ve declined the Quote.We\'ll get in touch with you shortly');
	}
}
