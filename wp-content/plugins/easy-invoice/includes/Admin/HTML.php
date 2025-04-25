<?php

namespace MatrixAddons\EasyInvoice\Admin;

class HTML
{
	public static function render_item($field, $field_id, $value, $group_id = null)
	{
		$type = $field['type'] ?? '';

		$class_name = "\MatrixAddons\EasyInvoice\Admin\FieldItems\\" . self::get_type_class($type);

		$css_class = 'matrixaddons-field matrixaddons-field-' . esc_attr($type);

		$title = $field['title'] ?? '';

		$desc = $field['desc'] ?? '';

		if (strtolower($type) === "wrap") {

			self::render_field_class($class_name, $field, $field_id, $value, $group_id);
		}


		if (!in_array(strtolower($type), array("wrap", "wrap_end"))) {


			echo '<div class="' . esc_attr($css_class) . '" id="' . esc_attr($field_id) . '">';

			echo '<div class="matrixaddons-title">';

			if ($title != '') {
				echo '<h4>' . esc_html($title) . '</h4>';
			}
			if ($desc != '') {
				echo '<small>' . esc_html($desc) . '</small>';
			}
			echo '</div>';

			self::render_field_class($class_name, $field, $field_id, $value, $group_id);

			echo '<div class="clear"></div>';

			echo '</div>';
		}


		if (strtolower($type) === "wrap_end") {

			self::render_field_class($class_name, $field, $field_id, $value, $group_id);
		}
	}

	public static function render($fields, $group_id = null)
	{
		foreach ($fields as $field_id => $field) {

			$default = $field['default'] ?? null;

			if (!is_null($group_id)) {

				self::render_item($field, $field_id, $default, $group_id);

			} else {

				$object_id = get_the_ID();

				$value = get_post_meta($object_id, $field_id, true);

				if (!metadata_exists('post', $object_id, $field_id)) {

					$value = is_null($value) || $value == '' ? $default : $value;
				}

				self::render_item($field, $field_id, $value, $group_id);
			}


		}


	}

	public static function render_field_class($class_name, $field, $field_id, $value, $group_id)
	{
		if (class_exists($class_name)) {

			$class_name::render($field, $field_id, $value, $group_id);
		}
	}

	public static function sanitize($settings, $post_data)
	{
		$valid_data = array();

		foreach ($settings as $field_id => $field) {

			$raw_data = $post_data[$field_id] ?? null;

			$valid_data[$field_id] = self::sanitize_item($field, $raw_data, $field_id);

		}
		return $valid_data;
	}

	public static function sanitize_item($field, $raw_data, $field_id)
	{
		$type = $field['type'] ?? '';

		$class_name = "\MatrixAddons\EasyInvoice\Admin\FieldItems\\" . self::get_type_class($type);

		$sanitize_callback = $field['sanitize_callback'] ?? '';

		if ($sanitize_callback != '' && is_callable($sanitize_callback)) {

			return $sanitize_callback($field, $raw_data, $field_id);

		} else if (class_exists($class_name)) {

			return $class_name::sanitize($field, $raw_data, $field_id);
		}


		return null;
	}

	public static function get_type_class($field_type)
	{

		$field_type = str_replace('_', ' ', $field_type);

		$field_type = ucwords($field_type);

		return str_replace(' ', '', $field_type);
	}

}
