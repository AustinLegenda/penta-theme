<?php

namespace MatrixAddons\EasyInvoice;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}


class Background
{

	public static function init()
	{
		include_once EASY_INVOICE_ABSPATH . 'includes/Helpers/background.php';

		$self = new self;

		add_action('easy_invoice_scheduled_events', array($self, 'run_events'));
	}

	public function run_events()
	{
		easy_invoice_update_overdue_status();
	}


}
