<?php global $ei_invoice;
?>
	
<?php if ($ei_invoice->get_client_name() !== '' && $ei_invoice->get_client_url() !== '') { ?>
	<div class="site-url"><a target="_blank"
							 href="<?php echo esc_url($ei_invoice->get_client_url()) ?>"><?php echo esc_html($ei_invoice->get_client_name()); ?></a>
	</div>
<?php } else if ($ei_invoice->get_client_name() !== '') { ?>
	<div class="site-url"><p><?php echo esc_html($ei_invoice->get_client_name()); ?></p></div>
<?php } ?>
<?php if ($ei_invoice->get_client_additional_info() !== '') { ?>
	<div class="address">
		<p>
			<?php easy_invoice_print_html_text(wpautop($ei_invoice->get_client_additional_info())); ?>
		</p>
	</div>
<?php } ?>
	<div class="extra">
		<p><?php echo esc_html($ei_invoice->get_client_email()) ?></p>
	</div>

<?php
