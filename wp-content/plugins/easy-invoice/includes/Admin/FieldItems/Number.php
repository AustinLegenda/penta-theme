<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class Number
{
	public static function render($field, $field_id, $value, $group_id = null)
	{
		$class = $field['class'] ?? '';

		$after = $field['after'] ?? '';

		$field_name = !(is_null($group_id)) ? $group_id . '[' . $field_id . ']' : $field_id;

		echo '
					<div class="matrixaddons-fieldset">
					<input type="number" step="any" name="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" class="' . esc_attr($class) . '" />
					' . $after . '
					</div>

				';
	}

	public static function sanitize($field, $raw_value, $field_id)
	{

		return floatval(abs($raw_value));
	}

}
