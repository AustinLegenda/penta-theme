<?php

namespace MatrixAddons\EasyInvoice\PostTypes;

use MatrixAddons\EasyInvoice\Constant;

class Invoices
{

	private $slug = Constant::INVOICE_POST_TYPE;

	public function register()
	{
		$permalink = sanitize_text_field(get_option('easy_invoice_permalink_for_invoice', Constant::INVOICE_POST_TYPE));

		$permalink = $permalink == '' ? Constant::INVOICE_POST_TYPE : sanitize_text_field($permalink);

		$labels = array(
			'name' => __('Invoices', 'easy-invoice'),
			'singular_name' => __('Invoice', 'easy-invoice'),
			'add_new' => __('Add New invoice', 'easy-invoice'),
			'add_new_item' => __('Add New invoice', 'easy-invoice'),
			'edit_item' => __('Edit invoice', 'easy-invoice'),
			'new_item' => __('New invoice', 'easy-invoice'),
			'all_items' => __('All Invoices', 'easy-invoice'),
			'view_item' => __('View invoice', 'easy-invoice'),
			'search_items' => __('Search invoice', 'easy-invoice'),
			'not_found' => __('No Invoices found', 'easy-invoice'),
			'not_found_in_trash' => __('No Invoices found in the Trash', 'easy-invoice'),
			'parent_item_colon' => '',
		);

		$args = array(
			'labels' => $labels,
			'menu_icon' => 'dashicons-calculator',
			'public' => true,
			'supports' => array('title'),
			'has_archive' => false,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_in_admin_bar' => false,
			'rewrite' => array(
				'slug' => trim($permalink),
				'with_front' => true
			),
		);
		register_post_type($this->slug, $args);

		do_action('easy_invoice_after_register_post_type');

	}

	public static function init()
	{
		$self = new self();
		add_action('init', [$self, 'register']);
	}
}



