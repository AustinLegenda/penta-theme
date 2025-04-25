<?php

namespace MatrixAddons\EasyInvoice;

use MatrixAddons\EasyInvoice\Hooks\InvoiceTemplate;

class PDF
{
	public function generate_pdf($content, $html_header, $html_footer)
	{
		global $ei_invoice, $ei_quote;
	
		// Determine the document type and get the number
		$document_number = 'document';
		if (isset($ei_invoice)) {
			$document_number = $ei_invoice->get_invoice_number();
		} elseif (isset($ei_quote)) {
			$document_number = $ei_quote->get_quote_number();
		}
	
		$file_name = sanitize_file_name('legenda-' . $document_number . '.pdf'); // Ensures valid file name
	
		$tmp_dir = easy_invoice()->get_tmp_pdf_dir(true, true);
	
		$mpdf_config = apply_filters('easy_invoice_mpdf_config_for_invoice', [
			'tempDir' => $tmp_dir,
			'format' => 'A4',
			'orientation' => 'P',
			'margin_header' => 0,
			'margin_footer' => 0,
			'margin_left' => 0,
			'margin_right' => 0,
		]);
	
		$mpdf = apply_filters('easy_invoice_pdf_mpdf_instance', new \Mpdf\Mpdf($mpdf_config));
	
		$mpdf->showImageErrors = true;
	
		$stylesheet = apply_filters('easy_invoice_pdf_stylesheet_for_invoice', file_get_contents(EASY_INVOICE_PLUGIN_DIR . 'assets/css/easy-invoice-mpdf.css'));
	
		$stylesheet = str_replace('/*# sourceMappingURL=easy-invoice-mpdf.css.map */', '', $stylesheet);
	
		$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
	
		// Apply header only on the first page
		$final_html = '<page><div style="text-align:center;">' . $html_header . '</div></page>';
		$mpdf->WriteHTML($final_html);
		$mpdf->WriteHTML($content);
	
		$mpdf->SetHTMLFooter($html_footer, 'ALL', true);
	
		$download_or_preview = apply_filters('easy_invoice_pdf_download_or_preview', 'download');
	
		$dest = ($download_or_preview === 'preview') ? 'I' : 'D';
		$mpdf->Output($file_name, $dest);
	}
}	
