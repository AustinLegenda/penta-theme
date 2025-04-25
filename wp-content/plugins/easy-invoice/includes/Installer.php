<?php

namespace MatrixAddons\EasyInvoice;

class Installer
{

	public function set_option()
	{
		if (!get_option('easy_invoice_version')) {
			add_option('easy_invoice_version', EASY_INVOICE_VERSION);
		}
		if (!get_option('easy_invoice_first_install_time')) {
			add_option('easy_invoice_first_install_time', time() + (get_option('gmt_offset') * HOUR_IN_SECONDS));
		}
	}

	public static function activate()
	{

		if (!get_option('easy_invoice_version')) {
			$self = new self;

			$self->set_option();
		}

		flush_rewrite_rules(true);
	}

	public static function deactivate()
	{
		// flush rewrite rules
		flush_rewrite_rules();
	}
}
