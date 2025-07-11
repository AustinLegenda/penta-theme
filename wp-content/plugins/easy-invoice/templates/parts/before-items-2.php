<hr>
<table class="before-items-two | lei-section">
    <tr class="lei-tr">
        <td class="lei-td | client-data"><?php easy_invoice_get_to_address(); ?></td>
    </tr>
    <tr class="lei-tr">
        <td class="lei-td | invoice-data">
            <?php
            global $ei_invoice, $ei_quote;

            $is_quote = isset($ei_quote);
            $is_invoice = isset($ei_invoice);

            if ($is_quote) {
                // Quote block
                foreach ($details_data as $details) {
                    $label = $details['label'];
                    $value = $details['value'];

                    // Format specific date fields
                    if (in_array($label, [
                        easy_invoice_get_text('quote_date'),
                        easy_invoice_get_text('valid_until_date'),
                    ])) {
                        $timestamp = strtotime($value);
                        if ($timestamp) {
                            $value = date('F j, Y', $timestamp); // e.g., "January 1, 2025"
                        }
                    }

                    if ($label === easy_invoice_get_text('quote_number')) {
                        echo '<div class="quote-number lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('job_number')) {
                        echo '<div class="order-number lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('quote_date')) {
                        echo '<div class="quote-date lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('valid_until_date')) {
                        echo '<div class="valid-until lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    }
                }
            } elseif ($is_invoice) {
                // Invoice block
                foreach ($details_data as $details) {
                    $label = $details['label'];
                    $value = $details['value'];

                    // Format specific date fields
                    if (in_array($label, [
                        easy_invoice_get_text('invoice_date'),
                        easy_invoice_get_text('due_date'),
                    ])) {
                        $value = date('F j, Y', strtotime($value)); // e.g., "January 1, 2025"
                    }

                    if ($label === easy_invoice_get_text('invoice_number')) {
                        echo '<div class="invoice-number lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('job_number')) {
                        echo '<div class="order-number lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('invoice_date')) {
                        echo '<div class="invoice-date lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('due_date')) {
                        echo '<div class="due-date lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    } elseif ($label === easy_invoice_get_text('total_due')) {
                        echo '<div class="total-due lei-detail"><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</div>';
                    }
                }
            }

            ?>
        </td>
    </tr>
</table>