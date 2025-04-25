<?php

namespace MatrixAddons\EasyInvoice\Repositories;

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Models\LineItemModel;

class InvoiceRepository
{
	protected $invoice_id;

	public function __construct($invoice_id = null)
	{
		$this->invoice_id = is_null($invoice_id) ? get_the_ID() : floatval($invoice_id);
	}

	public function get_title()
	{
		return get_the_title($this->invoice_id);
	}

	public function get_id()
	{
		return $this->invoice_id;
	}

	public function get_line_items()
	{
		$line_items = get_post_meta($this->invoice_id, 'easy_invoice_line_items', true);

		$line_items = is_array($line_items) ? $line_items : array();

		return LineItemModel::map($line_items);
	}

	public function get_description()
	{
		return get_post_meta($this->invoice_id, 'description', true);
	}

	public function get_client_email()
	{
		return get_post_meta($this->invoice_id, 'client_email', true);
	}

	public function get_client_name()
	{
		return get_post_meta($this->invoice_id, 'client_name', true);
	}

	public function get_client_url()
	{
		return get_post_meta($this->invoice_id, 'client_url', true);
	}


	public function get_client_additional_info()
	{
		return get_post_meta($this->invoice_id, 'additional_info', true);
	}

	public function get_invoice_status()
	{
		return get_post_meta($this->invoice_id, 'invoice_status', true);
	}

	public function get_invoice_number()
	{
		return get_post_meta($this->invoice_id, 'invoice_number', true);
	}

	public function get_order_number()
	{
		return get_post_meta($this->invoice_id, 'order_number', true);
	}

	public function get_created_date()
	{
		return get_post_meta($this->invoice_id, 'created_date', true);
	}

	public function get_due_date()
	{
		return get_post_meta($this->invoice_id, 'due_date', true);
	}

	public function get_currency()
	{
		return get_post_meta($this->invoice_id, 'currency', true);
	}

	public function get_currency_symbol()
	{
		return get_post_meta($this->invoice_id, 'currency_symbol', true);
	}

	public function get_tax_type()
	{
		return get_post_meta($this->invoice_id, 'tax_type', true);
	}

	public function get_tax_rate()
	{
		return get_post_meta($this->invoice_id, 'tax_rate', true);
	}

	public function get_terms_conditions()
	{
		$terms = get_post_meta($this->invoice_id, 'terms_and_conditions', true);

		return !$terms ? get_option('easy_invoice_terms_conditions', 'Payment is due within 30 days from the date of invoice') : $terms;
	}

	public function get_taxable_amount()
	{
		$line_items = $this->get_line_items();

		$taxable_amount = 0;
		/** @var LineItemModel $line_item */

		foreach ($line_items as $line_item) {


			$taxable_amount += ($line_item->is_taxable() ? floatval($line_item->get_amount()) : 0);
		}

		return $taxable_amount;
	}

	public function get_sub_total()
	{

		$line_items = $this->get_line_items();


		$sub_total = 0;
		/** @var LineItemModel $line_item */

		foreach ($line_items as $line_item) {


			$sub_total += floatval($line_item->get_amount());
		}

		return $sub_total;
	}

	public function get_tax_amount()
	{

		$calculation_value = $this->get_taxable_amount();

		$tax_percentage = floatval($this->get_tax_rate());

		$tax_type = $this->get_tax_type();

		if ($tax_percentage > 0 && $calculation_value > 0) {

			$calculation_method = $this->get_discount_calculation_method();

			if ($calculation_method === "before_tax") {

				$discount_amount = $this->get_discount_amount();


				$calculation_value = $calculation_value - $discount_amount;

				if ($calculation_value < 0 || $calculation_value === 0) {

					return 0;
				}
			}

			if ('inclusive' === $tax_type) {

				return (($calculation_value * $tax_percentage) / (100 + $tax_percentage));
			}
			return ($calculation_value * $tax_percentage) / 100;
		}
		return 0;
	}

	public function get_discount_value()
	{
		return floatval(get_post_meta($this->invoice_id, 'discount', true));
	}

	public function get_discount_type()
	{
		return get_post_meta($this->invoice_id, 'discount_type', true);
	}

	public function get_discount_calculation_method()
	{
		return get_post_meta($this->invoice_id, 'discount_calculation_method', true);
	}

	public function get_discount_amount()
	{
		$discount_value = $this->get_discount_value();

		$discount_type = $this->get_discount_type();

		$calculation_value = $this->get_sub_total();

		if ($discount_value > 0 && $calculation_value > 0) {

			$calculation_method = $this->get_discount_calculation_method();

			if ($calculation_method === "after_tax") {

				$calculation_value = $calculation_value + ($this->get_tax_amount());
			}
			$discount_amount = 0;

			if ($discount_type == 'fixed') {

				$discount_amount = $calculation_value >= $discount_value ? $discount_value : $calculation_value;
			} else {
				$discount_amount = ($calculation_value * $discount_value) / 100;
			}

			return $discount_amount;
		}

		return 0;
	}

	public function get_deposit_amount()
	{
		// Check if invoice is in "Pending Deposit" status
		$invoice_status = get_post_meta($this->get_id(), 'invoice_status', true);

		if ($invoice_status === 'pending_deposit') {
			// Retrieve deposit percentage, default to 50%
			$deposit_percentage = get_post_meta($this->get_id(), 'deposit_percentage', true);
			if (!$deposit_percentage) {
				$deposit_percentage = 0.50; // Default to 50% if no value is set
			}

			// Calculate deposit amount based on subtotal
			$deposit_amount = $this->get_sub_total() * $deposit_percentage;
			return floatval($deposit_amount);
		}

		return 0; // If not in "Pending Deposit" status, no deposit amount applies
	}

	public function get_total_paid()
	{
		return PaymentRepository::get_total_paid_amount_by_invoice_id($this->invoice_id);
	}

	public function get_due_amount()
	{
		// Get the standard due amount
		$due_amount = floatval($this->get_sub_total() + $this->get_tax_amount() - $this->get_discount_amount() - $this->get_total_paid());

		// Check if invoice is in "Pending Deposit" status
		$deposit_amount = $this->get_deposit_amount();
		if ($deposit_amount > 0) {
			return $deposit_amount; // Return only the deposit amount if pending deposit
		}

		return $due_amount; // Otherwise, return full due amount
	}
	
	public function is_invoice_payable()
	{
		if (get_post_status($this->invoice_id) != 'publish') {
			return false;
		}
		if (get_post_type($this->invoice_id) != Constant::INVOICE_POST_TYPE) {
			return false;
		}
		if ($this->get_client_email() == '') {
			return false;
		}

		if ($this->get_currency_symbol() == '') {
			return false;
		}

		if ($this->get_currency() == '') {
			return false;
		}
		if ($this->get_invoice_status() === 'paid' || $this->get_invoice_status() === 'cancelled') {
			return false;
		}
		if ($this->get_due_amount() > 0) {
			return true;
		}
		return false;
	}
}
