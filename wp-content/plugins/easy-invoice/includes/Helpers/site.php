<?php

use MatrixAddons\EasyInvoice\Constant;

if (!function_exists('easy_invoice_get_domain')) {
	function easy_invoice_get_domain()
	{
		$protocols = array('http://', 'https://', 'http://www.', 'https://www.', 'www.');
		return str_replace($protocols, '', site_url());
	}
}


if (!function_exists('easy_invoice_get_from_address')) {
	function easy_invoice_get_from_address()
	{ ?>
		<!--<div class="title"><strong> echo esc_html(easy_invoice_get_text('from'));</strong></div> add PHP tags back-->

		<div class="site-url"><a target="_blank"
								 href="<?php echo esc_url(site_url()) ?>"><?php echo esc_html(get_option('easy_invoice_business_name', get_bloginfo('name'))); ?></a>
		</div>
		<div class="address">
			<p><?php easy_invoice_print_html_text(wpautop(easy_invoice_business_address())); ?>
			</p>
		</div>
		<div class="extra">
			<p><?php easy_invoice_print_html_text(wpautop(get_option('easy_invoice_business_additional_info'), get_option('admin_email'))); ?></p>
		</div>
		<?php
	}
}


function easy_invoice_maybe_flush_rewrite_rules()
{
	if ('yes' === get_option('easy_invoice_queue_flush_rewrite_rules')) {
		update_option('easy_invoice_queue_flush_rewrite_rules', 'no');
		flush_rewrite_rules();
	}
}

if (!function_exists(('easy_invoice_get_var'))) {
	function easy_invoice_get_var(&$var, $default = null)
	{
		return isset($var) ? $var : $default;
	}
}


if (!function_exists('easy_invoice_get_premium_addons')) {

	function easy_invoice_get_premium_addons()
	{
		return apply_filters('easy_invoice_premium_addons', array());
	}
}

if (!function_exists('easy_invoice_get_hook')) {
	function easy_invoice_get_hook($hook_id)
	{
		global $easy_invoice_hooks;

		if (!is_array($easy_invoice_hooks)) {
			return null;
		}
		if (isset($easy_invoice_hooks[$hook_id])) {
			return $easy_invoice_hooks[$hook_id];
		}
		return null;
	}
}

if (!function_exists('easy_invoice_get_to_address')) {

	function easy_invoice_get_to_address()
	{
		if (get_post_type(get_the_ID()) == Constant::QUOTE_POST_TYPE) {

			easy_invoice_load_template('quote.to-address');

		} else {

			easy_invoice_load_template('invoice.to-address');

		}

	}
}

if (!function_exists('easy_invoice_get_items_html')) {

	function easy_invoice_get_items_html()
	{
		if (get_post_type(get_the_ID()) == Constant::QUOTE_POST_TYPE) {

			easy_invoice_load_template('quote.tables.items');

		} else {

			easy_invoice_load_template('invoice.tables.items');

		}

	}
}

if (!function_exists('easy_invoice_get_total_html')) {

	function easy_invoice_get_total_html()
	{
		if (get_post_type(get_the_ID()) == Constant::QUOTE_POST_TYPE) {

			easy_invoice_load_template('quote.tables.total');

		} else {

			easy_invoice_load_template('invoice.tables.total');

		}

	}
}
