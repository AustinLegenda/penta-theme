<?php
if (!function_exists('easy_invoice_update_overdue_status')) {
	function easy_invoice_update_overdue_status()
	{
		global $wpdb;

		$invoices = $wpdb->get_results(
			$wpdb->prepare(
				"
            SELECT p.ID
            FROM {$wpdb->posts} AS p
            INNER JOIN {$wpdb->postmeta} AS pm_status ON p.ID = pm_status.post_id
            INNER JOIN {$wpdb->postmeta} AS pm_due_date ON p.ID = pm_due_date.post_id
            WHERE p.post_type = %s
            AND pm_status.meta_key = 'invoice_status'
            AND pm_status.meta_value = 'available'
            AND pm_due_date.meta_key = 'due_date'
            AND STR_TO_DATE(pm_due_date.meta_value, '%%M %%d, %%Y') < CURDATE()
            ",
				\MatrixAddons\EasyInvoice\Constant::INVOICE_POST_TYPE
			)
		);
		foreach ($invoices as $invoice) {
			easy_invoice_update_invoice_status($invoice->ID, 'overdue');
		}

		return count($invoices);

	}
}
