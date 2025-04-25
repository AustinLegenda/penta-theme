<hr style="height:3px">
<div class="lei-before-items-3 | lei-section">
<div class="lei-job-title">
		
		<h5 class="pdf-td-h5">JOB TITLE</h5>	
		<h3 class="pdf-td-h3"><?php echo get_the_title($post_id); ?></h3>
</div>
<br>
	<div class="lei-job-description">
			<?php easy_invoice_print_html_text(wpautop($description), array(
				'p' => array(
					'style' => array()
				),
				'a' => array('href' => array(), 'target' => array(), 'rel' => array()),
				'br' => array(),
				'b' => array(),
				'strong' => array(),
				'em' => array(),
				'i' => array(),
				'u' => array(),
				'blockquote' => array(),
				'del' => array(),
				'ins' => array(),
				'img' => array(
					'src' => array(),
					'height' => array(),
					'width' => array()
				),
				'ul' => array(),
				'ol' => array(),
				'li' => array(),
				'code' => array(),
				'span' => array(
					'style' => array()
				),
				'h5' => array()
			)); ?>
		
		</div>
		</div>
<hr style="height:3px">