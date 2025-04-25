<?php

namespace MatrixAddons\EasyInvoice\Hooks;

use MatrixAddons\EasyInvoice\Repositories\InvoiceRepository;

class PostType
{
	public function __construct()
	{
		add_action('wp_insert_post', array($this, 'save_invoice'), 20, 3);
	}

	public function save_invoice($post_id, $post, $update)
	{
		if ($update) {
			return;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if ('easy-invoice' !== $post->post_type) {
			return;
		}

	}
}

