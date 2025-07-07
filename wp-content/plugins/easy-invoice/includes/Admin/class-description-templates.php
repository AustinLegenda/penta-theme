<?php

namespace MatrixAddons\EasyInvoice\Admin;

if (!defined('ABSPATH')) {
	exit;
}

class Description_Templates {

	public static function init() {
		// Render the template editor below the General Settings tab
		add_action('easy_invoice_admin_after_general_settings', [__CLASS__, 'render_template_editor']);

		// Save template data on settings submit
		add_action('admin_init', [__CLASS__, 'save_templates']);
	}

	public static function render_template_editor() {
		$templates = get_option('easy_invoice_description_templates', []);
		if (!is_array($templates)) {
			$templates = [];
		}

		echo '<h2 style="margin-top: 40px;">' . esc_html__('Invoice Description Templates', 'easy-invoice') . '</h2>';
		echo '<form method="post">';
		wp_nonce_field('save_invoice_templates', 'invoice_templates_nonce');

		$count = max(1, count($templates));

		for ($i = 0; $i < $count; $i++) {
			$title   = esc_attr($templates[$i]['title'] ?? '');
			$content = $templates[$i]['content'] ?? '';

			echo '<div style="margin-bottom: 30px;">';
			echo '<p><strong>' . esc_html__('Template', 'easy-invoice') . ' ' . ($i + 1) . '</strong></p>';
			echo '<input type="text" name="invoice_templates['.$i.'][title]" value="' . $title . '" placeholder="Template Title" style="width: 100%; margin-bottom: 10px;" />';

			wp_editor($content, 'invoice_template_content_' . $i, [
				'textarea_name' => 'invoice_templates['.$i.'][content]',
				'textarea_rows' => 6,
				'teeny'         => true,
				'media_buttons' => false,
			]);

			echo '</div><hr>';
		}

		echo '<p><button type="submit" class="button button-primary">' . esc_html__('Save Templates', 'easy-invoice') . '</button></p>';
		echo '</form>';
	}

	public static function save_templates() {
		if (isset($_POST['invoice_templates_nonce']) && wp_verify_nonce($_POST['invoice_templates_nonce'], 'save_invoice_templates')) {
			$clean = [];

			foreach ($_POST['invoice_templates'] ?? [] as $template) {
				if (!empty($template['title']) && !empty($template['content'])) {
					$clean[] = [
						'title'   => sanitize_text_field($template['title']),
						'content' => wp_kses_post($template['content']),
					];
				}
			}

			update_option('easy_invoice_description_templates', $clean);
		}
	}
}

Description_Templates::init();
