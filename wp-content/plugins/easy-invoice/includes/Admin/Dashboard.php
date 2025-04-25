<?php

namespace MatrixAddons\EasyInvoice\Admin;

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

class Dashboard
{

	public static function template()
	{
		$self = new self;

		$quotes_invoices = $self->get_quote_invoices();

		$dashboard = [];

		$dashboard = array_merge($dashboard, $quotes_invoices);

		$dashboard['total_invoices'] = $self->get_total_invoices();
		$dashboard['total_quotes'] = $self->get_total_quotes();
		$dashboard['paid_invoices'] = $self->get_total_paid_invoices();
		$dashboard['total_paid_amount'] = $self->get_total_paid_amount();
		$dashboard['accepted_quote'] = $self->get_total_accepted_quotes();

		easy_invoice_load_admin_template('Dashboard', $dashboard);
	}

	public static function init()
	{

		$self = new self;

		add_action('easy_invoice_plugin_loaded', array($self, 'hooks'), 11);

	}


	public function hooks()
	{

	}

	public function get_items($type = 'invoice', $period_ago = 0)
	{
		//echo phpinfo();exit;

		$start = strtotime(date('Y-m-01'));

		$end = strtotime(date('Y-m-t'));

		if ($period_ago != 0) {

			$start = strtotime($period_ago . 'month', $start);

			if (function_exists('cal_days_in_month')) {

				$days = cal_days_in_month(CAL_GREGORIAN, date('m', $start), date('Y', $start));

				$end = strtotime(date(date('Y', $start) . '-' . date('m', $start) . '-' . $days . ''));

			} else {
				$end = strtotime(date('Y-m-t', $start));
			}

		}

		// adding the times to start and end to ensure we get the full days
		$start = strtotime(date('Y-m-d 00:00:00', $start));

		$end = strtotime(date('Y-m-d 23:59:59', $end));

		$post_type = Constant::INVOICE_POST_TYPE;

		global $wpdb;


		$query = "SELECT $wpdb->posts.* FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id ) INNER JOIN $wpdb->postmeta AS mt1 ON ( $wpdb->posts.ID = mt1.post_id ) WHERE 1=1 AND ( ( $wpdb->postmeta.meta_key = 'created_date' AND UNIX_TIMESTAMP(STR_TO_DATE($wpdb->postmeta.meta_value, '%M %e, %Y')) BETWEEN %d AND %d ) AND ( mt1.meta_key ='invoice_status' AND mt1.meta_value NOT IN ('draft','cancelled') ) ) AND $wpdb->posts.post_type = %s AND (($wpdb->posts.post_status = 'publish')) GROUP BY $wpdb->posts.ID ORDER BY $wpdb->posts.post_date DESC";

		if ($type == 'quote') {

			$post_type = Constant::QUOTE_POST_TYPE;

			$query = "SELECT $wpdb->posts.* FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id ) INNER JOIN $wpdb->postmeta AS mt1 ON ( $wpdb->posts.ID = mt1.post_id ) WHERE 1=1 AND ( ( $wpdb->postmeta.meta_key = 'created_date' AND UNIX_TIMESTAMP(STR_TO_DATE($wpdb->postmeta.meta_value, '%M %e, %Y')) BETWEEN %d AND %d ) AND ( mt1.meta_key ='quote_status' AND mt1.meta_value NOT IN ('draft','cancelled', 'declined') ) ) AND $wpdb->posts.post_type = %s AND (($wpdb->posts.post_status = 'publish')) GROUP BY $wpdb->posts.ID ORDER BY $wpdb->posts.post_date DESC";

		}

		$results = $wpdb->get_results($wpdb->prepare($query, $start, $end, $post_type));

		$total = array();

		if (!is_wp_error($results)) :
			foreach ($results as $post) {
				if ($type === 'invoice') {
					$invoice_repo = new InvoiceRepository($post->ID);
					$total[$post->ID] = $invoice_repo->get_sub_total();
				}
				if ($type === 'quote') {
					$quote_repo = new QuoteRepository($post->ID);
					$total[$post->ID] = $quote_repo->get_sub_total();
				}
			};
		endif;

		return $total;

	}


	public function get_quote_invoices()
	{

		$month_array = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');

		// sure there's an easier way.
		$count = date('m');

		foreach ($month_array as $index => $month) {
			$index = $index - 1;//minus 1 so that we start on 0 for current month
			$items = $this->get_items('invoice', -$index);

			if ($items) {
				$sum = array_sum($items);
				$invoice_total[$month_array[(int)$count]] = $sum;
			} else {
				$invoice_total[$month_array[(int)$count]] = 0;
			}

			if ($count <= 1) {
				$count = 13;
			}
			$count--;
		}

		foreach ($month_array as $index => $month) {
			$index = $index - 1;//minus 1 so that we start on 0 for current month
			$items = $this->get_items('quote', -$index);

			if ($items) {
				$sum = array_sum($items);
				$quote_total[$month_array[(int)$count]] = $sum;
			} else {
				$quote_total[$month_array[(int)$count]] = 0;
			}

			if ($count <= 1) {
				$count = 13;
			}
			$count--;
		}

		$quotes = array_reverse(array_slice($quote_total, 0, 12));

		$invoices = array_reverse(array_slice($invoice_total, 0, 12));

		return ['quotes' => $quotes, 'invoices' => $invoices];
	}

	public function get_total_invoices()
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => Constant::INVOICE_POST_TYPE,
			'post_status' => 'publish',
			'meta_query' => array(
//				array(
//					'key' => 'invoice_status',
//					'value' => array('red', 'blue'),
//					'operator' => 'NOT IN'
//				),
			),
		);

		$query = new \WP_Query(
			$args
		);

		return $query->found_posts;

	}

	public function get_total_quotes()
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => Constant::QUOTE_POST_TYPE,
			'post_status' => 'publish',
			'meta_query' => array(
//				array(
//					'key' => 'invoice_status',
//					'value' => array('red', 'blue'),
//					'operator' => 'NOT IN'
//				),
			),
		);

		$query = new \WP_Query(
			$args
		);

		return $query->found_posts;

	}

	public function get_total_paid_invoices()
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => Constant::INVOICE_POST_TYPE,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'invoice_status',
					'value' => array('paid'),
					'operator' => 'IN'
				),
			),
		);

		$query = new \WP_Query(
			$args
		);

		return $query->found_posts;

	}

	public function get_total_accepted_quotes()
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => Constant::QUOTE_POST_TYPE,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'quote_status',
					'value' => array('accepted'),
					'operator' => 'IN'
				),
			),
		);

		$query = new \WP_Query(
			$args
		);

		return $query->found_posts;

	}

	public function get_total_paid_amount()
	{

		global $wpdb;

		$paid_amount = $wpdb->get_results("SELECT SUM(pm.meta_value) as paid_amount FROM {$wpdb->postmeta} pm
                             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                             WHERE pm.meta_key = 'paid_amount'
                             AND p.post_status = 'publish'
                             AND p.post_type = '" . Constant::PAYMENT_POST_TYPE . "'");

		if (!isset($paid_amount[0])) {
			return 0;
		}
		if (isset($paid_amount[0]->paid_amount)) {
			return $paid_amount[0]->paid_amount;
		}
		return 0;
	}

}
