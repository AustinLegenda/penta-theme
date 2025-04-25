<?php

namespace MatrixAddons\EasyInvoice;

use MatrixAddons\EasyInvoice\Admin\Init;
use MatrixAddons\EasyInvoice\Gateways\PaymentGatewayLoader;
use MatrixAddons\EasyInvoice\PostTypes\Invoices;
use MatrixAddons\EasyInvoice\PostTypes\Quotes;
use MatrixAddons\EasyInvoice\PostTypes\Payments;

final class Main
{
	private static $instance = null;

	public static function getInstance()
	{
		if (!self::$instance instanceof Main) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	protected function __construct()
	{
		$this->define_constant();
		register_activation_hook(EASY_INVOICE_FILE, [$this, 'activate']);
		register_deactivation_hook(EASY_INVOICE_FILE, [$this, 'deactivate']);
		$this->load_helpers();
		$this->dispatch_hook();

		do_action('easy_invoice_plugin_loaded');
	}

	public function define_constant()
	{
		define('EASY_INVOICE_ABSPATH', dirname(EASY_INVOICE_FILE) . '/');
		define('EASY_INVOICE_PLUGIN_BASENAME', plugin_basename(EASY_INVOICE_FILE));
		define('EASY_INVOICE_PLUGIN_SLUG', 'easy-invoice');
		define('EASY_INVOICE_ASSETS_DIR_PATH', EASY_INVOICE_PLUGIN_DIR . 'assets/');
		define('EASY_INVOICE_ASSETS_URI', EASY_INVOICE_PLUGIN_URI . 'assets/');
		define('EASY_INVOICE_REST_WEBHOOKS_NAMESPACE', 'easy-invoice/v1/webhooks');
	}

	public function load_helpers()
	{
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/currency.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/template.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/invoice.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/quote.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/text.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/invoice-options.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/quote-options.php';
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/site.php';

	}

	public function init_plugin()
	{
		$this->load_textdomain();
	}

	public function dispatch_hook()
	{
		add_action('init', [$this, 'init_plugin']);
		Assets::init();
		Migration::init();
		Invoices::init();
		Quotes::init();
		Payments::init();
		Meta\Invoice::init();
		Meta\Payments::init();
		Meta\Quote::init();
		Ajax::init();
		Hooker::init();
		Email::init();
		Cron::init();
		Background::init();
		PaymentGatewayLoader::instance()->init();

		if (is_admin()) {
			Init::instance();
		}
		//add_action('admin_notices', array($this, 'admin_notice'));

	}

	public function admin_notice()
	{
		$message = 'Yes writable';
		if (!is_writable(easy_invoice()->get_tmp_pdf_dir(true, true))) {
			$message = 'Not writable';
		}

		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo esc_html($message); ?></p>
		</div>
		<?php
	}

	public function activate()
	{
		Installer::activate();
	}

	public function deactivate()
	{
		Installer::deactivate();
	}

	public function load_textdomain()
	{
		load_plugin_textdomain('easy-invoice', false, dirname(EASY_INVOICE_PLUGIN_BASENAME) . '/languages');
	}


	protected function __clone()
	{
	}

	public function __wakeup()
	{
		throw new \Exception("Cannot unserialize singleton");
	}

	public function get_log_dir($create_if_not_exists = true)
	{
		$wp_upload_dir = wp_upload_dir();

		$log_dir = $wp_upload_dir['basedir'] . '/easy-invoice/';

		if (!file_exists(trailingslashit($log_dir) . 'index.html') && $create_if_not_exists) {

			$files = array(
					array(
							'base' => $log_dir,
							'file' => 'index.html',
							'content' => '',
					),
					array(
							'base' => $log_dir,
							'file' => '.htaccess',
							'content' => 'deny from all',
					)
			);

			$this->create_files($files, $log_dir);


		}
		return $log_dir;
	}

	private function clear_dir($dir)
	{
		if (is_dir($dir)) {
			$objects = scandir($dir);

			foreach ($objects as $object) {
				if ($object != '.' && $object != '..') {
					if (filetype($dir . '/' . $object) == 'dir') {
						$this->clear_dir($dir . '/' . $object);
					} else {
						unlink($dir . '/' . $object);
					}
				}
			}

			reset($objects);

			rmdir($dir);
		}
	}

	public function get_tmp_pdf_dir($create_if_not_exists = true, $force_clear = true)
	{
		$log_dir = $this->get_log_dir(true);

		$tmp_pdf_dir = $log_dir . 'pdf/';

		if ($force_clear) {

			$this->clear_dir($tmp_pdf_dir);
		}

		if (!file_exists(trailingslashit($tmp_pdf_dir) . 'index.html') && $create_if_not_exists) {

			$files = array(
					array(
							'base' => $tmp_pdf_dir,
							'file' => 'index.html',
							'content' => '',
					),
					array(
							'base' => $tmp_pdf_dir,
							'file' => '.htaccess',
							'content' => 'deny from all',
					)
			);

			$this->create_files($files, $tmp_pdf_dir);


		}
		return $tmp_pdf_dir;
	}

	private function create_files($files, $base_dir)
	{
		// Bypass if filesystem is read-only and/or non-standard upload system is used.
		if (apply_filters('easy_invoice_install_skip_create_files', false)) {
			return;
		}

		if (file_exists(trailingslashit($base_dir) . 'index.html')) {
			return true;
		}
		$has_created_dir = false;

		foreach ($files as $file) {
			if (wp_mkdir_p($file['base']) && !file_exists(trailingslashit($file['base']) . $file['file'])) {
				$file_handle = @fopen(trailingslashit($file['base']) . $file['file'], 'w');
				if ($file_handle) {
					fwrite($file_handle, $file['content']);
					fclose($file_handle);
					if (!$has_created_dir) {
						$has_created_dir = true;
					}
				}
			}
		}
		if ($has_created_dir) {
			return true;
		}


	}


}
