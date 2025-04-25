<?php

namespace MatrixAddons\EasyInvoice\Repositories;

use MatrixAddons\EasyInvoice\Constant;
use MatrixAddons\EasyInvoice\Models\LineItemModel;

class QuoteRepository
{
	protected $quote_id;

	public function __construct($quote_id = null)
	{
		$this->quote_id = is_null($quote_id) ? get_the_ID() : floatval($quote_id);
	}

	public function get_title()
	{
		return get_the_title($this->quote_id);
	}

	public function get_id()
	{
		return $this->quote_id;
	}

	public function get_line_items()
	{
		$line_items = get_post_meta($this->quote_id, 'easy_invoice_quote_line_items', true);

		$line_items = is_array($line_items) ? $line_items : array();

		return LineItemModel::map($line_items);

	}

	public function get_description()
	{
		return get_post_meta($this->quote_id, 'description', true);
	}

	public function get_client_email()
	{
		return get_post_meta($this->quote_id, 'client_email', true);
	}

	public function get_client_name()
	{
		return get_post_meta($this->quote_id, 'client_name', true);
	}

	public function get_client_url()
	{
		return get_post_meta($this->quote_id, 'client_url', true);
	}


	public function get_client_additional_info()
	{
		return get_post_meta($this->quote_id, 'additional_info', true);
	}

	public function get_quote_status()
	{
		return get_post_meta($this->quote_id, 'quote_status', true);
	}

	public function get_quote_number()
	{
		return get_post_meta($this->quote_id, 'quote_number', true);
	}

	public function get_order_number()
	{
		return get_post_meta($this->quote_id, 'order_number', true);
	}

	public function get_created_date()
	{
		return get_post_meta($this->quote_id, 'created_date', true);
	}

	public function get_valid_until()
	{
		return get_post_meta($this->quote_id, 'valid_until', true);
	}

	public function get_currency()
	{
		return get_post_meta($this->quote_id, 'currency', true);
	}

	public function get_currency_symbol()
	{
		return get_post_meta($this->quote_id, 'currency_symbol', true);
	}

	public function get_tax_type()
	{
		return get_post_meta($this->quote_id, 'tax_type', true);
	}

	public function get_tax_rate()
	{
		return get_post_meta($this->quote_id, 'tax_rate', true);
	}

	public function get_quote_log()
	{
		return get_post_meta($this->quote_id, 'quote_log', true);
	}

	public function get_terms_conditions()
	{
		$terms = get_post_meta($this->quote_id, 'terms_and_conditions', true);

		return !$terms ? get_option('easy_invoice_quote_terms_conditions', 'This estimate has a fixed price. Upon acceptance, we kindly ask for a 25% deposit prior to initiating the work.') : $terms;
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
		return floatval(get_post_meta($this->quote_id, 'discount', true));

	}

	public function get_discount_type()
	{
		return get_post_meta($this->quote_id, 'discount_type', true);

	}

	public function get_discount_calculation_method()
	{
		return get_post_meta($this->quote_id, 'discount_calculation_method', true);

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

	public function get_total_paid()
	{
		return 0;
	}

	public function get_due_amount()
	{
		return floatval($this->get_sub_total() + $this->get_tax_amount() - $this->get_discount_amount() - $this->get_total_paid());
	}


}
