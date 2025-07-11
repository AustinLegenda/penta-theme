<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class LineItemFields extends Base
{
	public function get_settings()
	{
		$adjust_enable = easy_invoice_show_hide_adjust();
		$adjust_type = $adjust_enable ? 'text' : 'hidden';
		$pre_defined_line_items = easy_invoice_get_predefined_line_items();
		$pre_defined_section_titles = easy_invoice_get_predefined_section_titles();

		return [
			'easy_invoice_line_items' => [
				'type' => 'group',
				'button_title' => __('Add Header or Line Item', 'easy-invoice'),
				'repeatable' => true,
				'fields' => [
					'entry_type' => [
						'type' => 'select',
						'title' => __('Entry Type', 'easy-invoice'),
						'options' => [
							'header' => __('Section Header', 'easy-invoice'),
							'line_item' => __('Line Item', 'easy-invoice'),
						],
						'class' => 'easy-invoice-entry-type',
						'default' => 'line_item'
					],

					// Section Header Field (Only used when "Header" is selected)
					'section_header_wrap' => [
						'type' => 'wrap',
						'class' => 'section-header-group',
					],
					'pre_defined_section_headers' => [
						'title' => __('Predefined Section Title', 'easy-invoice'),
						'type' => 'select',
						'options' => $pre_defined_section_titles,
						'class' => 'easy-invoice-predefined-section-titles',
						'name' => '',
					],
					'section_title' => [
						'type' => 'text',
						'title' => __('Section Title', 'easy-invoice'),
						'class' => 'easy-invoice-section-title',
						'default' => '',
					],
					'section_header_wrap_end' => [
						'type' => 'wrap_end',
					],

					// Line Item Fields (Only used when "Line Item" is selected)
					'line_item_pre_start' => [
						'type' => 'wrap',
						'class' => 'easy-invoice-line-item-pre-wrap',
					],
					'pre_defined_line_items' => [
						'title' => __('Predefined Line Items', 'easy-invoice'),
						'type' => 'select',
						'options' => $pre_defined_line_items,
						'class' => 'easy-invoice-predefined-line-items',
						'name' => '',
					],
					'line_item_pre_end' => [
						'type' => 'wrap_end',
						'class' => 'easy-invoice-line-item-pre-wrap-end',
					],
					'line_item_1_start' => [
						'type' => 'wrap',
						'class' => 'easy-invoice-line-item-1-wrap',
					],
					'quantity' => [
						'type' => 'text',
						'title' => __('Quantity', 'easy-invoice'),
						'class' => 'easy-invoice-line-item-quantity',
						'default' => '',
					],
					'item_title' => [
						'type' => 'text',
						'title' => __('Item Title', 'easy-invoice'),
						'class' => 'easy-invoice-line-item-title',
						'default' => '',
					],
					'qty_type' => [
						'type' => 'select',
						'options' => [
							'' => __('Null', 'easy-invoice'),
							'hour' => __('Hour(s)', 'easy-invoice'),
							'day' => __('Day(s)', 'easy-invoice'),
						],
						'title' => __('Qty Type', 'easy-invoice'),
						'class' => 'easy-invoice-entry-type',
						'default' => 'blank',
					],
					'adjust' => [
						'type' => $adjust_type,
						'title' => __('Adjust (%)', 'easy-invoice'),
						'class' => 'easy-invoice-line-item-adjust',
						'default' => '',
					],
					'rate' => [
						'type' => 'text',
						'title' => __('Rate', 'easy-invoice'),
						'class' => 'easy-invoice-line-item-rate',
						'default' => '',
					],
					'amount' => [
						'title' => __('Amount', 'easy-invoice'),
						'type' => 'content',
						'content' => '<div class="amount-content"></div>',
						'class' => 'easy-invoice-line-item-amount',
						'allowed_html' => array('div' => array('class' => array()))
					],
					'line_item_1_end' => [
						'type' => 'wrap_end',
						'class' => 'easy-invoice-line-item-1-wrap-end',
					],

					'line_item_2_start' => [
						'type' => 'wrap',
						'class' => 'easy-invoice-line-item-2-wrap',
					],
					'description' => [
						'title' => __('Description', 'easy-invoice'),
						'type' => 'textarea',
						'class' => 'easy-invoice-line-item-description',
					],
					'taxable' => [
						'type' => 'checkbox',
						'title' => __('Taxable', 'easy-invoice'),
						'class' => 'easy-invoice-line-item-taxable',
						'desc' => __("Make this line item taxable.", 'easy-invoice')

					],
					'line_item_2_end' => [
						'type' => 'wrap_end',
						'class' => 'easy-invoice-line-item-3-wrap-end',
					],
					'pre_defined_line_items' => [
						'title' => __('Predefine Line Items', 'easy-invoice'),
						'type' => 'select',
						'options' => $pre_defined_line_items,
						'class' => 'easy-invoice-predefined-line-items',
						'name' => '',
					],

					'line_item_2_start' => [
						'type' => 'wrap',
						'class' => 'easy-invoice-line-item-2-wrap',
					],
					'description' => [
						'title' => __('Description', 'easy-invoice'),
						'type' => 'textarea',
						'class' => 'easy-invoice-line-item-description',
					],

					'line_item_2_end' => [
						'type' => 'wrap_end',
						'class' => 'easy-invoice-line-item-3-wrap-end',
					],

					'line_item_3_start' => [
						'type' => 'wrap',
						'class' => 'easy-invoice-line-item-2-wrap',
					],

					'amount' => [
						'title' => __('Amount', 'easy-invoice'),
						'type' => 'content',
						'content' => '<div class="amount-content"></div>',
						'class' => 'easy-invoice-line-item-amount',
						'allowed_html' => array('div' => array('class' => array()))
					],

					'taxable' => [
						'type' => 'checkbox',
						'title' => __('Taxable', 'easy-invoice'),
						'class' => 'easy-invoice-line-item-taxable',
						'desc' => __("Make this line item taxable.", 'easy-invoice')

					],
					'line_item_3_end' => [
						'type' => 'wrap_end',
						'class' => 'easy-invoice-line-item-3-wrap-end',
					],

				],
			],
		];
	}

	public function render()
	{
		$this->output();
	}

	public function nonce_id()
	{
		return 'easy_invoice_line_item_fields';
	}
}
