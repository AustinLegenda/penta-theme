<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class Select
{
	public static function render($field, $field_id, $value, $group_id = null)
	{
		$class = $field['class'] ?? '';

		$after = $field['after'] ?? '';

		$field_name = !(is_null($group_id)) ? $group_id . '[' . $field_id . ']' : $field_id;

		$field_name = $field['name'] ?? $field_name;

		$options = $field['options'] ?? array();

		?>
		<div class="matrixaddons-fieldset">
			<select id="<?php echo esc_attr($field_name) ?>" class="<?php echo esc_attr($class) ?>"
					name="<?php echo esc_attr($field_name) ?>">
				<?php foreach ($options as $option_id => $option) {
					$option_text = $option;
					$extra_attributes = array();
					if (is_array($option)) {
						$option_text = $option['text'] ?? '';
						$extra_attributes = $option;
						if (isset($extra_attributes['text'])) {
							unset($extra_attributes['text']);
						}
					}
					$attribute_string = '';
					foreach ($extra_attributes as $attr_key => $attr_value) {
						$attribute_string .= esc_attr($attr_key) . '="' . esc_attr($attr_value) . '" ';
					}
					?>
					<option value="<?php echo esc_attr($option_id) ?>" <?php echo trim($attribute_string) ?>
							<?php selected($value, $option_id) ?>><?php echo esc_html($option_text) ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<?php
	}

	public static function sanitize($field, $raw_value, $field_id)
	{
		$options = $field['options'] ?? array();

		if (isset($options[$raw_value])) {

			return sanitize_text_field($raw_value);
		}
		return null;
	}

}
