<?php

namespace MatrixAddons\EasyInvoice\Meta;

use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\ClientFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\CurrencyFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\DiscountFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\InvoiceDescriptionFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\InvoiceFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\LineItemFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\PaymentFields;
use MatrixAddons\EasyInvoice\Admin\Fields\Invoices\TaxFields;
use MatrixAddons\EasyInvoice\Constant;

class Payments
{

	public function metabox()
	{
		$current_screen = get_current_screen();

		$screen_id = $current_screen->id ?? '';

		if ($screen_id != CONSTANT::PAYMENT_POST_TYPE) {
			return;
		}


		add_action('edit_form_after_editor', array($this, 'payments_template'));


	}


	public function save($post_id)
	{

		if (get_post_type($post_id) !==CONSTANT::PAYMENT_POST_TYPE) {
			return;
		}


		$paymentFields = new PaymentFields();

		$paymentFields->save($_POST, $post_id);

	}

	public function payments_template()
	{

		easy_invoice_load_admin_template('Metabox.Payments');
	}

	public function scripts()
	{
		$screen = get_current_screen();

		$screen_id = $screen->id ?? '';

		if ($screen_id != CONSTANT::PAYMENT_POST_TYPE) {
			return;
		}
		wp_enqueue_media();


		wp_register_style('easy-invoice-flatpicker-style', EASY_INVOICE_PLUGIN_URI . '/assets/lib/flatpicker/flatpicker.min.css', array(), EASY_INVOICE_VERSION);
		wp_register_script('easy-invoice-flatpicker-script', EASY_INVOICE_PLUGIN_URI . '/assets/lib/flatpicker/flatpicker.min.js', array('jquery'), EASY_INVOICE_VERSION);


		wp_enqueue_style('easy-invoice-admin-style', EASY_INVOICE_PLUGIN_URI . '/assets/admin/css/easy-invoice-admin.css', array('easy-invoice-flatpicker-style'), EASY_INVOICE_VERSION);
		wp_enqueue_script('easy-invoice-admin-script', EASY_INVOICE_PLUGIN_URI . '/assets/admin/js/easy-invoice-admin.js', array('easy-invoice-flatpicker-script'), EASY_INVOICE_VERSION, true);
		wp_localize_script('easy-invoice-admin-script', 'easyInvoiceAdminParams', array(
			'currency_position' => easy_invoice_get_currency_position(),
			'thousand_separator' => easy_invoice_get_thousand_separator(),
			'decimal_separator' => easy_invoice_get_decimal_separator(),
			'number_decimals' => easy_invoice_get_price_number_decimals(),
			'currency_symbol_type' => get_option('easy_invoice_currency_symbol_type', 'symbol')
		));


	}


	public function hide_screen_option($show_screen)
	{
		if (get_current_screen()->post_type === 'easy-invoice') {

			return false;
		}
		return $show_screen;

	}

	public static function init()
	{
		$self = new self();
		add_filter('screen_options_show_screen', array($self, 'hide_screen_option'));
		add_action('add_meta_boxes', array($self, 'metabox'));
		//add_action('save_post', array($self, 'save'));
		add_action('admin_enqueue_scripts', array($self, 'scripts'), 10);

	}

}

