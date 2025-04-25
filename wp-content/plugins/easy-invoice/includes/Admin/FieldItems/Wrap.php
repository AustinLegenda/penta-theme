<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class Wrap
{
	public static function render($field, $field_id, $value, $group_id = null)
	{
		$class = $field['class'] ?? '';

		$class = "easy-invoice-field-wrap {$class}";

		echo '<div class="' . esc_attr($class) . '">';

	}

	public static function sanitize($field, $raw_value, $field_id)
	{

		return sanitize_text_field($raw_value);
	}

}
