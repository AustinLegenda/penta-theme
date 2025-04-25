<?php

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

if (!function_exists('easy_invoice_load_admin_template')) {

	function easy_invoice_load_admin_template($template = null, $variables = array(), $include_once = false)
	{
		$variables = (array)$variables;

		$variables = apply_filters('easy_invoice_load_admin_template_variables', $variables);

		extract($variables);

		$isLoad = apply_filters('should_easy_invoice_load_admin_template', true, $template, $variables);

		if (!$isLoad) {

			return;
		}

		do_action('easy_invoice_load_admin_template_before', $template, $variables);

		if ($include_once) {

			include_once easy_invoice_get_admin_template($template);

		} else {

			include easy_invoice_get_admin_template($template);
		}
		do_action('easy_invoice_load_admin_template_after', $template, $variables);
	}
}
if (!function_exists('easy_invoice_get_admin_template')) {

	function easy_invoice_get_admin_template($template = null)
	{
		if (!$template) {
			return false;
		}
		$template = str_replace('.', DIRECTORY_SEPARATOR, $template);

		$template_location = EASY_INVOICE_ABSPATH . "includes/Admin/Templates/{$template}.php";

		if (!file_exists($template_location)) {

			echo '<div class="easy_invoice-notice-warning"> ' . __(sprintf('The file you are trying to load is not exists in your theme or easy_invoice plugins location, if you are a developer and extending easy_invoice plugin, please create a php file at location %s ', "<code>{$template_location}</code>"), 'easy-invoice') . ' </div>';
		}


		return apply_filters('easy_invoice_get_admin_template_path', $template_location, $template);
	}
}


if (!function_exists('easy_invoice_load_template')) {
	function easy_invoice_load_template($template = null, $variables = array(), $easy_invoice_pro = false)
	{
		$variables = (array)$variables;
		$variables = apply_filters('get_easy_invoice_load_template_variables', $variables);

		extract($variables);

		$isLoad = apply_filters('should_easy_invoice_load_template', true, $template, $variables);
		if (!$isLoad) {
			return;
		}

		do_action('easy_invoice_load_template_before', $template, $variables);
		include easy_invoice_get_template($template, $easy_invoice_pro);
		do_action('easy_invoice_load_template_after', $template, $variables);
	}
}

if (!function_exists('easy_invoice_get_template')) {
	function easy_invoice_get_template($template = null, $easy_invoice_pro = false)
	{
		if (!$template) {
			return false;
		}
		$template = str_replace('.', DIRECTORY_SEPARATOR, $template);

		/**
		 * Get template first from child-theme if exists
		 * If child theme not exists, then get template from parent theme
		 */
		$template_location = trailingslashit(get_stylesheet_directory()) . "easy-invoice/{$template}.php";
		if (!file_exists($template_location)) {
			$template_location = trailingslashit(get_template_directory()) . "easy-invoice/{$template}.php";
		}
		$file_in_theme = $template_location;

		if (!file_exists($template_location)) {

			$template_location = trailingslashit(EASY_INVOICE_ABSPATH) . "templates/{$template}.php";

			if (!file_exists($template_location)) {
				echo '<div class="easy-invoice-notice-warning"> ' . __(sprintf('The file you are trying to load is not exists in your theme or easy invoice plugins location, if you are a developer and extending easy invoice plugin, please create a php file at location %s ', "<code>{$file_in_theme}</code>"), 'easy-invoice') . ' </div>';
			}
		}

		return apply_filters('easy_invoice_get_template_path', $template_location, $template);
	}
}

if (!function_exists('easy_invoice_smart_tags_for_invoice')) {

	function easy_invoice_smart_tags_for_invoice($object_id)
	{
		$invoice = new InvoiceRepository($object_id);

		$quote = new QuoteRepository($object_id);

		$post_title = get_the_title($object_id); //added

		$today = current_time('timestamp');

		$due_and_valid_date = get_post_type($object_id) != Constant::QUOTE_POST_TYPE ? $invoice->get_due_date() : $quote->get_valid_until();

		$due_amount = get_post_type($object_id) != Constant::QUOTE_POST_TYPE ? $invoice->get_due_amount() : $quote->get_due_amount();

		$client_name = get_post_type($object_id) != Constant::QUOTE_POST_TYPE ? $invoice->get_client_name() : $quote->get_client_name();

		$due_date = $due_and_valid_date !== '' ? strtotime($due_and_valid_date) : 0;

		$is_was = $today > $due_date && $due_date !== 0 ? __('was', 'easy-invoice') : __('is', 'easy-invoice');

		return apply_filters(
			'easy_invoice_smart_tags_for_invoice',
			array(
				'home_url' => get_home_url(),
				'invoice_number' => $invoice->get_invoice_number(),
				'quote_number' => $quote->get_quote_number(),
				'quote_invoice_title' => $post_title, // added
				'client_name' => $client_name,
				'invoice_permalink' => get_permalink($object_id),
				'quote_permalink' => get_permalink($object_id),
				'due_date' => $due_and_valid_date,
				'total_due' => easy_invoice_get_price($due_amount, '', $object_id),
				'current_date' => date_i18n(get_option('date_format'), (int)current_time('timestamp')),
				'is_was' => $is_was,
				
			),
			$object_id
		);
	}
}
if (!function_exists('easy_invoice_available_smart_tags_for_invoice')) {
	function easy_invoice_available_smart_tags_for_invoice()
	{
		return apply_filters(
			'easy_invoice_available_smart_tags_for_invoice',
			array(
				'home_url' => __("Website URL", 'easy-invoice'),
				'invoice_number' => __("Invoice number", 'easy-invoice'),
				'quote_number' => __("Quote number", 'easy-invoice'),
				'quote_invoice_title' => __("Title of the invoice or quote", 'easy-invoice'), // Added
				'client_name' => __("Name of the client on invoice", 'easy-invoice'),
				'invoice_permalink' => __("Permalink of the invoice", 'easy-invoice'),
				'quote_permalink' => __("Permalink of the quote", 'easy-invoice'),
				'due_date' => __("Due date of the invoice", 'easy-invoice'),
				'total_due' => __("Total due amount of the invoice with currency code", 'easy-invoice'),
				'current_date' => __("Current date", 'easy-invoice'),
				'is_was' => __("If due date of the invoice is past", 'easy-invoice'),
			)
		);
	}
}
if (!function_exists('easy_invoice_maybe_parse_smart_tags')) {

	function easy_invoice_maybe_parse_smart_tags($all_smart_tags, $content)
	{

		foreach ($all_smart_tags as $tag => $smart_tag_value) {

			$smart_tag = "{{" . $tag . "}}";

			$content = str_replace($smart_tag, $smart_tag_value, $content);
		}

		return $content;
	}
}

if (!function_exists('easy_invoice_print_html_text')) {
	function easy_invoice_print_html_text($text, $allowed_html = array())
	{
		$default_args = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'style' => array()
			),
			'br' => array(
				'style' => array()
			),
			'em' => array(
				'style' => array()
			),
			'i' => array(
				'style' => array()
			),
			'strong' => array(
				'style' => array()
			),
			'b' => array(
				'style' => array()
			),
			'hr' => array('style' => array()),
			'p' => array('style' => array()),
			'h1' => array('style' => array()),
			'h2' => array('style' => array()),
			'h3' => array('style' => array()),
			'h4' => array('style' => array()),
			'h5' => array('style' => array()),
			'h6' => array('style' => array())
		);

		$allowed = wp_parse_args($allowed_html, $default_args);

		echo wp_kses($text, $allowed);

	}
}

if (!function_exists('easy_invoice_redirect_with_error')) {
	function easy_invoice_redirect_with_error($error_code, $error_message, $permalink = '')
	{
		global $easy_invoice_error;

		$easy_invoice_error = new WP_Error($error_code, $error_message);

		if ($permalink != '') {

			$permalink = add_query_arg('ei_payment_process', 1, $permalink);

			wp_redirect($permalink);

			exit;
		}

	}
}

if (!function_exists('easy_invoice_get_requested')) {
	function easy_invoice_get_requested($request_id)
	{
		global $easy_invoice_requested;

		if (!is_array($easy_invoice_requested)) {
			return '';
		}
		if (isset($easy_invoice_requested[$request_id])) {
			return $easy_invoice_requested[$request_id];
		}
	}
}

if (!function_exists('easy_invoice_set_requested')) {
	function easy_invoice_set_requested($request_id, $data = '')
	{
		global $easy_invoice_requested;

		$easy_invoice_requested[sanitize_text_field($request_id)] = sanitize_text_field($data);
	}
}
if (!function_exists('easy_invoice_get_attachment_image_url')) {
	function easy_invoice_get_attachment_image_url($image_id)
	{
		$src = $image_id > 0 ? wp_get_attachment_image_url($image_id, 'full') : '';

		return apply_filters('easy_invoice_get_attachment_image_url', $src, $image_id);
	}
}

if (!function_exists('easy_invoice_get_available_invoice_smart_tags_html_content')) {
	function easy_invoice_get_available_invoice_smart_tags_html_content()
	{
		$tags = easy_invoice_available_smart_tags_for_invoice();

		ob_start();

		echo '<div style="padding:15px; background:#f9f9f9;">';

		echo '<h2>' . __('Smart Tags for Emails', 'easy-invoice') . '</h2>';

		foreach ($tags as $tag_id => $tag_label) {

			echo '<p><strong>{{' . esc_html($tag_id) . '}}</strong>: <span>' . esc_html($tag_label) . '</span></p>';
		}

		echo '</div>';

		return ob_get_clean();
	}

}
