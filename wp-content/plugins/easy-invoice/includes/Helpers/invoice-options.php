<?php

use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;

if (!function_exists('easy_invoice_show_hide_adjust')) {
	function easy_invoice_show_hide_adjust()
	{
		return get_option('easy_invoice_show_hide_adjust', 'yes') === 'yes';
	}
}
if (!function_exists('easy_invoice_footer_text')) {
	function easy_invoice_footer_text()
	{
		return get_option('easy_invoice_footer_text', 'Thanks for choosing <a href="' . esc_url(site_url()) . '">' . esc_html(get_bloginfo('name')) . '</a> | <a href="mailto:' . esc_attr(get_option('admin_email')) . '">' . esc_html(get_option('admin_email')) . '</a>');
	}
}

if (!function_exists('easy_invoice_terms_conditions')) {
	function easy_invoice_terms_conditions($invoice_id = '')
	{
		$invoice_id = $invoice_id === '' ? get_the_ID() : absint($invoice_id);

		$invoice = new InvoiceRepository($invoice_id);

		return $invoice->get_terms_conditions();
	}
}

if (!function_exists('easy_invoice_business_address')) {
	function easy_invoice_business_address()
	{
		return get_option('easy_invoice_business_address', 'Your main address
123 Somewhere Street
Your City Address 12345');
	}
}

if (!function_exists('easy_invoice_enable_proceed_to_payment')) {

	function easy_invoice_enable_proceed_to_payment($invoice_id = '')
	{
		$invoice_id = $invoice_id === '' ? get_the_ID() : absint($invoice_id);

		$invoice = new InvoiceRepository($invoice_id);

		if (in_array($invoice->get_invoice_status(), array('paid', 'cancelled'))) {

			return false;
		}
		return get_option('easy_invoice_enable_proceed_to_payment', 'yes') === 'yes';
	}
}

if (!function_exists('easy_invoice_proceed_to_payment_button_link')) {
	function easy_invoice_proceed_to_payment_button_link()
	{
		return get_option('easy_invoice_proceed_to_payment_button_link', '#');
	}
}

if (!function_exists('easy_invoice_get_predefined_line_items')) {
	function easy_invoice_get_predefined_line_items()
	{

		$pre_defined = get_option('easy_invoice_pre_defined_line_items', '1 | Software Development | 150 | Development cost
1 | Banner Design | 30 | Homepage Banner for the website');

		$items = explode("\n", $pre_defined);

		$items = array_filter($items); // remove any empty items

		$price_array[] = [
			'text' => __("Add a pre-defined line item", 'easy-invoice'),
			'data-qty' => '',
			'data-price' => '',
			'data-desc' => '',
		];
		if ($items) :

			$index = 1;
			foreach ($items as $item) {

				$item_array = explode('|', $item);
				$qty = isset($item_array[0]) ? trim($item_array[0]) : '';
				$title = isset($item_array[1]) ? trim($item_array[1]) : '';
				$price = isset($item_array[2]) ? trim($item_array[2]) : '';
				$desc = isset($item_array[3]) ? trim($item_array[3]) : '';

				$single_item_array = [
					'text' => $title,
					'data-qty' => $qty,
					'data-price' => $price,
					'data-desc' => $desc,
				];


				$price_array[$index] = $single_item_array;

				$index++;
			}

		endif;

		return $price_array;
	}
}
