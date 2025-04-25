<?php
/**
 * Plugin Name: Easy Invoice
 * Plugin URI: https://matrixaddons.com/wordpress-invoice-plugin
 * Description: Invoicing plugin for WordPress that supports invoice Payment
 * Author: MatrixAddons
 * Author URI: https://profiles.wordpress.org/matrixaddons
 * Version: 1.1.3
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Define EASY_INVOICE_PLUGIN_FILE.
if (!defined('EASY_INVOICE_FILE')) {
	define('EASY_INVOICE_FILE', __FILE__);
}

// Define EASY_INVOICE_VERSION.
if (!defined('EASY_INVOICE_VERSION')) {
	define('EASY_INVOICE_VERSION', '1.1.2');
}

// Define EASY_INVOICE_PLUGIN_URI.
if (!defined('EASY_INVOICE_PLUGIN_URI')) {
	define('EASY_INVOICE_PLUGIN_URI', plugins_url('/', EASY_INVOICE_FILE));
}

// Define EASY_INVOICE_PLUGIN_DIR.
if (!defined('EASY_INVOICE_PLUGIN_DIR')) {
	define('EASY_INVOICE_PLUGIN_DIR', plugin_dir_path(EASY_INVOICE_FILE));
}
/**
 * Initializes the main plugin
 *
 * @return \MatrixAddons\EasyInvoice\Main
 */
if (!function_exists('easy_invoice')) {
	function easy_invoice()
	{
		return \MatrixAddons\EasyInvoice\Main::getInstance();
	}
}



easy_invoice();
