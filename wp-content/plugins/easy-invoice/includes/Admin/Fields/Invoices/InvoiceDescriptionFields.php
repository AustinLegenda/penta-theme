<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;

class InvoiceDescriptionFields extends Base
{
    public function get_settings()
    {
        return [];
    }

    public function render()
    {
        global $post;
        $post_id = $post->ID ?? 0;

        $templates = function_exists('easy_invoice_get_description_templates')
            ? easy_invoice_get_description_templates()
            : [];

        $selected = get_post_meta($post_id, '_easy_invoice_selected_template', true);

        echo '<p><label for="easy_invoice_selected_template">'
            . esc_html__('Choose a template:', 'easy-invoice') . '</label> ';
        echo '<select id="easy_invoice_selected_template" name="easy_invoice_selected_template">';
        echo '<option value="">' . esc_html__('-- none --', 'easy-invoice') . '</option>';
        foreach ($templates as $key => $tpl) {
            printf(
                '<option value="%1$s"%2$s data-content="%3$s">%4$s</option>',
                esc_attr($key),
                selected($selected, $key, false),
                esc_attr($tpl['content']),
                esc_html($tpl['label'])
            );
        }
        echo '</select></p>';

        $existing = $post->post_content ?? '';
        $editor_content = (empty(trim($existing)) && $selected && isset($templates[$selected]))
            ? $templates[$selected]['content']
            : $existing;

        wp_editor(
            $editor_content,
            'easy_invoice_description',
            [
                'textarea_name' => 'easy_invoice_description',
                'media_buttons' => true,
                'textarea_rows' => 10,
            ]
        );

        wp_nonce_field($this->nonce_id(), $this->nonce_id() . '_nonce');
    }

    public function nonce_id()
    {
        return 'easy_invoice_description_fields';
    }

    public function save($post_data, $post_id)
    {
        static $already_saved = false;
        if ($already_saved) {
            return;
        }
        $already_saved = true;

        parent::save($post_data, $post_id);

        if (isset($post_data['easy_invoice_selected_template'])) {
            $sel = sanitize_text_field(wp_unslash($post_data['easy_invoice_selected_template']));
            update_post_meta($post_id, '_easy_invoice_selected_template', $sel);
        }

        if (isset($_POST['easy_invoice_description'])) {
            wp_update_post([
                'ID'           => $post_id,
                'post_content' => wp_kses_post(wp_unslash($_POST['easy_invoice_description'])),
            ]);
        }

        $already_saved = false;
    }
}
