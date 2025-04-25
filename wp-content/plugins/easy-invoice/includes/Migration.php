<?php

namespace MatrixAddons\EasyInvoice;

class Migration
{
	public static function init()
	{
		$self = new self();
		$self->run_migration();
	}

	public function run_migration()
	{
		if (get_option('easy_invoice_version') != EASY_INVOICE_VERSION) {
			update_option('easy_invoice_version', EASY_INVOICE_VERSION);
		}
		if (!get_option('easy_invoice_first_install_time')) {
			add_option('easy_invoice_first_install_time', time() + (get_option('gmt_offset') * HOUR_IN_SECONDS));
		}
	}
}
