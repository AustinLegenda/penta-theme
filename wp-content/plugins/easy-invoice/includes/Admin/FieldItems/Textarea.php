<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;

class Textarea
{
	public static function render($field, $field_id, $value, $group_id = null)
	{
		$field_name = !(is_null($group_id)) ? $group_id . '[' . $field_id . ']' : $field_id;

		$class = $field['class'] ?? '';

		echo '
					<div class="matrixaddons-fieldset">
					<textarea name="' . esc_attr($field_name) . '" class="' . esc_attr($class) . '">' . esc_html($value) . '</textarea>
					</div>

				';
	}

	public static function sanitize($field, $raw_value, $field_id)
	{
		$allowed_html = $field['allowed_html'] ?? array(
			'p' => array(
				'style' => array()
			),
			'a' => array('href' => array(), 'target' => array(), 'rel' => array()),
			'br' => array(),
			'&nbsp;' => array(),
			'b' => array(),
			'strong' => array(),
			'em' => array(),
			'i' => array(),
			'u' => array(),
			'blockquote' => array(),
			'del' => array(),
			'ins' => array(),
			'img' => array(
				'src' => array(),
				'height' => array(),
				'width' => array()
			),
			'ul' => array(),
			'ol' => array(),
			'li' => array(),
			'code' => array(),
			'span' => array(
				'style' => array()
			),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
		);

		return wp_kses($raw_value, $allowed_html);
	}
}
