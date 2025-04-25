<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class Content
{
	public static function render($field, $field_id, $value, $group_id = null)
	{

		$allowed_html = $field['allowed_html'] ?? array('div' => array('class' => array()));

		echo '<div class="easy-invoice-map-render-element-wrap">';
		echo "<div id='" . esc_attr($group_id) . "' class='easy-invoice-invoice-content-wrap'>";
		echo isset($field['content']) ? wp_kses($field['content'], $allowed_html) : '';
		echo '</div>';
		echo '</div>';
	}

	public static function sanitize($field, $raw_value, $field_id)
	{

		return sanitize_text_field($raw_value);
	}
}
