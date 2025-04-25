<?php

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;


if (!function_exists('easy_invoice_get_quote_options')) {

	function easy_invoice_get_quote_options($quote_id = null)
	{
		$quote_id = is_null($quote_id) ? get_the_ID() : absint($quote_id);

		return new QuoteRepository($quote_id);

	}
}

if (!function_exists('easy_invoice_get_quote_label')) {
	function easy_invoice_get_quote_label()
	{
		return easy_invoice_get_text('quote');
	}
}


if (!function_exists('easy_invoice_get_quote_description')) {
	function easy_invoice_get_quote_description()
	{
		global $ei_quote;

		return $ei_quote->get_description();
	}
}


if (!function_exists('easy_invoice_get_quote_number')) {
	function easy_invoice_get_quote_number()
	{
		$pad_len = 4;

		$quote_number = absint(get_option('easy_invoice_quote_number', 0));

		$quote_number = $quote_number + 1;

		$quote_number = $quote_number < 1 ? 1 : $quote_number;

		$prefix = get_option('easy_invoice_quote_number_prefix', 'EIQN_');

		//update_option('easy_invoice_quote_number', absint($quote_number));

		if (is_string($prefix)) {
			return sprintf("%s%s", $prefix, str_pad($quote_number, $pad_len, "0", STR_PAD_LEFT));
		}

		return str_pad($quote_number, $pad_len, "0", STR_PAD_LEFT);
	}
}
if (!function_exists('easy_invoice_update_quote_status')) {

	function easy_invoice_update_quote_status($quote_id = 0, $status = '')
	{
		$easy_invoice_quote_statuses = easy_invoice_get_quote_statuses();

		if (absint($quote_id) < 1 || !isset($easy_invoice_quote_statuses[$status])) {

			return false;
		}

		do_action('easy_invoice_before_quote_status_change', array(
			'quote_id' => $quote_id,
			'status' => $status
		));

		update_post_meta($quote_id, 'quote_status', sanitize_text_field($status));

		do_action('easy_invoice_after_quote_status_change', array(
			'quote_id' => $quote_id,
			'status' => $status
		));

		return true;
	}
}

if (!function_exists('easy_invoice_get_quote_statuses')) {
	function easy_invoice_get_quote_statuses()
	{

		return [
			'available' => __("Available", "easy-invoice"),
			'draft' => __("Draft", "easy-invoice"),
			'accepted' => __("Accepted", "easy-invoice"),
			'declined' => __("Declined", "easy-invoice"),
			'cancelled' => __("Cancelled", "easy-invoice"),
			'expired' => __("Expired", "easy-invoice"),
			'sent' => __("Sent", "easy-invoice"),

		];
	}
}

if (!function_exists('easy_invoice_get_quote_details_data')) {
	function easy_invoice_get_quote_details_data()
	{

		global $ei_quote;

		return array(
			array(
				'label' => easy_invoice_get_text('quote_number'),
				'value' => $ei_quote->get_quote_number(),
			),
			array(
				'label' => easy_invoice_get_text('order_number'),
				'value' => $ei_quote->get_order_number(),
			),
			array(
				'label' => easy_invoice_get_text('quote_date'),
				'value' => $ei_quote->get_created_date(),
			),
			array(
				'label' => easy_invoice_get_text('valid_until_date'),
				'value' => $ei_quote->get_valid_until(),
			),
			array(
				'label' => easy_invoice_get_text('total_due'),
				'value' => easy_invoice_get_price($ei_quote->get_due_amount(), '', $ei_quote->get_id()),
				'is_total' => true,
			),
		);
	}
}

if (!function_exists('easy_invoice_change_quote_to_invoice')) {
	function easy_invoice_change_quote_to_invoice($quote_id)
	{
		$accept_button_action = get_option('easy_invoice_quote_accept_quote_button_action', 'convert');

		global $wpdb;

		$post = get_post($quote_id);

		if (!$post || $post->post_type != Constant::QUOTE_POST_TYPE) {
			return false;
		}


		$remove_metas_from_invoice = array(
			'easy_invoice_quote_line_items',
			'quote_number',
			'quote_status',
		);

		$invoice_id = 0;

		$invoice_created_date = date("F j, Y");

		if ($accept_button_action == 'duplicate' || $accept_button_action == 'duplicate_send') {

			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status' => $post->ping_status,
				'post_author' => $post->post_author,
				'post_content' => $post->post_content,
				'post_excerpt' => $post->post_excerpt,
				'post_name' => $post->post_name,
				'post_parent' => $post->post_parent,
				'post_password' => $post->post_password,
				'post_status' => 'publish',
				'post_title' => $post->post_title,
				'post_type' => $post->post_type,
				'to_ping' => $post->to_ping,
				'menu_order' => $post->menu_order
			);

			$invoice_id = wp_insert_post($args);

			$post_metas = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id=%d",
					$quote_id
				)
			);
			if ($post_metas && count($post_metas)) {
				$sql_query = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
				$sql_values = array();
				foreach ($post_metas as $post_meta) {
					$meta_key = esc_sql($post_meta->meta_key);
					$meta_value = esc_sql($post_meta->meta_value);
					if (!in_array($meta_key, $remove_metas_from_invoice)) {
						$sql_values[] = "($invoice_id, '$meta_key', '$meta_value')";
					}
				}
				$sql_query .= implode(',', $sql_values);
				$wpdb->query($sql_query);
			}
			update_post_meta($invoice_id, 'invoice_number', sanitize_text_field(easy_invoice_get_invoice_number()));

			easy_invoice_update_invoice_status($invoice_id, 'available');

			$line_items = get_post_meta($quote_id, 'easy_invoice_quote_line_items', true);

			update_post_meta($invoice_id, 'easy_invoice_line_items', $line_items);

			update_post_meta($invoice_id, 'created_date', $invoice_created_date);

		} else if ($accept_button_action !== 'do_nothing') {

			set_post_type($quote_id, Constant::INVOICE_POST_TYPE);

			$invoice_id = $quote_id;

			update_post_meta($invoice_id, 'invoice_number', sanitize_text_field(easy_invoice_get_invoice_number()));

			easy_invoice_update_invoice_status($invoice_id, 'available');

			$line_items = get_post_meta($invoice_id, 'easy_invoice_quote_line_items', true);

			update_post_meta($invoice_id, 'easy_invoice_line_items', $line_items);

			update_post_meta($invoice_id, 'created_date', $invoice_created_date);

			foreach ($remove_metas_from_invoice as $remove_item) {

				delete_post_meta($invoice_id, $remove_item);
			}


		}

		do_action('easy_invoice_quote_converted_to_invoice', $quote_id, $invoice_id);
	}
}

if (!function_exists('easy_invoice_add_quote_log')) {

	function easy_invoice_add_quote_log($quote_id, $log)
	{
		if (get_post_type($quote_id) !== Constant::QUOTE_POST_TYPE) {
			return false;
		}
		$current_user = get_current_user_id();

		$log_message = sanitize_text_field($log);

		$log_time = time();

		$user = wp_get_current_user();

		$username = $user->user_login;

		$log_object = [
			'user_id' => $current_user,
			'username' => $username,
			'message' => $log_message,
			'time' => $log_time
		];

		$quote_log = get_post_meta($quote_id, 'quote_log', true);

		$quote_log = !is_array($quote_log) ? [] : $quote_log;

		array_push($quote_log, $log_object);

		update_post_meta($quote_id, 'quote_log', $quote_log);

		return true;

	}
}

if (!function_exists('easy_invoice_quote_can_accept')) {
	function easy_invoice_quote_can_accept($quote_id)
	{
		if (get_post_type($quote_id) !== Constant::QUOTE_POST_TYPE) {
			return false;
		}
		$can_accept_status = ["available"];

		$quote = new QuoteRepository($quote_id);

		$quote_validity = $quote->get_valid_until();

		$quote_validity = $quote_validity == '' ? 0 : $quote_validity;

		$quote_status = $quote->get_quote_status();

		if (strtotime($quote_validity) < time() && $quote_validity !== 0) {
			return false;
		}
		if (!in_array($quote_status, $can_accept_status)) {
			return false;
		}
		return true;

	}
}

if (!function_exists('easy_invoice_quote_can_decline')) {
	function easy_invoice_quote_can_decline($quote_id)
	{
		if (get_post_type($quote_id) !== Constant::QUOTE_POST_TYPE) {
			return false;
		}
		$can_decline = ["available"];

		$quote = new QuoteRepository($quote_id);

		$quote_validity = $quote->get_valid_until();

		$quote_validity = $quote_validity == '' ? 0 : $quote_validity;

		$quote_status = $quote->get_quote_status();

		if (strtotime($quote_validity) < time() && $quote_validity !== 0) {

			return false;
		}

		if (!in_array($quote_status, $can_decline)) {
			return false;
		}
		return true;
	}
}
