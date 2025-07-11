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

//lei
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
//lei predefined sections

if (!function_exists('easy_invoice_get_predefined_section_titles')) {
	function easy_invoice_get_predefined_section_titles()
	{
		$headers_raw = get_option('easy_invoice_pre_defined_section_titles', "Production\nDesign\nPost-Production");
		$lines = array_filter(array_map('trim', explode("\n", $headers_raw)));

		$options = [
			['text' => __('Add a pre-defined section title', 'easy-invoice')],
		];

		foreach ($lines as $line) {
			$options[] = [
				'text' => $line,
				'data-title' => $line,
			];
		}

		return $options;
	}
}
//lei description templates
if (!function_exists('easy_invoice_get_description_templates')) {
	function easy_invoice_get_description_templates()
	{
		$raw = get_option('easy_invoice_description_templates', '');

		error_log("[Templates Raw Option]\n" . $raw);

		$lines = preg_split('/\r?\n/', trim($raw));
		$templates = [];

		foreach ($lines as $line) {
			if (strpos($line, '|') === false) {
				error_log("[Templates Skip] Malformed line: $line");
				continue;
			}

			list($title, $html) = explode('|', $line, 2);
			$key = sanitize_title($title);

			$templates[$key] = [
				'label'   => trim($title),
				'content' => trim($html),
			];
		}

		error_log("[Templates Parsed Keys] " . implode(', ', array_keys($templates)));

		return $templates;
	}
}
