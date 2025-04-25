<?php

namespace MatrixAddons\EasyInvoice\Admin;

class LicenseManager
{

	public static function init()
	{

		$self = new self;

		add_action('easy_invoice_plugin_loaded', array($self, 'hooks'), 11);

	}

	public function hooks()
	{
		if (count(easy_invoice_get_premium_addons()) < 1) {
			return;
		}


		add_filter('easy_invoice_admin_main_submenu', array($this, 'license_menu'));

		add_action('admin_enqueue_scripts', array($this, 'license_scripts'), 11);

		add_action('wp_ajax_easy_invoice_update_single_license', array($this, 'update_single_license'), 10);

		add_action('wp_ajax_easy_invoice_deactivate_single_license', array($this, 'deactivate_single_license'), 10);

	}

	public static function get_license_details($product_id = '')
	{
		$details = get_option('easy_invoice_license_details', array());

		$details = !is_array($details) ? array() : $details;

		if ($product_id == '') {

			return $details;
		}
		if (isset($details[$product_id])) {

			return $details[$product_id];
		}
		return [];
	}

	public static function update_license_details($all_licenses)
	{
		update_option('easy_invoice_license_details', $all_licenses);
	}

	public function license_menu($submenu)
	{

		$submenu[] = array(
			'parent_slug' => 'edit.php?post_type=easy-invoice',
			'page_title' => __('Licenses', 'easy-invoice'),
			'menu_title' => __('Licenses', 'easy-invoice'),
			'capability' => 'manage_options',
			'menu_slug' => 'ei-license',
			'callback' => array($this, 'license_page'),
			'position' => 27,
		);
		return $submenu;

	}


	public function update_single_license()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		if (!wp_verify_nonce($nonce, 'easy_invoice_update_license_nonce') || !current_user_can('manage_options')) {

			wp_send_json_error();
			exit;
		}

		$this->update_license();

		wp_send_json_success();

	}

	public function deactivate_single_license()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		if (!wp_verify_nonce($nonce, 'easy_invoice_deactivate_license_nonce') || !current_user_can('manage_options')) {

			wp_send_json_error();

			exit;
		}
		$slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';

		$all_licenses = self::get_license_details();

		if (isset($all_licenses[$slug])) {

			$product_license = $all_licenses[$slug];

			$product_license = apply_filters('easy_invoice_addon_before_license_deactivate', $product_license, $slug);

			$all_licenses[$slug] = $product_license;

			self::update_license_details($all_licenses);

		}
		wp_send_json_success();

	}

	public function update_license()
	{
		$all_licenses = self::get_license_details();

		$all_valid_licenses = array();

		$premium_addons = easy_invoice_get_premium_addons();

		foreach ($premium_addons as $addon_slug => $addon_config) {

			if (isset($_POST[$addon_slug . '_license'])) {

				$license = isset($_POST[$addon_slug . '_license']) ? sanitize_text_field($_POST[$addon_slug . '_license']) : '';

				$product_license = is_array($all_licenses) && isset($all_licenses[$addon_slug]) ? $all_licenses[$addon_slug] : array();

				$product_license['license_key'] = $license;

				$product_license['id'] = isset($addon_config['id']) ? sanitize_text_field($addon_config['id']) : '';

				$product_license['label'] = isset($addon_config['label']) ? sanitize_text_field($addon_config['label']) : $addon_slug;

				$product_license = apply_filters('easy_invoice_addon_before_license_update', $product_license, $addon_slug);
			} else {
				$product_license = $all_licenses[$addon_slug] ?? array();
			}

			$all_valid_licenses[$addon_slug] = $product_license;
		}

		self::update_license_details($all_valid_licenses);
	}

	public function license_page()
	{
		$premium_addons = easy_invoice_get_premium_addons();



		$message = '';

		if (isset($_POST['easy_invoice_license_save_button'])) {

			$nonce_value = isset($_POST['_wpnonce']) ? sanitize_text_field($_POST['_wpnonce']) : '';

			if (wp_verify_nonce($nonce_value, 'easy_invoice_license_save_nonce')) {

				$message = __('License updated. Please check license status and notice for more details.', 'easy-invoice');

				$this->update_license();

			}


		}
		$all_license_details = self::get_license_details();

		echo '<div class="wrap easy-invoice-license-page-wrap">';

		easy_invoice_load_admin_template('License.License', array('addons' => $premium_addons, 'message' => $message, 'license_details' => $all_license_details));

		echo '</div>';
	}

	public function license_scripts($hook)
	{
		if ('easy-invoice_page_ei-license' != $hook) {
			return;
		}


		wp_enqueue_style('easy-invoice-license-style', EASY_INVOICE_PLUGIN_URI . '/assets/admin/css/license.css', array(), EASY_INVOICE_VERSION);
		wp_enqueue_script('easy-invoice-license-script', EASY_INVOICE_PLUGIN_URI . '/assets/admin/js/license.js', array('jquery'), EASY_INVOICE_VERSION);
		$data =
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'update_license_nonce' => wp_create_nonce('easy_invoice_update_license_nonce'),
				'update_license_action' => 'easy_invoice_update_single_license',
				'deactivate_license_nonce' => wp_create_nonce('easy_invoice_deactivate_license_nonce'),
				'deactivate_license_action' => 'easy_invoice_deactivate_single_license'
			);
		wp_localize_script('easy-invoice-license-script', 'easyInvoiceLicenseScript', $data);

	}
}
