<?php

namespace MatrixAddons\EasyInvoice\Admin\FieldItems;


class WrapEnd
{
	public static function render($field, $field_id, $value, $group_id = null)
	{

		echo '</div>';

	}

	public static function sanitize($field, $raw_value, $field_id)
	{

		return sanitize_text_field($raw_value);
	}

}
