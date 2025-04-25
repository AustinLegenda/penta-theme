<?php

namespace MatrixAddons\EasyInvoice\Admin;

class Assets
{
	public static function init()
	{
		$self = new self();

		add_action('admin_enqueue_scripts', array($self, 'admin_assets'), 10, 1);
	}

	public function admin_assets()
	{
		$screen = get_current_screen();

		$screen_id = $screen->id ?? '';


		if ($screen_id == 'easy-invoice_page_easy-invoice-settings') {

			wp_enqueue_media();

			wp_register_style(
				'easy-invoice-admin-settings', // Handle.
				EASY_INVOICE_ASSETS_URI . 'admin/css/settings.css',
				array('wp-color-picker'),
				EASY_INVOICE_VERSION
			);

			wp_register_script(
				'easy-invoice-admin-settings', // Handle.
				EASY_INVOICE_ASSETS_URI . 'admin/js/settings.js',
				array('jquery', 'wp-color-picker'),
				EASY_INVOICE_VERSION
			);
			wp_enqueue_script('easy-invoice-admin-settings');
			wp_enqueue_style('easy-invoice-admin-settings');
		} else if ($screen_id === "toplevel_page_easy-invoice-dashboard") {
			wp_register_style(
				'easy-invoice-admin-dashboard', // Handle.
				EASY_INVOICE_ASSETS_URI . 'admin/css/dashboard.css',
				array(),
				EASY_INVOICE_VERSION
			);


			wp_register_script(
				'easy-invoice-chartjs', // Handle.
				EASY_INVOICE_ASSETS_URI . 'lib/chart/Chart.min.js',
				array(),
				EASY_INVOICE_VERSION
			);
			wp_enqueue_script('easy-invoice-chartjs');

			wp_enqueue_style('easy-invoice-admin-dashboard');

		}
	}
}
