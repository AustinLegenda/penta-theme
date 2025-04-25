<?php

use MatrixAddons\EasyInvoice\Repositories\PaymentRepository;

if (!function_exists('easy_invoice_get_payment_gateways')) {
	function easy_invoice_get_payment_gateways()
	{
		return apply_filters('easy_invoice_payment_gateways', array());
	}
}

if (!function_exists('easy_invoice_get_active_payment_gateways')) {

	function easy_invoice_get_active_payment_gateways()
	{
		$easy_invoice_payment_gateways = get_option('easy_invoice_payment_gateways', array());

		return array_keys($easy_invoice_payment_gateways);
	}
}


if (!function_exists('easy_invoice_get_payment_gateway_lists')) {

	function easy_invoice_get_payment_gateway_lists()
	{
		$gateways = easy_invoice_get_payment_gateways();

		$gateways_lists = array('' => 'Not selected');

		foreach ($gateways as $gateway) {

			if (isset($gateway['id']) && isset($gateway['title'])) {

				$gateways_lists[$gateway['id']] = $gateway['title'];
			}

		}
		return $gateways_lists;
	}
}

function easy_invoice_payment_gateway_test_mode()
{

	$is_test_mode = get_option('easy_invoice_payment_gateway_test_mode');

	if ($is_test_mode == 'yes') {
		return true;
	}
	return false;
}

function easy_invoice_update_payment_status($payment_id, $status, $paid_amount, $transaction_id = '')
{
	if (!$payment_id || absint($payment_id) < 1) {
		return;
	}

	do_action('easy_invoice_before_update_payment_status', $payment_id, $status, $paid_amount);

	$payment = new PaymentRepository($payment_id);

	$payment->update_paid_amount($paid_amount);

	$payment->update_due_amount(0);

	$payment->update_status($status);

	if ($status === 'publish') {

		$payment->update_transaction_id($transaction_id);
	}

	$net_due_amount = $payment->get_net_due_amount();

	$invoice_id = $payment->get_invoice_id();

	if ($net_due_amount > 0) {

		easy_invoice_update_invoice_status($invoice_id, 'partial');

	} else {

		easy_invoice_update_invoice_status($invoice_id, 'paid');
	}

	do_action('easy_invoice_after_update_payment_status', $payment_id, $status, $paid_amount);

}


if (!function_exists('easy_invoice_payment_gateway_fields')) {

	function easy_invoice_payment_gateway_fields()
	{
		$easy_invoice_get_active_payment_gateways = (easy_invoice_get_active_payment_gateways());

		$easy_invoice_get_payment_gateways = easy_invoice_get_payment_gateways();

		$number_of_gateways = 1;

		if (count($easy_invoice_get_active_payment_gateways) > 0) {

			$active_payment_gateway = easy_invoice_get_requested('easy_invoice_payment_gateway');

			$active_payment_gateway = $active_payment_gateway === '' ? $easy_invoice_get_payment_gateways[0]['id'] : $active_payment_gateway;

			echo '<ul class="easy-invoice-payment-gateway">';

			foreach ($easy_invoice_get_payment_gateways as $gateway) {

				$gateway_id = $gateway['id'] ?? '';

				if (in_array($gateway_id, $easy_invoice_get_active_payment_gateways)) {

					$checked = $active_payment_gateway === $gateway_id ? 'checked="checked"' : '';

					$hide_class = $active_payment_gateway != $gateway_id ? 'easy-invoice-hide' : '';

					echo '<li>';

					echo '<label for="easy-invoice-payment-gateway-' . esc_attr($gateway_id) . '">';

					echo '<input ' . $checked . ' type="radio" id="easy-invoice-payment-gateway-' . esc_attr($gateway_id) . '" name="easy_invoice_payment_gateway" value="' . esc_attr($gateway_id) . '"/>';

					echo '&nbsp;<span>' . $gateway['frontend_title'] . '</span>';

					echo '</label>';

					echo '<div class="easy-invoice-payment-gateway-field-wrap easy-invoice-payment-gateway-field-' . $gateway_id . ' ' . esc_attr($hide_class) . '">';

					do_action('easy_invoice_payment_gateway_field_' . $gateway_id);

					echo '</div>';

					echo '</li>';

					$number_of_gateways++;
				}

			}
			echo '</ul>';
		}
	}
}

