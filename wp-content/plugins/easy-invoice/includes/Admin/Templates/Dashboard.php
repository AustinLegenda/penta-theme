<h2></h2>
<div class="ei-dashboard-wrap">
	<div class="ei-container">
		<div class="ei-dashboard-elements ei-flex">
			<div class="ei-dashboard-element">
				<div class="ei-dashboard-element-icon"></div>
				<h2><?php echo esc_html__('Total Invoices', 'easy-invoice') ?></h2>
				<p class="count"><?php echo absint($total_invoices) ?></p>
			</div>
			<div class="ei-dashboard-element">
				<div class="ei-dashboard-element-icon"></div>
				<h2><?php echo esc_html__('Total Quotes', 'easy-invoice') ?></h2>
				<p class="count"><?php echo absint($total_quotes) ?></p>
			</div>
			<div class="ei-dashboard-element">
				<div class="ei-dashboard-element-icon"></div>
				<h2><?php echo esc_html__('Paid Invoices', 'easy-invoice') ?></h2>
				<p class="count"><?php echo absint($paid_invoices) ?></p>
			</div>
			<div class="ei-dashboard-element">
				<div class="ei-dashboard-element-icon"></div>
				<h2><?php echo esc_html__('Paid Amount', 'easy-invoice') ?></h2>
				<p class="count"><?php echo esc_html(easy_invoice_get_price($total_paid_amount, '')); ?></p>
			</div>
			<div class="ei-dashboard-element">
				<div class="ei-dashboard-element-icon"></div>
				<h2><?php echo esc_html__('Accepted Quotes', 'easy-invoice') ?></h2>
				<p class="count"><?php echo absint($accepted_quote) ?></p>
			</div>
		</div>

	</div>
</div>

<div class="ei-dashboard-wrap">
	<div class="ei-container">
		<h2><?php echo esc_html(easy_invoice_get_text('quotes')) . ' & ' . esc_html(easy_invoice_get_text('invoices')) ?></h2>
		<div class="ei-dashboard-elements ei-flex">
			<div style="width: 100%">
				<canvas id="ei-canvas" height="300" width="600"></canvas>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">

	var barChartData = {
		labels: [ <?php
			foreach ($quotes as $month => $amount) {
				echo '"' . $month . '",';
			} ?>],
		datasets: [
			{
				label: '<?php echo esc_html(easy_invoice_get_text('quotes')); ?>',
				backgroundColor: "rgba(42, 61, 100, 0.8)",
				borderColor: "rgba(42, 61, 100, 1)",
				data: [<?php
					foreach ($quotes as $month => $amount) {
						echo (int)$amount . ',';
					} ?>]
			},
			{
				label: '<?php echo esc_html(easy_invoice_get_text('invoices')); ?>',
				backgroundColor: "rgba(255, 180, 100, 0.8)",
				borderColor: "rgba(255, 180, 100, 1)",
				data: [<?php
					foreach ($invoices as $month => $amount) {
						echo (int)$amount . ',';
					} ?>]
			}
		]
	};

	jQuery(document).ready(function () {
		var ctx = document.getElementById("ei-canvas").getContext("2d");
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				scales: {
					y: {
						ticks: {
							callback: function (value, index, ticks) {
								return '<?php echo esc_html(html_entity_decode(easy_invoice_get_currency_symbol())) ?>' + value;
							}
						}
					}
				},
				responsive: true,
				legend: {
					display: true
				},
				showScale: true,
				scaleBeginAtZero: true,
				tooltipTitleFontSize: 13,
				tooltipYPadding: 10,
				tooltipXPadding: 15,
				plugins: {
					tooltip: {
						callbacks: {
							label: function (context) {
								let label = context.dataset.label || '';

								if (label) {
									label += ': ';
								}
								if (context.parsed.y !== null) {
									label += '<?php echo esc_html(html_entity_decode(easy_invoice_get_currency_symbol())) ?>' + context.parsed.y;
								}
								return label;
							}
						}
					}
				}

			}
		});
	});

</script>
