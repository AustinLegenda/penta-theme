<?php

namespace MatrixAddons\EasyInvoice\Admin;

class Init
{

	/**
	 * The single instance of the class.
	 *
	 * @var Init
	 * @since 1.0.0
	 */
	protected static $_instance = null;


	/**
	 * Main Main Instance.
	 *
	 *
	 * @return Init - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Main Constructor.
	 */
	public function __construct()
	{
		$this->init();
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks()
	{


		add_action('admin_menu', array($this, 'admin_menu'));


	}

	function admin_menu()
	{
		add_menu_page(
			__('Easy Invoice', 'easy-invoice'),
			__('Easy Invoice', 'easy-invoice'),
			'manage_options',
			'easy-invoice-dashboard',
			array($this, 'dashboard'),
			'dashicons-money-alt',
			28
		);
		add_submenu_page(
			'easy-invoice-dashboard',
			__('Easy Invoice Dashboard', 'easy-invoice'),
			__('Dashboard', 'easy-invoice'),
			'manage_options',
			'easy-invoice-dashboard',
			null,
			5
		);


		$settings_page = add_submenu_page('easy-invoice-dashboard', __('Easy Invoice Settings', 'easy-invoice'), __('Settings', 'easy-invoice'), 'manage_options', 'easy-invoice-settings', array($this, 'settings'));

		add_action('load-' . $settings_page, array($this, 'settings_page_init'));


		$default_submenu_args = array(
			'parent_slug' => '',
			'page_title' => '',
			'menu_title' => '',
			'capability' => 'manage_options',
			'menu_slug' => '',
			'callback' => '',
			'position' => null,
			'load_action' => '',
		);

		$submenu_configurations = apply_filters('easy_invoice_admin_main_submenu', array());

		$submenu_columns = array_column($submenu_configurations, "position");

		array_multisort($submenu_columns, SORT_ASC, $submenu_configurations);

		foreach ($submenu_configurations as $configuration) {

			$configuration = wp_parse_args($configuration, $default_submenu_args);

			$hookname = add_submenu_page(
				$configuration['parent_slug'],
				$configuration['page_title'],
				$configuration['menu_title'],
				$configuration['capability'],
				$configuration['menu_slug'],
				$configuration['callback'],
				$configuration['position']
			);
			if ($configuration['load_action'] !== '') {

				add_action('load-' . $hookname, $configuration['load_action']);

			}
		}

	}

	public function dashboard()
	{
		Dashboard::template();
	}

	public function settings()
	{
		Settings::output();


	}

	public function settings_page_init()
	{
		global $current_tab, $current_section;

		// Include settings pages.
		Settings::get_settings_pages();

		// Get current tab/section.
		$current_tab = empty($_GET['tab']) ? 'ei_general' : sanitize_title(wp_unslash($_GET['tab'])); // WPCS: input var okay, CSRF ok.
		$current_section = empty($_REQUEST['section']) ? '' : sanitize_title(wp_unslash($_REQUEST['section'])); // WPCS: input var okay, CSRF ok.

		// Save settings if data has been posted.
		if ('' !== $current_section && apply_filters("easy_invoice_save_settings_{$current_tab}_{$current_section}", !empty($_POST['save']))) { // WPCS: input var okay, CSRF ok.
			Settings::save();
		} elseif ('' === $current_section && apply_filters("easy_invoice_save_settings_{$current_tab}", !empty($_POST['save']))) { // WPCS: input var okay, CSRF ok.
			Settings::save();
		}

		// Add any posted messages.
		if (!empty($_GET['easy_invoice_error'])) { // WPCS: input var okay, CSRF ok.
			Settings::add_error(wp_kses_post(wp_unslash($_GET['easy_invoice_error']))); // WPCS: input var okay, CSRF ok.
		}

		if (!empty($_GET['easy_invoice_message'])) { // WPCS: input var okay, CSRF ok.
			Settings::add_message(wp_kses_post(wp_unslash($_GET['easy_invoice_message']))); // WPCS: input var okay, CSRF ok.
		}

		do_action('easy_invoice_settings_page_init');


	}

	/**
	 * Include required core files used in admin.
	 */
	public function init()
	{
		Assets::init();
		Addons::init();
		LicenseManager::init();
		Dashboard::init();
	}


}
