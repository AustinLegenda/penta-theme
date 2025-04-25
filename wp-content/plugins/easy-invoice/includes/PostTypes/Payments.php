<?php

namespace MatrixAddons\EasyInvoice\PostTypes;

use MatrixAddons\EasyInvoice\Constant;

class Payments
{

	private $slug = CONSTANT::PAYMENT_POST_TYPE;

	public function register_post_status()
	{

		$payment_statuses = array(
			'processing' => array(
				'label' => _x('Pending', 'payment status', 'easy-invoice'),
				'public' => true,
				'exclude_from_search' => false,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of orders */
				'label_count' => _n_noop('Pending payment <span class="count">(%s)</span>', 'Pending payments <span class="count">(%s)</span>', 'easy-invoice'),
			),
			'publish' => array(
				'label' => _x('Complete', 'Payment status', 'easy-invoice'),
				'public' => true,
				'exclude_from_search' => false,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of orders */
				'label_count' => _n_noop('Complete <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'easy-invoice'),
			),
			'hold' => array(
				'label' => _x('On hold', 'Payment status', 'easy-invoice'),
				'public' => true,
				'exclude_from_search' => false,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of orders */
				'label_count' => _n_noop('On hold <span class="count">(%s)</span>', 'On hold <span class="count">(%s)</span>', 'easy-invoice'),
			),
			'refunded' => array(
				'label' => _x('Refunded', 'Payment status', 'easy-invoice'),
				'public' => true,
				'exclude_from_search' => false,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of orders */
				'label_count' => _n_noop('Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'easy-invoice'),
			),
			'failed' => array(
				'label' => _x('Failed', 'Payment status', 'easy-invoice'),
				'public' => true,
				'exclude_from_search' => false,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of orders */
				'label_count' => _n_noop('Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'easy-invoice'),
			),
		);

		foreach ($payment_statuses as $payment_status => $values) {
			register_post_status($payment_status, $values);
		}
	}

	function fix_capability_create()
	{
		$post_types = get_post_types(array(), 'objects');
		foreach ($post_types as $post_type) {
			$cap = "create_" . $post_type->name;
			$post_type->cap->create_posts = $cap;
			map_meta_cap($cap, 1);
		}
	}

	public function register()
	{

		$labels = array(
			'name' => __('Payments', 'easy-invoice'),
			'singular_name' => __('Payment', 'easy-invoice'),
			'add_new' => __('Add New payment', 'easy-invoice'),
			'add_new_item' => __('Add New payment', 'easy-invoice'),
			'edit_item' => __('Edit payment', 'easy-invoice'),
			'new_item' => __('New payment', 'easy-invoice'),
			'all_items' => __('All Payments', 'easy-invoice'),
			'view_item' => __('View invoice', 'easy-invoice'),
			'search_items' => __('Search invoice', 'easy-invoice'),
			'not_found' => __('No Payments found', 'easy-invoice'),
			'not_found_in_trash' => __('No Payments found in the Trash', 'easy-invoice'),
			'parent_item_colon' => '',
		);

		$args = array(
			'labels' => $labels,
			'show_in_menu' => 'edit.php?post_type=' . CONSTANT::INVOICE_POST_TYPE,
			'public' => true,
			'supports' => array('title'),
			'has_archive' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => false,
			'show_in_admin_bar' => false,
			'capabilities' => array(
				'create_posts' => false,
				'edit_published_posts' => false,
				'delete_published_posts' => false,
			),
		);
		register_post_type($this->slug, $args);


	}

	public static function init()
	{
		$self = new self();
		add_action('init', [$self, 'register']);
		add_action('init', array($self, 'register_post_status'), 9);
		//add_action('init', array($self, 'fix_capability_create'), 100);


	}
}





