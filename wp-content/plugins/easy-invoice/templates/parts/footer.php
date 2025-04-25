<div class="ei-flex ei-component-wrap <?php echo esc_attr(apply_filters('easy_invoice_footer_class_for_invoice', 'ei-invoice-footer')); ?>">

	<div class="ei-flex-1 footer-text"><p><?php easy_invoice_print_html_text($footer_text); ?></p></div>

	<div class="ei-flex-1 page-number ei-no-print">
		<strong><?php echo esc_html($page); ?></strong> {PAGENO}/{nbpg}
	</div>

</div>
