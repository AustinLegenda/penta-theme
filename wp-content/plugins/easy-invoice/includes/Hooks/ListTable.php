<?php

namespace MatrixAddons\EasyInvoice\Hooks;

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

class ListTable
{
	public function __construct()
	{
		add_filter('manage_edit-easy-invoice_columns', array($this, 'invoice_column'));
		add_action('manage_easy-invoice_posts_custom_column', array($this, 'invoice_column_value'), 15, 2);


		add_filter('manage_edit-easy-invoice-quotes_columns', array($this, 'quote_column'));
		add_action('manage_easy-invoice-quotes_posts_custom_column', array($this, 'quote_column_value'), 15, 2);


		add_filter('manage_edit-easy-invoice-payment_columns', array($this, 'invoice_payment_column'));
		add_action('manage_easy-invoice-payment_posts_custom_column', array($this, 'invoice_payment_column_value'), 15, 2);


		add_filter('post_row_actions', array($this, 'row_link'), 10, 2);
		add_filter('admin_action_clone_quote_invoice', array($this, 'row_actions'));
	}

	public function row_link($actions, $post)
	{

		if (current_user_can('edit_posts') && ($post->post_type == Constant::INVOICE_POST_TYPE || $post->post_type == Constant::QUOTE_POST_TYPE)) {

			$nonce = wp_create_nonce('easy_invoice_clone_quote_and_invoice-' . $post->ID);

			$output = admin_url('admin.php?action=clone_quote_invoice&amp;post=' . $post->ID . '&amp;_wpnonce=' . $nonce);

			$actions['duplicate'] = '<a href="' . esc_url($output) . '" title="' . __('Clone this item', 'easy-invoice') . '" rel="permalink">' . __('Clone', 'easy-invoice') . '</a>';
		}

		return $actions;
	}

	public function row_actions()
	{

		global $wpdb;

		if (!current_user_can('edit_posts')) {
			return;
		}
		$post_id = isset($_REQUEST['post']) ? intval(sanitize_text_field($_REQUEST['post'])) : false;
		if (!$post_id) {
			wp_die(__('No estimate or invoice to duplicate!', 'easy-invoice'));
		}
		$nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : false;
		if (!wp_verify_nonce($nonce, 'easy_invoice_clone_quote_and_invoice-' . $post_id)) {
			wp_die(__('The link you followed has expired.', 'easy-invoice'));
		}

		$post = get_post($post_id);
		if (!$post || !in_array($post->post_type, array(Constant::QUOTE_POST_TYPE, Constant::INVOICE_POST_TYPE))) {
			wp_die(__('Creation failed, could not find original invoice or estimate: ', 'easy-invoice') . $post_id);
		}

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
			'post_title' => $post->post_title . ' - copy',
			'post_type' => $post->post_type,
			'to_ping' => $post->to_ping,
			'menu_order' => $post->menu_order
		);

		$new_post_id = wp_insert_post($args);

		$non_cloneable_post_metas = apply_filters('easy_invoice_non_cloneable_post_metas', array(
			'invoice_number',
			'quote_number',
		));
		$post_metas = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id=%d",
				$post_id
			)
		);
		if ($post_metas && count($post_metas)) {
			$sql_query = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";
			$sql_values = array();
			foreach ($post_metas as $post_meta) {
				$meta_key = esc_sql($post_meta->meta_key);
				$meta_value = esc_sql($post_meta->meta_value);
				if (!in_array($meta_key, $non_cloneable_post_metas)) {
					$sql_values[] = "($new_post_id, '$meta_key', '$meta_value')";
				}
			}
			$sql_query .= implode(',', $sql_values);
			$wpdb->query($sql_query);
		}


		if ($post->post_type === Constant::QUOTE_POST_TYPE) {
			update_post_meta($new_post_id, 'quote_number', easy_invoice_get_quote_number());
		} else {
			update_post_meta($new_post_id, 'invoice_number', easy_invoice_get_invoice_number());
		}


		$current_url = admin_url('edit.php?post_type=' . $post->post_type . '');
		wp_redirect($current_url);
		exit;
	}

	public function invoice_column($columns)
	{

		unset($columns['date']);
		$columns['invoice_number'] = __('Invoice Number', 'easy-invoice');
		$columns['client'] = __('Client', 'easy-invoice');
		$columns['status'] = __('Status', 'easy-invoice');
		$columns['due_date'] = __('Due Date', 'easy-invoice');
		$columns['due_amount'] = __('Due Amount', 'easy-invoice');

		$columns['date'] = __('Date', 'easy-invoice');
		$columns['action'] = __('Action', 'easy-invoice');

		return $columns;
	}

	public function invoice_column_value($column, $a)
	{
		global $post;

		$invoice_id = $post->ID;

		$invoice_repository = new InvoiceRepository($invoice_id);

		switch ($column) {
			case "invoice_number":
				echo '<code style="font-size:15px;">' . esc_html($invoice_repository->get_invoice_number()) . '</code>';
				break;
			case "client":
				echo '<span>' . esc_html($invoice_repository->get_client_name()) . '</span>';
				echo '<br/>';
				echo '<span>' . esc_html($invoice_repository->get_client_email()) . '</span>';
				break;
			case "status":
				$invoice_status = easy_invoice_get_invoice_statuses();
				if (isset($invoice_status[$invoice_repository->get_invoice_status()])) {
					echo '<span>' . esc_html($invoice_status[$invoice_repository->get_invoice_status()]) . '</span>';
				} else {
					echo '<span>N/A</span>';
				}
				break;
			case "due_date":
				echo '<span>' . esc_html($invoice_repository->get_due_date()) . '</span>';
				break;
			case "due_amount":
				echo '<span>' . esc_html(easy_invoice_get_price($invoice_repository->get_due_amount(), '', $invoice_id)) . '</span>';
				break;
			case "action":
				echo '<a style="display:inline-flex;justify-content:space-between; align-items: center; text-align:center; margin-right:5px;" target="_blank" title="View" class="button button-secondary" href="' . get_permalink($invoice_id) . '"><span class="dashicons dashicons-visibility"></span></a>';
				echo '<a style="display:inline-flex;justify-content:space-between; align-items: center; text-align:center;margin-right:5px;" target="_blank; margin-right:5px;" title="Download as PDF" class="button button-secondary" href="' . esc_url(easy_invoice_get_download_as_pdf_url($invoice_id)) . '"><span class="dashicons dashicons-pdf"></span></a>';
				/*echo '<a style="display:inline-flex;justify-content:space-between; align-items: center; text-align:center;" target="_blank" title="Download as PDF" class="button button-secondary" href="#"><span class="dashicons dashicons-email"></span></a>';*/

				break;
		}
	}


	public function invoice_payment_column($columns)
	{

		unset($columns['date']);
		$columns['payment_gateway'] = __('Payment Gateway', 'easy-invoice');
		$columns['paid_amount'] = __('Paid Amount', 'easy-invoice');
		$columns['status'] = __('Payment Status', 'easy-invoice');
		$columns['invoice'] = __('Invoice', 'easy-invoice');
		$columns['payment_note'] = __('Payment Note', 'easy-invoice');
		$columns['payment_date'] = __('Payment Date', 'easy-invoice');


		return $columns;
	}

	public function invoice_payment_column_value($column, $a)
	{
		global $post;

		$payment_id = $post->ID;

		$payment = new PaymentRepository($payment_id);

		$invoice_id = $payment->get_invoice_id();


		$all_payment_status = PaymentRepository::payment_statuses();

		switch ($column) {

			case "payment_gateway":
				$gateway_lists = easy_invoice_get_payment_gateway_lists();

				$gateway_id = $payment->get_gateway();

				if ($gateway_id === '') {

					echo '<span>N/A</span>';
				} else if (isset($gateway_lists[$gateway_id])) {

					echo '<span>' . esc_html($gateway_lists[$gateway_id]) . '</span>';
				} else {
					echo '<span>' . esc_html($gateway_id) . '</span>';
				}
				break;
			case "paid_amount":
				echo '<span>' . esc_html(easy_invoice_get_price($payment->get_paid_amount(), '', $invoice_id)) . '</span>';
				break;
			case "status":
				if (isset($all_payment_status[$payment->get_status()])) {
					echo '<span>' . esc_html($all_payment_status[$payment->get_status()]) . '</span>';
				} else {
					echo '<span>N/A</span>';
				}
				break;
			case "invoice":
				if (absint($invoice_id) > 0) {
					echo '<a href="' . get_permalink($invoice_id) . '" target="_blank">' . esc_html(get_the_title($invoice_id)) . '</a>';
				} else {
					echo '<span > N / A</span > ';
				}
				break;
			case "payment_note":
				echo '<p>' . esc_html($payment->get_note()) . '</p>';
				break;
			case "payment_date":
				echo '<p>' . esc_html($payment->get_payment_date()) . '</p>';
				break;
		}
	}

	public function quote_column($columns)
	{

		unset($columns['date']);
		$columns['quote_number'] = __('Estimate Number', 'easy-invoice');
		$columns['client'] = __('Client', 'easy-invoice');
		$columns['status'] = __('Status', 'easy-invoice');
		$columns['valid_date'] = __('Valid Date', 'easy-invoice');
		$columns['quote_amount'] = __('Total Amount', 'easy-invoice');
		$columns['date'] = __('Date', 'easy-invoice');
		$columns['action'] = __('Action', 'easy-invoice');

		return $columns;
	}

	public function quote_column_value($column, $a)
	{
		global $post;

		$quote_id = $post->ID;

		$quote_repository = new QuoteRepository($quote_id);

		switch ($column) {
			case "quote_number":
				echo '<code style="font-size:15px;">' . esc_html($quote_repository->get_quote_number()) . '</code>';
				break;
			case "client":
				echo '<span>' . esc_html($quote_repository->get_client_name()) . '</span>';
				echo '<br/>';
				echo '<span>' . esc_html($quote_repository->get_client_email()) . '</span>';
				break;
			case "status":
				$invoice_status = easy_invoice_get_quote_statuses();
				if (isset($invoice_status[$quote_repository->get_quote_status()])) {
					echo '<span>' . esc_html($invoice_status[$quote_repository->get_quote_status()]) . '</span>';
				} else {
					echo '<span>N/A</span>';
				}
				break;
			case "valid_date":
				echo '<span>' . esc_html($quote_repository->get_valid_until()) . '</span>';
				break;
			case "quote_amount":
				echo '<span>' . esc_html(easy_invoice_get_price($quote_repository->get_due_amount(), '', $quote_id)) . '</span>';
				break;
			case "action":
				echo '<a style="display:inline-flex;justify-content:space-between; align-items: center; text-align:center; margin-right:5px;" target="_blank" title="View" class="button button-secondary" href="' . get_permalink($quote_id) . '"><span class="dashicons dashicons-visibility"></span></a>';
				echo '<a style="display:inline-flex;justify-content:space-between; align-items: center; text-align:center;margin-right:5px;" target="_blank; margin-right:5px;" title="Download as PDF" class="button button-secondary" href="' . esc_url(easy_invoice_get_download_as_pdf_url($quote_id)) . '"><span class="dashicons dashicons-pdf"></span></a>';
				/*echo '<a style="display:inline-flex;justify-content:space-between; align-items: center; text-align:center;" target="_blank" title="Download as PDF" class="button button-secondary" href="#"><span class="dashicons dashicons-email"></span></a>';*/

				break;
		}
	}
}
