(function ($) {
	var common;
	var EasyInvoiceFrontend = {
		init: function () {

			common = window.EasyInvoiceCommon;
			this.initEventListners();
		},
		initEventListners: function () {
			var _that = this;
			this.download_as_pdf_button = $('.ei-download-pdf-button');
			this.send_email_button = $('.ei-send-email-button');
			this.proceed_to_payment_button = $('.ei-proceed-to-payment-button');
			this.accept_quote_button = $('.ei-accept-quote-button');
			this.accept_quote_form = $('.easy-invoice-accept-quote-form');
			this.decline_quote_form = $('.easy-invoice-decline-quote-form');
			this.decline_quote_button = $('.ei-decline-quote-button');
			this.download_as_pdf_button.on('click', function () {
				var url = $(this).attr('data-url');
				window.open(url, '_self');

			});
			this.send_email_button.on('click', function () {
				common.send_email($(this));
			})
			this.accept_quote_button.on('click', function () {
				if ($(this).hasClass('disabled')) {
					return;
				}
				$('.ei-popup-page-main-container.accept-quote').addClass('active');
			});
			this.decline_quote_button.on('click', function () {
				if ($(this).hasClass('disabled')) {
					return;
				}
				$('.ei-popup-page-main-container.decline-quote').addClass('active');
			});
			this.accept_quote_form.on('submit', function (e) {
				e.preventDefault();
				common.accept_quote($(this));
			});
			this.decline_quote_form.on('submit', function (e) {
				e.preventDefault();
				common.decline_quote($(this));
			});

			$('body').on('click', '.ei-proceed-to-payment-button', function (e) {
				e.stopImmediatePropagation();
				common.proceed_to_payment($(this));
			});

			/*$('body').on('submit', '.easy-invoice-checkout-form', function (e) {
				e.preventDefault();
				common.payment_form_submit($(this));
			});*/

			$(document).keyup(function (e) {
				console.log(e.keyCode);
				if (e.keyCode === 27) { // escape key maps to keycode `27`
					common.remove_response_template();
				}
			});

			$('body').on('click', '.ei-close', function () {
				common.remove_response_template();
			});
			$('body').on('click', '.ei-invoice-error', function () {
				$(this).html("");
				$(this).removeClass('ei-invoice-error');
			});
			$('body').on('change', 'input[name="easy_invoice_payment_gateway"]', function (e) {

				var payment_mode = $('ul.easy-invoice-payment-gateway input[name="easy_invoice_payment_gateway"]:checked').val();

				_that.load_gateway(payment_mode);

			});
		},
		load_gateway: function (payment_mode) {

			$('ul.easy-invoice-payment-gateway').find('.easy-invoice-payment-gateway-field-wrap').addClass('easy-invoice-hide');

			$('ul.easy-invoice-payment-gateway').trigger('easy_invoice_gateway_loaded', [payment_mode]);
		}
	};

	$(document).ready(function () {
		EasyInvoiceFrontend.init();

	});
}(jQuery));
