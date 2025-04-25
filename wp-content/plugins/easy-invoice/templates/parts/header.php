<div class="ei-flex ei-component-wrap <?php echo esc_attr(apply_filters('easy_invoice_header_class_for_invoice', 'ei-invoice-header')); ?>">
	<div class="ei-flex-1 ei-business">
		<a target="_blank" href="<?php echo esc_url(site_url()) ?>">
			<?php if ($logo_src === '') { ?>
				<h1 class="logo-type"><?php echo esc_html(get_bloginfo('name')) ?></h1>
			<?php } else { ?>
				<img src="<?php echo esc_url($logo_src) ?>" class="ei-business-logo"/>
			<?php } ?>
		</a>
	</div>
</div>