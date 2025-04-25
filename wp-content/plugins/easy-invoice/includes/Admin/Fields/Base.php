<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields;

use MatrixAddons\EasyInvoice\Admin\HTML;

abstract class Base
{
	public abstract function get_settings();

	public abstract function render();

	public abstract function nonce_id();

	protected function output()
	{
		$settings = $this->get_settings();

		HTML::render($settings);

		wp_nonce_field($this->nonce_id(), $this->nonce_id() . '_nonce');
	}


	public function get_valid_data($data)
	{
		$settings = $this->get_settings();


		return HTML::sanitize($settings, $data);
	}


	public function save($post_data, $post_id)
	{
		if (empty($post_data) || !check_admin_referer($this->nonce_id(), $this->nonce_id() . '_nonce')) {
			return;
		}

		$valid_data = $this->get_valid_data($post_data);

		do_action('easy_invoice_meta_fields_before_save', $post_id, $valid_data, $this->nonce_id());

		foreach ($valid_data as $valid_data_index => $valid_data_item) {

			update_post_meta($post_id, $valid_data_index, $valid_data_item);
		}

		do_action('easy_invoice_meta_fields_after_save', $post_id, $valid_data, $this->nonce_id());

	}
}
