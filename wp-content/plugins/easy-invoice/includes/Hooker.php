<?php

namespace MatrixAddons\EasyInvoice;

use MatrixAddons\EasyInvoice\Hooks\Buttons;
use MatrixAddons\EasyInvoice\Hooks\Currency;
use MatrixAddons\EasyInvoice\Hooks\Gateway;
use MatrixAddons\EasyInvoice\Hooks\InvoiceTemplate;
use MatrixAddons\EasyInvoice\Hooks\ListTable;
use MatrixAddons\EasyInvoice\Hooks\PostType;
use MatrixAddons\EasyInvoice\Hooks\QuoteTemplate;
use MatrixAddons\EasyInvoice\Hooks\Template;

class Hooker
{
	public static function init()
	{
		global $easy_invoice_hooks;
		$easy_invoice_hooks["Template"] = new Template();
		$easy_invoice_hooks["InvoiceTemplate"] = new InvoiceTemplate();
		$easy_invoice_hooks["QuoteTemplate"] = new QuoteTemplate();
		$easy_invoice_hooks["Buttons"] = new Buttons();
		if (is_admin()) {
			$easy_invoice_hooks["PostType"] = new PostType();
			$easy_invoice_hooks["ListTable"] = new ListTable();
		}
		do_action('easy_invoice_hooker_loaded');
	}
}
