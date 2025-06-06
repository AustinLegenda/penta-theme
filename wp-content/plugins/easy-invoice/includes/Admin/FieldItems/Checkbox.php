<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class Checkbox
{
	public static function render($field, $field_id, $value, $group_id = null)
	{
		$class = $field['class'] ?? '';

		$after = $field['after'] ?? '';

		$field_name = !(is_null($group_id)) ? $group_id . '[' . $field_id . ']' : $field_id;

		?>
		<div class="matrixaddons-fieldset">
			<input <?php checked($value, 1); ?> type="checkbox" name="<?php echo esc_attr($field_name); ?>" value="1"
												class="<?php echo esc_attr($class); ?>"/>
			<?php echo $after; ?>
		</div>

		<?php
	}

	public static function sanitize($field, $raw_value, $field_id)
	{

		return absint($raw_value) === 1 ? 1 : 0;
	}

}
