<?php
/**
 * Admin View: Settings
 *
 * @package AgencyEcommerceAddons
 */

if (!defined('ABSPATH')) {
    exit;
}


$tab_exists = isset($tabs[$current_tab]) || has_action('easy_invoice_sections_' . $current_tab) || has_action('easy_invoice_settings_' . $current_tab) || has_action('easy_invoice_settings_tabs_' . $current_tab);
$current_tab_label = isset($tabs[$current_tab]) ? $tabs[$current_tab] : '';

if (!$tab_exists) {
    wp_safe_redirect(admin_url('admin.php?page=easy-invoice-settings'));
    exit;
}
?>
<div class="wrap easy-invoice-admin-setting-page-wrap">
    <h1 class="screen-reader-text"><?php echo esc_html($current_tab_label); ?></h1>
    <form method="<?php echo esc_attr(apply_filters('easy_invoice_settings_form_method_tab_' . $current_tab, 'post')); ?>"
          id="mainform" action="" enctype="multipart/form-data">
        <nav class="nav-tab-wrapper easy-invoice-nav-tab-wrapper">
            <?php

            foreach ($tabs as $slug => $label) {
                echo '<a href="' . esc_html(admin_url('admin.php?page=easy-invoice-settings&tab=' . esc_attr($slug))) . '" class="nav-tab ' . ($current_tab === $slug ? 'nav-tab-active' : '') . '">' . esc_html($label) . '</a>';
            }

            do_action('easy_invoice_settings_tabs');

            ?>
        </nav>

        <?php
        do_action('easy_invoice_sections_' . $current_tab);

        self::show_messages();

        do_action('easy_invoice_settings_' . $current_tab);
        do_action('easy_invoice_settings_tabs_' . $current_tab);
        ?>
        <p class="submit">
            <?php if (empty($GLOBALS['hide_save_button'])) : ?>
                <button name="save" class="button-primary easy-invoice-save-button" type="submit"
                        value="<?php esc_attr_e('Save changes', 'easy-invoice'); ?>"><?php esc_html_e('Save changes', 'easy-invoice'); ?></button>
            <?php endif; ?>
            <?php wp_nonce_field('easy-invoice-settings'); ?>
        </p>
    </form>
</div>
