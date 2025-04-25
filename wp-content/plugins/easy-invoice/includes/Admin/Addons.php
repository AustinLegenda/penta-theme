<?php

namespace MatrixAddons\EasyInvoice\Admin;

class Addons
{
	public static function init()
	{
		$self = new self;

		add_action('easy_invoice_admin_main_submenu', array($self, 'submenu'));

		add_action('admin_enqueue_scripts', array($self, 'scripts'), 11);

		add_filter('plugin_action_links_' . plugin_basename(EASY_INVOICE_PLUGIN_DIR . 'easy-invoice.php'), [$self, 'settings_link'], 10, 4);


	}

	public function submenu($submenu)
	{

		$submenu[] = array(
			'parent_slug' => 'easy-invoice-dashboard',
			'page_title' => esc_html__('Easy Invoice Addons', 'easy-invoice'),
			'menu_title' => '<span style="color:#28d01d">' . esc_html__('Addons', 'easy-invoice') . '</span>',
			'capability' => 'manage_options',
			'menu_slug' => 'ei-addons',
			'callback' => array($this, 'addon_page'),
			'position' => 30,
		);


		if (count(easy_invoice_get_premium_addons()) < 1) {

			$submenu[] = array(
				'parent_slug' => 'easy-invoice-dashboard',
				'page_title' => esc_html__('Upgrade to Pro', 'easy-invoice'),
				'menu_title' => '<span style="color:#e27730">' . esc_html__('Upgrade to Pro', 'easy-invoice') . '</span>',
				'capability' => 'manage_options',
				'menu_slug' => esc_url('https://matrixaddons.com/downloads/easy-invoice-pro/?utm_campaign=freeplugin&utm_medium=admin-menu&utm_source=WordPress&utm_content=Upgrade+to+Pro'),
				'callback' => '',
				'position' => 35,
			);
		}
		return $submenu;
	}

	public function addon_page()
	{
		try {

			$addons_json = file_get_contents(EASY_INVOICE_PLUGIN_DIR . 'assets/admin/addon-lists.json');

			$addons = json_decode($addons_json, true);

		} catch (\Exception $e) {

			$addons = [];
		}

		$all_license_details = LicenseManager::get_license_details();

		easy_invoice_load_admin_template('Addon.List', array('addons' => $addons, 'licenses' => $all_license_details));
	}

	public function scripts($hook)
	{

		if ('easy-invoice_page_ei-addons' != $hook) {
			return;
		}

		wp_enqueue_style('easy-invoice-admin-addons', EASY_INVOICE_PLUGIN_URI . '/assets/admin/css/addons.css', array(), EASY_INVOICE_VERSION);
	}

	public function settings_link($links, $plugin_file, $plugin_data, $context)
	{

		$custom['pro'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer"
				style="color: #00a32a; font-weight: 700;"
				onmouseover="this.style.color=\'#008a20\';"
				onmouseout="this.style.color=\'#00a32a\';"
				>%3$s</a>',
			esc_url(
				add_query_arg(
					[
						'utm_content' => 'Get+Easy+Invoice+Premium',
						'utm_campaign' => 'freeplugin',
						'utm_medium' => 'all-plugins',
						'utm_source' => 'WordPress',
					],
					'https://matrixaddons.com/downloads/easy-invoice-pro/'
				)
			),
			esc_attr__('Get Easy Invoice Pro', 'easy-invoice'),
			esc_html__('Get Easy Invoice Pro', 'easy-invoice')
		);

		$custom['settings'] = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url(
				add_query_arg(
					['page' => 'easy-invoice-settings'],
					admin_url('edit.php?post_type=easy-invoice&page=easy-invoice-settings')
				)
			),
			esc_attr__('Go to Easy Invoice Settings page', 'easy-invoice'),
			esc_html__('Settings', 'easy-invoice')
		);

		$custom['docs'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
			esc_url(
				add_query_arg(
					[
						'utm_content' => 'Documentation',
						'utm_campaign' => 'freeplugin',
						'utm_medium' => 'all-plugins',
						'utm_source' => 'WordPress',
					],
					'https://matrixaddons.com/docs/easy-invoice-best-wordpress-invoice-plugin/'
				)
			),
			esc_attr__('Read the documentation', 'easy-invoice'),
			esc_html__('Docs', 'easy-invoice')
		);
		if (count(easy_invoice_get_premium_addons()) > 0) {
			unset($custom['pro']);
		}

		return array_merge($custom, (array)$links);
	}

}
