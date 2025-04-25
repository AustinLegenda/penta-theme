(function ($) {
	var EasyInvoiceCommon = {
		send_email: function (button_el) {
			var _that = this;
			if (button_el.hasClass('disabled')) {
				return;
			}
			button_el.removeClass('error').removeClass('success');
			var form_data = {
				'action': easy_invoice.send_email_action,
				'nonce': easy_invoice.send_email_nonce,
				'invoice_id': easy_invoice.invoice_id
			};
			$.ajax({
				type: "POST",
				url: easy_invoice.ajax_url,
				data: form_data,
				beforeSend: function () {
					button_el.addClass('disabled');
				},
				success: function (response) {
					var status = typeof response.success !== "undefined" ? response.success : false;
					if (status) {
						button_el.removeClass('disabled').addClass('success');
					} else {
						_that.response_template(response);

						button_el.removeClass('disabled').addClass('error');
					}

					setTimeout(() => {
						button_el.removeClass('error').removeClass('success');
					}, 3000)
				},
				complete: function () {
					button_el.removeClass('disabled').addClass('success');

					setTimeout(() => {
						button_el.removeClass('error').removeClass('success');
					}, 3000)
				}
			});
		},
		accept_quote: function (form_el) {
			var _that = this;
			if (form_el.hasClass('disabled')) {
				return;
			}
			$.ajax({
				type: "POST",
				url: easy_invoice.ajax_url,
				data: new FormData(form_el[0]),
				beforeSend: function () {
					_that.loading();
					form_el.addClass('disabled');
				},
				processData: false,
				contentType: false,
				success: function (response) {
					let position = response.search("ei-popup-message");
					if (position > 0) {
						$('.easy-invoice-top-bar').find('.ei-accept-quote-button').addClass('disabled');
					}
					form_el.removeClass('disabled');
					_that.remove_loading();
					_that.response_template(response);
				},
				complete: function () {
					_that.remove_loading();
					form_el.removeClass('disabled');
				}
			});
		},
		decline_quote: function (form_el) {
			var _that = this;
			if (form_el.hasClass('disabled')) {
				return;
			}
			$.ajax({
				type: "POST",
				url: easy_invoice.ajax_url,
				data: new FormData(form_el[0]),
				beforeSend: function () {
					_that.loading();
					form_el.addClass('disabled');
				},
				processData: false,
				contentType: false,
				success: function (response) {
					let position = response.search("ei-popup-message");
					if (position > 0) {
						$('.easy-invoice-top-bar').find('.ei-decline-quote-button').addClass('disabled');
					}
					form_el.removeClass('disabled');
					_that.remove_loading();
					_that.response_template(response);
				},
				complete: function () {
					_that.remove_loading();
					form_el.removeClass('disabled');
				}
			});
		},

		proceed_to_payment: function (button_el) {
			let _that = this;
			if (button_el.hasClass('disabled')) {
				return;
			}
			this.loading();

			var form_data = {
				'action': easy_invoice.proceed_to_payment_action,
				'nonce': easy_invoice.proceed_to_payment_nonce,
				'invoice_id': easy_invoice.invoice_id
			};
			$.ajax({
				type: "POST",
				url: easy_invoice.ajax_url,
				data: form_data,
				beforeSend: function () {
					button_el.addClass('disabled');
				},
				success: function (response) {

					_that.response_template(response);
					button_el.removeClass('disabled');
				},
				complete: function () {
					_that.remove_loading();
					button_el.removeClass('disabled');
				}
			});
		},
		loading: function () {
			$('body').find('.ei-loading').remove();
			$('body').append('<div class="ei-loading"></div>');
		},
		remove_loading: function () {
			$('body').find('.ei-loading').remove();
		},
		response_template: function (response) {
			$('body').find('.ei-popup-page-main-container.active').remove();
			$('body').append('<div class="ei-popup-page-main-container active">' + response + '</div>');
		},
		remove_response_template: function () {
			var active_popup = $('body').find('.ei-popup-page-main-container.active');

			$.each(active_popup, function () {
				let close = $(this).find('.ei-close');
				let action = close.attr('data-action');
				if (action === "hide") {
					$(this).removeClass('active');
				} else {
					$(this).remove();
				}
			})
		},
		payment_form_submit: function (form) {
			var form_data = new FormData(form)
			$.ajax({
				type: "POST",
				url: form.attr('data-action'),
				data: form_data,
				beforeSend: function () {
					form.closest('.ei-popup-page-content').addClass('.ei-before-loading');
				},
				success: function (response) {

					_that.response_template(response);
					form.closest('.ei-popup-page-content').removeClass('.ei-before-loading');
				},
				complete: function () {
					form.closest('.ei-popup-page-content').removeClass('.ei-before-loading');
				}
			});
		}
	};

	$(document).ready(function () {
		window.EasyInvoiceCommon = EasyInvoiceCommon;

	});
}(jQuery));
