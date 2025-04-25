<?php

namespace MatrixAddons\EasyInvoice\Hooks;

class Currency
{
	public function __construct()
	{
		add_filter('easy_invoice_currency', array($this, 'currency'));

		add_filter('easy_invoice_currency_symbol', array($this, 'currency_symbol'));
	}

	public function currency($currency)
	{
		global $ei_invoice;

		if (!is_singular('easy-invoice')) {

			return $currency;
		}
		return $ei_invoice->get_currency() === '' ? $currency : $ei_invoice->get_currency();
 	}

	public function currency_symbol($currency_symbol)
	{
		global $ei_invoice;

		if (!is_singular('easy-invoice')) {

			return $currency_symbol;
		}
		return $ei_invoice->get_currency_symbol() === '' ? $currency_symbol : $ei_invoice->get_currency_symbol();
	}


}
