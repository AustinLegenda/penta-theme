<?php
foreach ($logs as $log) {
	if (is_array($log) && count($log) > 2) {
		echo '<div class="quote-log-history">';
		echo '<p>' . esc_html($log['message']) . '</p>';
		echo '<em> By ' . esc_html($log['username']) . ' ' . date('F j, Y, g:i A', absint($log['time'])) . '</em>';
		echo '</div>';
	}
}
