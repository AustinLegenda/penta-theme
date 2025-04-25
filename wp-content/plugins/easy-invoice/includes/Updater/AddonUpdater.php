<?php

namespace MatrixAddons\EasyInvoice\Updater;

use MatrixAddons\EasyInvoice\Admin\LicenseManager;

abstract class AddonUpdater
{
	/**
	 * Plugin File.
	 *
	 * @var string
	 */
	protected $plugin_id;
	/**
	 * Plugin File.
	 *
	 * @var string
	 */
	protected $item_name;

	/**
	 * Plugin File.
	 *
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * Plugin File.
	 *
	 * @var string
	 */
	private $license_key = '';

	/**
	 * Plugin Name.
	 *
	 * @var string
	 */
	private $plugin_name = '';

	/**
	 * Plugin Slug.
	 *
	 * @var string
	 */
	private $plugin_slug = '';

	/**
	 * Plugins data.
	 *
	 * @var array of strings
	 */
	private $plugin_data = array();


	/**
	 * Constructor.
	 */
	public function __construct()
	{

		$this->init_updates();
		$this->hooks();
	}

	/**
	 * Init the updater.
	 */
	public function init_updates()
	{
		$this->plugin_slug = str_replace('.php', '', basename($this->plugin_file));
		$this->plugin_name = basename(dirname($this->plugin_file)) . '/' . $this->plugin_slug . '.php';
		register_deactivation_hook($this->plugin_name, array($this, 'plugin_deactivation'));

	}

	public function hooks()
	{
		add_filter('block_local_requests', '__return_false');
		add_action('admin_init', array($this, 'admin_init'));
		add_filter('easy_invoice_premium_addons', array($this, 'addon_config'), 10, 1);
		add_filter('easy_invoice_addon_before_license_update', array($this, 'activate'), 10, 2);
		add_filter('easy_invoice_addon_before_license_deactivate', array($this, 'deactivate'), 10, 2);
	}

	/**
	 * Run on admin init.
	 */
	public function admin_init()
	{

		$product_license = LicenseManager::get_license_details($this->plugin_slug);

		$this->license_key = isset($product_license['license_key']) ? sanitize_text_field($product_license['license_key']) : '';

		$this->plugin_data = get_plugin_data($this->plugin_file);

		AddonUpdaterActions::auto_updater($this->plugin_data, $this->license_key, $this->plugin_id, $this->plugin_name, $this->plugin_slug);

	}

	public function addon_config($config)
	{
		$config[$this->plugin_slug] = array(

			'label' => $this->item_name,

			'id' => $this->plugin_id
		);
		return $config;
	}

	public function activate($license_details, $addon_slug)
	{
		if ($addon_slug !== $this->plugin_slug) {

			return $license_details;
		}
		$new_license_key = $license_details['license_key'] ?? '';

		$response = $this->activate_license($new_license_key);

		$license_details['status'] = $response['status'] === 'valid' ? 'active' : 'inactive';

		$license_details['notice'] = $response['notice'];

		$license_details['server_response'] = $response['server_response'];

		return $license_details;


	}

	public function deactivate($license_details, $addon_slug)
	{
		if ($addon_slug !== $this->plugin_slug) {

			return $license_details;
		}

		$this->deactivate_license();

		$updated_license_details['status'] = 'inactive';

		$updated_license_details['notice'] = __('License inactive', 'easy-invoice');

		return $updated_license_details;


	}


	/**
	 * Ran on plugin-deactivation.
	 */
	public function plugin_deactivation()
	{
		$all_licenses = LicenseManager::get_license_details();

		if (isset($all_licenses[$this->plugin_slug])) {

			$product_license = $all_licenses[$this->plugin_slug];

			$product_license = $this->deactivate($product_license, $this->plugin_slug);

			$all_licenses[$this->plugin_slug] = $product_license;

			LicenseManager::update_license_details($all_licenses);
		}
	}


	/**
	 * Try to activate a license.
	 */
	public function activate_license($license_key)
	{
		$status = false;

		$message = '';

		try {

			if (empty($license_key)) {
				throw new \Exception('Empty license key');
			}
			$license_args = array(
				'license' => $license_key,
				'item_id' => $this->plugin_id,
				'item_name' => rawurlencode($this->item_name), // the name of our product in EDD

			);

			$activate_results = json_decode(
				AddonUpdaterActions::activate(
					$license_args
				)
			);


			if (!empty($activate_results) && is_object($activate_results)) {

				if (isset($activate_results->error_code)) {
					throw new \Exception($activate_results->error);

				} elseif (false === $activate_results->success) {


					switch ($activate_results->error) {
						case 'expired':
							$error_msg = sprintf(__('The provided license key expired on %1$s. Please <a href="%2$s" target="_blank">renew your license key</a>.', 'easy-invoice'), date_i18n(get_option('date_format'), strtotime($activate_results->expires, current_time('timestamp'))), 'https://matrixaddons.com/checkout/?edd_license_key=' . $license_key . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired');
							break;

						case 'revoked':
							$error_msg = sprintf(__('The provided license key has been disabled. Please <a href="%s" target="_blank">contact support</a> for more information.', 'easy-invoice'), 'https://matrixaddons.com/contact-us?utm_campaign=admin&utm_source=licenses&utm_medium=revoked');
							break;

						case 'missing':
							$error_msg = sprintf(__('The provided license is invalid. Please <a href="%s" target="_blank">visit your account page</a> and verify it.', 'easy-invoice'), 'https://matrixaddons.com/checkout/order-history/?utm_campaign=admin&utm_source=licenses&utm_medium=missing');
							break;

						case 'invalid':
						case 'site_inactive':
							$error_msg = sprintf(__('The provided license is not active for this URL. Please <a href="%s" target="_blank">visit your account page</a> to manage your license key URLs.', 'easy-invoice'), 'https://matrixaddons.com/checkout/order-history/?utm_campaign=admin&utm_source=licenses&utm_medium=missing');
							break;

						case 'invalid_item_id':
						case 'item_name_mismatch':
							$error_msg = sprintf(__('This appears to be an invalid license key for <strong>%1$s</strong>.', 'easy-invoice'), $this->plugin_data['Name']);
							break;

						case 'no_activations_left':
							$error_msg = sprintf(__('The provided license key has reached its activation limit. Please <a href="%1$s" target="_blank">View possible upgrades</a> now.', 'easy-invoice'), 'https://matrixaddons.com/checkout/order-history/');
							break;

						case 'license_not_activable':
							$error_msg = __('The key you entered belongs to a bundle, please use the product specific license key.', 'easy-invoice');
							break;

						default:
							$error_msg = sprintf(__('The provided license key could not be found. Please <a href="%s" target="_blank">contact support</a> for more information.', 'easy-invoice'), 'https://matrixaddons.com/contact-us/');
							break;
					}

					$status = $activate_results->error;

					$message = (sprintf(__('<strong>Activation error:</strong> %1$s', 'easy-invoice'), $error_msg));

				} elseif ('valid' === $activate_results->license) {

					$this->license_key = $license_key;

					$status = $activate_results->license;

					$message = __('License successfully activated', 'easy-invoice');

				} else {

					throw new \Exception('License could not activate. Please contact support.');
				}
			} else {
				throw new \Exception('Connection failed to the License Key API server - possible server issue.');
			}
		} catch (\Exception $e) {

			$message = $e->getMessage();

			$status = false;

			$activate_results = array(
				'Undefined error code or something wrong',
				'catch from exceptions'
			);

		}
		return ['status' => $status, 'notice' => $message, 'server_response' => $activate_results];
	}

	/**
	 * Deactivate a license.
	 */
	public
	function deactivate_license()
	{
		AddonUpdaterActions::deactivate(
			array(
				'license' => $this->license_key,
			)
		);

		$cache_key = md5(serialize($this->plugin_slug . $this->license_key . false));

		delete_option($cache_key);

		$this->license_key = '';
	}


}
