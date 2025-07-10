<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Quotes;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class QuoteDescriptionFields extends Base
{
	public function get_settings()
	{
		$templates = function_exists('easy_invoice_get_description_templates')
			? easy_invoice_get_description_templates()
			: [];

		$options = ['' => __('-- Select a template --', 'easy-invoice')];
		foreach ($templates as $key => $tpl) {
			$options[$key] = $tpl['label'];
		}

		return [
			'selected_template' => [
				'title'   => __('Template', 'easy-invoice'),
				'type'    => 'select',
				'class'   => 'easy-invoice-description-templates',
				'options' => $options,
				'name' => '',
			],
			'description' => [
				'type'  => 'editor',
			],
		];
	}

	public function save($post_data, $post_id)
	{
		if (empty($post_data) || !check_admin_referer($this->nonce_id(), $this->nonce_id() . '_nonce')) {
			return;
		}

		// 1. Save selected template
		$sel = isset($post_data['easy_invoice_selected_template'])
			? sanitize_text_field(wp_unslash($post_data['easy_invoice_selected_template']))
			: '';
		update_post_meta($post_id, '_easy_invoice_selected_template', $sel);

		// 2. Save description content to the expected key
		if (isset($post_data['easy_invoice_description'])) {
			$desc = wp_kses_post(wp_unslash($post_data['easy_invoice_description']));
			update_post_meta($post_id, 'description', $desc); // <-- This is key
		}
	}

	public function render()
	{
		global $post;
		$post_id = $post->ID ?? 0;

		// 1. Get templates
		$templates = function_exists('easy_invoice_get_description_templates')
			? easy_invoice_get_description_templates()
			: [];

		// 2. Get saved values
		$selected = get_post_meta($post_id, '_easy_invoice_selected_template', true);
		$saved_description = get_post_meta($post_id, 'description', true); // FIXED key here
		$post_content_fallback = get_post_field('post_content', $post_id);

		// 3. Decide what goes in the editor
		if (!empty($saved_description)) {
			$default_content = $saved_description;
		} elseif (!empty($selected) && isset($templates[$selected])) {
			$default_content = $templates[$selected]['content'];
		} else {
			$default_content = $post_content_fallback;
		}

		// 4. Render template dropdown
		echo '<p><label for="easy_invoice_selected_template">'
			. esc_html__('Choose a description template:', 'easy-invoice')
			. '</label><br />';
		echo '<select id="easy_invoice_selected_template" name="easy_invoice_selected_template">';
		echo '<option value="">' . esc_html__('-- Select a template --', 'easy-invoice') . '</option>';

		foreach ($templates as $key => $tpl) {
			printf(
				'<option value="%s"%s>%s</option>',
				esc_attr($key),
				selected($selected, $key, false),
				esc_html($tpl['label'])
			);
		}
		echo '</select></p>';

		// 5. Render the WP editor
		wp_editor(
			$default_content,
			'easy_invoice_description',
			[
				'textarea_name' => 'easy_invoice_description',
				'media_buttons' => true,
				'textarea_rows' => 10,
			]
		);

		// 6. Inject template JSON blob
		$template_map = [];
		foreach ($templates as $key => $tpl) {
			$template_map[$key] = $tpl['content'];
		}

		echo '<script id="easy_invoice_template_data" type="application/json">'
			. wp_json_encode($template_map)
			. '</script>';

		// 7. Nonce
		wp_nonce_field($this->nonce_id(), $this->nonce_id() . '_nonce');
	}

	public function nonce_id()
	{
		return 'easy_invoice_description_fields';
	}
}
