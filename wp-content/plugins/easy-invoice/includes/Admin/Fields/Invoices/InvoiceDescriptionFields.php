<?php

namespace MatrixAddons\EasyInvoice\Admin\Fields\Invoices;

use MatrixAddons\EasyInvoice\Admin\Fields\Base;


class InvoiceDescriptionFields extends Base {

    /**
     * No auto-fields here; we render everything by hand.
     */
    public function get_settings() {
        return [];
    }

    /**
     * Render the template dropdown + WP editor.
     */
    public function render() {
        global $post;
        $post_id = $post->ID ?? 0;

        // 1) Pull templates via our helper in invoice-options.php
        $templates = easy_invoice_get_description_templates();

        // 2) Determine which template was saved
        $selected = get_post_meta( $post_id, '_easy_invoice_selected_template', true );

        // 3) Build the <select>
        echo '<p><label for="easy_invoice_selected_template">'
           . esc_html__( 'Choose a template:', 'easy-invoice' )
           . '</label> ';
        echo '<select id="easy_invoice_selected_template" name="easy_invoice_selected_template">';
        echo '<option value="">' . esc_html__( '-- none --', 'easy-invoice' ) . '</option>';
        foreach ( $templates as $key => $tpl ) {
            printf(
                '<option value="%1$s"%2$s data-content="%3$s">%4$s</option>',
                esc_attr( $key ),
                selected( $selected, $key, false ),
                esc_attr( $tpl['content'] ),
                esc_html( $tpl['label'] )
            );
        }
        echo '</select></p>';

        // 4) Determine editor start content: existing or template
        $existing       = $post->post_content ?? '';
        $editor_content = (
            $selected && isset( $templates[ $selected ] )
                ? $templates[ $selected ]['content']
                : $existing
        );

        // 5) Render the WP editor
        wp_editor(
            $editor_content,
            'easy_invoice_description',
            [
                'textarea_name' => 'easy_invoice_description',
                'media_buttons' => true,
                'textarea_rows' => 10,
            ]
        );

        // 6) Output nonce
        wp_nonce_field( $this->nonce_id(), $this->nonce_id() . '_nonce' );
    }

    /**
     * Unique nonce identifier.
     */
    public function nonce_id() {
        return 'easy_invoice_description_fields';
    }

    /**
     * Save meta + post_content without infinite recursion.
     *
     * @param array $post_data Submitted data.
     * @param int   $post_id   The invoice post ID.
     */
    public function save( $post_data, $post_id ) {
        // 1) Save any meta fields via Base::save()
        parent::save( $post_data, $post_id );

        // 2) Persist chosen template key
        if ( isset( $post_data['easy_invoice_selected_template'] ) ) {
            $sel = sanitize_text_field( wp_unslash( $post_data['easy_invoice_selected_template'] ) );
            update_post_meta( $post_id, '_easy_invoice_selected_template', $sel );
        }

        // 3) Save editor content into post_content safely
        if ( isset( $_POST['easy_invoice_description'] ) ) {
            // Temporarily unhook to prevent recursion
            remove_action( 'save_post_invoice', [ $this, 'save' ], 10 );

            wp_update_post( [
                'ID'           => $post_id,
                'post_content' => wp_kses_post( wp_unslash( $_POST['easy_invoice_description'] ) ),
            ] );

            // Re-hook after update
            add_action( 'save_post_invoice', [ $this, 'save' ], 10, 2 );
        }
    }
}
