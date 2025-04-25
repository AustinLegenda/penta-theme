<?php

namespace MatrixAddons\EasyInvoice\Updater;

class AddonUpdaterActions
{
	private static $endpoint = 'https://matrixaddons.com/edd-sl-api/?';


	public static function auto_updater($plugin_data, $license, $product_id, $plugin_name, $plugin_slug)
	{

		$args = array(
			'version' => $plugin_data['Version'] ?? '',
			'license' => $license,
			'author' => $plugin_data['AuthorName'] ?? ''
		);

		$args['item_id'] = $product_id;


		new AddonUpdateHandler(
			self::$endpoint,
			$plugin_name, $plugin_slug,
			$args
		);
	}

	/**
	 * Attempt to activate a plugin license.
	 *
	 * @return string JSON response
	 */
	public static function activate($api_params)
	{
		$defaults = array(
			'url' => home_url(),
			'edd_action' => 'activate_license',
			'item_id' => '',
		);

		$api_params = wp_parse_args($defaults, $api_params);

		// Call the API.
		$response = wp_remote_post(
			self::$endpoint,
			array(
				'timeout' => 15,
				'body' => $api_params,
				'sslverify' => apply_filters('https_local_ssl_verify', false),
			)
		);

		// Make sure there are no errors.
		if (is_wp_error($response)) {
			return json_encode(
				array(
					'error_code' => $response->get_error_code(),
					'error' => $response->get_error_message(),
				)
			);
		}

		if (wp_remote_retrieve_response_code($response) != 200) {
			return json_encode(
				array(
					'error_code' => wp_remote_retrieve_response_code($response),
					'error' => 'Error code: ' . wp_remote_retrieve_response_code($response),
				)
			);
		}

		// Tell WordPress to look for updates.
		set_site_transient('update_plugins', null);

		return wp_remote_retrieve_body($response);
	}

	/**
	 * Attempt to deactivate a plugin license.
	 */
	public static function deactivate($api_params)
	{
		$defaults = array(
			'url' => home_url(),
			'edd_action' => 'deactivate_license',
			'item_id' => '',
		);

		$api_params = wp_parse_args($defaults, $api_params);

		// Call the API.
		$response = wp_remote_post(
			self::$endpoint,
			array(
				'timeout' => 15,
				'body' => $api_params,
				'sslverify' => apply_filters('https_local_ssl_verify', false),
			)
		);

		if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
			return false;
		} else {
			return wp_remote_retrieve_body($response);
		}
	}
}

