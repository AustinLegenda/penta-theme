<?php

namespace MatrixAddons\EasyInvoice\Hooks;

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Hooker;
use MatrixAddons\EasyInvoice\PDF;
use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;
use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;
use MatrixAddons\EasyInvoice\Repositories\QuoteRepository;

class Template
{
	public function __construct()
	{
		add_filter('single_template', array($this, 'invoice_template'), 999);
		add_filter('template_redirect', array($this, 'pdf_generate'), 11);
		add_action('easy_invoice_after_register_post_type', 'easy_invoice_maybe_flush_rewrite_rules');
		add_action('easy_invoice_payment_page', array($this, 'payment_page'), 10, 1);
		add_action('template_redirect', array($this, 'pay_now_action'));
	}


	public function invoice_template($template)
	{

		if (get_post_type() == Constant::INVOICE_POST_TYPE) {

			if (!post_password_required()) {

				/** @var InvoiceRepository $ei_invoice */
				global $ei_invoice;

				$template = easy_invoice_get_template('single-invoice');

				$ei_invoice = new InvoiceRepository(get_the_ID());

			}

		} else if (get_post_type() == Constant::QUOTE_POST_TYPE) {

			if (!post_password_required()) {

				/** @var QuoteRepository $ei_quote */
				global $ei_quote;

				$template = easy_invoice_get_template('single-quote');

				$ei_quote = new QuoteRepository(get_the_ID());


			}
		}

		return $template;
	}

	public function pdf_generate()
	{

		$nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';

		$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';

		$object_id = get_the_ID();

		$post_type = get_post_type($object_id);

		if (!wp_verify_nonce($nonce, $action) || ($post_type !== Constant::INVOICE_POST_TYPE && $post_type !== Constant::QUOTE_POST_TYPE)) {
			return;
		}

		add_filter('easy_invoice_get_attachment_image_url', array($this, 'modify_attachment_src'), 10, 2);

		$template_hook = $post_type == Constant::QUOTE_POST_TYPE ? easy_invoice_get_hook('QuoteTemplate') : easy_invoice_get_hook('InvoiceTemplate');

		remove_action('easy_invoice_content', array($template_hook, 'header'), 15);

		remove_action('easy_invoice_content', array($template_hook, 'footer'), 55);

		ob_start();

		if ($post_type === Constant::QUOTE_POST_TYPE) {

			global $ei_quote;

			$ei_quote = new QuoteRepository($object_id);

			easy_invoice_load_template('single-quote');

		} else {

			global $ei_invoice;

			$ei_invoice = new InvoiceRepository($object_id);

			easy_invoice_load_template('single-invoice');
		}


		$content = ob_get_clean();

		$header = '';

		$footer = '';

		if (!is_null($template_hook)) {

			ob_start();

			$template_hook->header();

			$header = ob_get_clean();

			ob_start();

			$template_hook->footer();

			$footer = ob_get_clean();
		}

		$pdf = new PDF();


		$pdf->generate_pdf($content, $header, $footer);

		exit;
	}

	public function modify_attachment_src($src, $image_id)
	{
		if (absint($image_id) < 1) {
			return $src;
		}
		return wp_get_original_image_path($image_id);
	}

	public function payment_page($invoice_id)
	{
		easy_invoice_load_template('invoice.payment-page', array('invoice_id' => $invoice_id, 'error' => ''));
	}

	public function pay_now_action()
	{
		$redirect = !wp_doing_ajax();

		$nonce_value = easy_invoice_get_var($_REQUEST['ei_nonce'], easy_invoice_get_var($_REQUEST['_wpnonce'], '')); // @codingStandardsIgnoreLine.


		if (!wp_verify_nonce($nonce_value, 'ei_pay_now_nonce')) {
			return;

		}

		if (empty($_POST['action']) || 'ei_pay_now_action' !== $_POST['action']) {
			return;
		}

		$invoice_id = isset($_POST['invoice_id']) ? absint($_POST['invoice_id']) : 0;


		$payment_gateway_id = isset($_POST['easy_invoice_payment_gateway']) ? sanitize_text_field($_POST['easy_invoice_payment_gateway']) : '';

		$active_payment_gateways = easy_invoice_get_active_payment_gateways();

		easy_invoice_set_requested('easy_invoice_payment_gateway', $payment_gateway_id);

		if (count($active_payment_gateways) < 1) {

			easy_invoice_redirect_with_error(1200, 'Invalid payment gateway. Please contact your site administrator.');

			return;
		}
		if (!in_array($payment_gateway_id, $active_payment_gateways)) {

			easy_invoice_redirect_with_error(1201, 'Invalid payment gateway id. Please contact your site administrator.');

			return;
		}
		if ($invoice_id < 1) {

			easy_invoice_redirect_with_error(1203, 'Invalid invoice.');

			return;
		}

		if (get_post_type($invoice_id) != Constant::INVOICE_POST_TYPE) {

			easy_invoice_redirect_with_error(1204, 'Invalid invoice type.');

			return;
		}

		if (get_post_status($invoice_id) != 'publish') {

			easy_invoice_redirect_with_error(1205, 'Permission denied on this invoice.');

			return;
		}

		$invoice = new InvoiceRepository($invoice_id);

		if (!$invoice->is_invoice_payable()) {

			easy_invoice_redirect_with_error(1206, 'This invoice is not payable. Please contact your site administrator.');

			return;
		}
		$is_validate = apply_filters('easy_invoice_validate_payment_gateway_request_' . $payment_gateway_id, true);

		if (!$is_validate) {

			return;
		}

		$payment_id = PaymentRepository::create($invoice_id, $payment_gateway_id);

		do_action('easy_invoice_payment_checkout_payment_gateway_' . $payment_gateway_id, $payment_id);

		if ($redirect) {

			$thank_you_page = get_option('easy_invoice_thankyou_page', '');

			$page_permalink = absint($thank_you_page) > 0 ? get_permalink($thank_you_page) : home_url();

			wp_safe_redirect($page_permalink);

			exit;

		} else {
			return ['payment_id' => $payment_id];
		}
	}


}
