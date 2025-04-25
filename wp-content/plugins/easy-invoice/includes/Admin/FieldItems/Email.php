<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class Email
{
	public static function render($field, $field_id, $value, $group_id = null)
	{
		$class = $field['class'] ?? '';

		$after = $field['after'] ?? '';

		$custom_attributes = $field['custom_attributes'] ?? array();

		$custom_attributes_string = '';

		foreach ($custom_attributes as $attribute_key => $attribute) {

			$custom_attributes_string .= esc_attr($attribute_key) . '="' . esc_attr($attribute) . '" ';
		}

		$field_name = !(is_null($group_id)) ? $group_id . '[' . $field_id . ']' : $field_id;

		echo '
					<div class="matrixaddons-fieldset">
					<input type="email" name="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" class="' . esc_attr($class) . '"  ' . $custom_attributes_string . '/>
					' . $after . '
					</div>

				';
	}

	public static function sanitize($field, $raw_value, $field_id)
	{

		return sanitize_email($raw_value);
	}

}
