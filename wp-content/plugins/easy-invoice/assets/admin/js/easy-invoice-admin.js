// @var easyInvoiceAdminParams
(function ($) {
		//lei drag handle
		$(".easy-invoice-line-item").each(function () {
			if ($(this).find(".lei-drag-handle").length === 0) {
				$(this).prepend('<div class="lei-drag-handle">☰</div>');
			}
		});
	
		// Enable sortable functionality
		if ($.fn.sortable) {
			$(".matrixaddons-repeater-wrapper").sortable({
				handle: ".lei-drag-handle",
				update: function (event, ui) {
					let sortedIDs = $(this).sortable("toArray", { attribute: "data-id" });
					console.log("New Order: ", sortedIDs); // Send this data via AJAX if needed
				}
			});
		} else {
			console.error("jQuery UI Sortable is not loaded.");
		}
	
	
//hide or show line-item options based on selection
	$(document).ready(function () {
		function toggleFields(parent, type) {
			 if (type === 'header') {
            parent.find('.section-header-group, .easy-invoice-section-title').show();
            parent.find('.easy-invoice-line-item-1-wrap, .easy-invoice-line-item-2-wrap, .easy-invoice-predefined-line-items, .easy-invoice-line-item-title').hide();
        } else {
            parent.find('.section-header-group, .easy-invoice-section-title').hide();
            parent.find('.easy-invoice-line-item-1-wrap, .easy-invoice-line-item-2-wrap, .easy-invoice-predefined-line-items, .easy-invoice-line-item-title').show();
        }
			
		}

		// Handle change event for Entry Type selection
		$('body').on('change', '.easy-invoice-entry-type', function () {
			var parent = $(this).closest('.matrixaddons-repeater-item');
			var type = $(this).val();
			toggleFields(parent, type);
		});

		// Automatically scroll to newly added items for better UX
		$('body').on('click', '.matrixaddons-repeater-add', function () {
			setTimeout(() => {
				$('.matrixaddons-repeater-item').last().get(0).scrollIntoView({ behavior: 'smooth' });
			}, 300);
		});

		// Initialize UI state on page load
		$('.easy-invoice-entry-type').each(function () {
			var parent = $(this).closest('.matrixaddons-repeater-item');
			toggleFields(parent, $(this).val());
		});
	});

	var EasyInvoiceAdmin = {
		setupElementBasic: function () {
			this.currency_symbol = $('input[name="currency_symbol"]');
			this.currency = $('input[name="currency"]');
			this.tax_rate = $('input[name="tax_rate"]');
			this.discount = $('input[name="discount"]');
			this.discount_type = $('select[name="discount_type"]');
			this.discount_calculation_method = $('select[name="discount_calculation_method"]');
			this.tax_type = $('select[name="tax_type"]');
			this.item_title = $('.easy-invoice-line-item-title');
			this.section_title = $('.easy-invoice-section-title');


		},
		getPrice(price) {
			var currency = this.settings.currency_symbol_type === "code" ? this.currency.val() : this.currency_symbol.val()
			price = this.numberFormat(price);
			var currency_position = this.settings.currency_position;
			if (currency_position === "left_space") {
				return currency + ' ' + price;

			} else if (currency_position === "right_space") {
				return price + ' ' + currency;

			} else if (currency_position === "right") {
				return price + currency;

			} else {
				return currency + price;

			}
		},
		numberFormat: function (number) {
			var decimals = this.settings.number_decimals;
			var dec_point = this.settings.decimal_separator;
			var thousands_sep = this.settings.thousand_separator;
			// Strip all characters but numerical ones.
			number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
			var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				s = '',
				toFixedFix = function (n, prec) {
					var k = Math.pow(10, prec);
					return '' + Math.round(n * k) / k;
				};
			// Fix for IE parseFloat(0.55).toFixed(0) = 0;
			s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
			if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			}
			if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
			}
			return s.join(dec);
		},
		parseFloat: function (value) {
			if (isNaN(value) || value === '') {
				return 0;
			}
			return parseFloat(value);
		},
		parseInt: function (value) {
			if (isNaN(value) || value === '') {
				return 0;
			}
			return parseInt(value);
		},
		setupValueBasic: function () {
			var _that = this;
			this.basic_values = {
				discount: _that.parseFloat(this.discount.val()),
				discount_type: this.discount_type.find(':selected').val(),
				discount_calculation_method: this.discount_calculation_method.find(':selected').val(),
				currency_symbol: this.settings.currency_symbol_type === "code" ? this.currency.val() : this.currency_symbol.val(),
				tax_rate: _that.parseFloat(this.tax_rate.val()),
				tax_type: this.tax_type.find(':selected').val(),
				line_items: _that.getLineItems(),
			};
			var amount = this.get_amount();
			var tax_amount = this.get_tax_amount();
			var discount_amount = this.get_discount_amount();
			var paid = this.parseFloat($('.easy-invoice-paid.total-item').attr('data-amount'));
			var total_due = this.parseFloat(amount.subtotal + tax_amount - discount_amount - paid);

			$('.easy-invoice-sub-total.total-item').find('.value').text(this.getPrice(amount.subtotal));
			$('.easy-invoice-tax.total-item').find('.value').text(this.getPrice(tax_amount));
			$('.easy-invoice-discount.total-item').find('.value').text('- ' + this.getPrice(discount_amount));
			$('.easy-invoice-paid.total-item').find('.value').text(this.getPrice(paid));
			$('.easy-invoice-total.total-item').find('.value').text(this.getPrice(total_due));

			console.log(this.basic_values);
		},
		get_amount: function () {
			var total_taxable_amount = 0;
			var total_subtotal = 0;
			this.basic_values.line_items.map(function (item) {
				total_taxable_amount = (item.taxable ? (total_taxable_amount + item.amount) : total_taxable_amount);
				total_subtotal += item.amount;
			});
			return { taxable: total_taxable_amount, subtotal: total_subtotal };
		},
		get_tax_amount: function () {

			var amount = this.get_amount();

			var calculation_value = amount.taxable;

			var tax_percentage = this.parseFloat(this.basic_values.tax_rate);

			var tax_type = this.basic_values.tax_type;

			if (tax_percentage > 0 && calculation_value > 0) {

				var calculation_method = this.basic_values.discount_calculation_method;

				if (calculation_method === "before_tax") {

					var discount_amount = this.get_discount_amount();

					calculation_value = calculation_value - discount_amount;

					if (calculation_value < 0 || calculation_value === 0) {

						return 0;
					}

				}

				if ('inclusive' === tax_type) {

					return ((calculation_value * tax_percentage) / (100 + tax_percentage));

				}
				return (calculation_value * tax_percentage) / 100;

			}
			return 0;

		},
		get_discount_amount: function () {

			var discount_value = this.basic_values.discount;

			var discount_type = this.basic_values.discount_type;

			var amount = this.get_amount();

			var calculation_value = amount.subtotal;

			if (discount_value > 0 && calculation_value > 0) {

				var calculation_method = this.basic_values.discount_calculation_method;

				if (calculation_method === "after_tax") {

					calculation_value = calculation_value + (this.get_tax_amount());
				}
				var discount_amount = 0;

				if (discount_type === 'fixed') {

					discount_amount = calculation_value >= discount_value ? discount_value : calculation_value;

				} else {
					discount_amount = (calculation_value * discount_value) / 100;
				}

				return discount_amount;

			}

			return 0;
		},
		getLineItems: function () {
			var _that = this;
			let items = [];
			$('.matrixaddons-repeater-item:not(.matrixaddon-repeater-template)').each(function () {
				let item = {
					quantity: 0,
					rate: 0,
					taxable: false,
					adjust: 0,
					amount: 0
				};
				item.quantity = _that.parseInt($(this).find('.easy-invoice-line-item-quantity').val());
				item.rate = _that.parseFloat($(this).find('.easy-invoice-line-item-rate').val());
				item.taxable = $(this).find('.easy-invoice-line-item-taxable').is(':checked');
				item.adjust = _that.parseFloat($(this).find('.easy-invoice-line-item-adjust').val());
				let amount = item.quantity * item.rate;
				item.amount = amount > 0 && item.adjust > 0 ? (amount + (amount * item.adjust) / 100) : amount;
				$(this).find('.amount-content').text(_that.getPrice(item.amount));
				items.push(item);

			});
			return items;
		},
		init: function () {
			this.settings = (easyInvoiceAdminParams);
			this.bindEvents();
			this.image_upload_frame = '';
			this.initMediaUploader();
			this.setupElementBasic();
			this.calculateEverything();
			this.openFirstLineItem();
			this.fillPreDefinedLineItems();
		},
		fillPreDefinedLineItems: function () {
			$('body').on('change', '.easy-invoice-predefined-line-items', function () {
				var selected = $(this).find(':selected');
				var wrap = $(this).closest('.matrixaddons-repeater-item');
				wrap.find('.easy-invoice-line-item-quantity').val(selected.attr('data-qty'));
				wrap.find('.easy-invoice-line-item-rate').val(selected.attr('data-price'));
				wrap.find('.easy-invoice-line-item-description').val(selected.attr('data-desc'));
				wrap.find('.easy-invoice-line-item-title').val(selected.text());
				wrap.find('.easy-invoice-line-item-rate').trigger('keyup');
			});
		},
		openFirstLineItem: function () {
			$('.matrixaddons-repeater-wrapper').find('.matrixaddons-repeater-item').last().find('.matrixaddons-repeater-title').trigger('click');
		},
		calculateEverything: function () {
			var _that = this;
			this.discount.on('keyup', function () {
				_that.setupValueBasic();
			});
			this.discount_type.on('change', function () {
				_that.setupValueBasic();
			});
			this.discount_calculation_method.on('change', function () {
				_that.setupValueBasic();
			});
			this.currency_symbol.on('keyup', function () {
				_that.setupValueBasic();
				_that.setupValueBasic();
			});

			this.currency.on('change', function () {
				_that.setupValueBasic();
			});
			this.tax_rate.on('keyup', function () {
				_that.setupValueBasic();
			});
			this.tax_type.on('change', function () {
				_that.setupValueBasic();
			});

			$('body').on('click', '.easy-invoice-line-item-taxable', function () {
				_that.setupValueBasic();
			});
			$('body').on('keyup', '.easy-invoice-line-item-adjust', function () {
				_that.setupValueBasic();
			});
			$('body').on('keyup', '.easy-invoice-line-item-rate', function () {
				_that.setupValueBasic();
			});
			$('body').on('keyup', '.easy-invoice-line-item-quantity', function () {
				_that.setupValueBasic();
			});
			$('body').on('easy_invoice_repeater_modify', '.matrixaddons-field-group', function (event, id) {
				if (id !== "easy_invoice_line_items") {
					return;
				}
				_that.setupValueBasic();
			});
		},
		bindEvents: function () {
			var _that = this;
			$('body').on('click', '.matrixaddons-tab-nav-item', function (e) {
				e.preventDefault();
				var id = $(this).attr('id');
				$(this).closest('.matrixaddons-tabs').find('.matrixaddons-tab-nav .matrixaddons-tab-nav-item').removeClass('item-active');
				$(this).addClass('item-active');
				$(this).closest('.matrixaddons-tabs').find('.matrixaddons-tab-section').addClass('matrixaddons-hide');
				$(this).closest('.matrixaddons-tabs').find('.matrixaddons-tab-section.' + id + '_content').removeClass('matrixaddons-hide');
				var tab = $(this).attr('data-tab');
				$('[name="easy_invoice_meta_active_tab"]').val(tab);

			});

			$(".matrixaddons-repeater-title").each(function () {
				let titleBar = $(this).find(".matrixaddons-repeater-header-icon");

				// Ensure the drag handle is only added once
				if ($(this).find(".lei-drag-handle").length === 0) {
					titleBar.before('<span class="lei-drag-handle dashicons dashicons-ellipsis"></span>');
				}
			});

			// Enable drag-and-drop sorting
			$(".matrixaddons-repeater-wrapper").sortable({
				handle: ".lei-drag-handle",
				update: function (event, ui) {
					let sortedIDs = $(this).sortable("toArray", { attribute: "data-item-id" });
					console.log("New Order: ", sortedIDs); // Send this data via AJAX if needed
				}
			});

			$('body').on('keyup', '.easy-invoice-line-item-title', function (e) {
				e.preventDefault();
				var item_title = $(this).val();
				$(this).closest('.matrixaddons-repeater-item').find('.matrixaddons-repeater-text').text(item_title);

			});
			$('body').on('keyup', '.easy-invoice-section-title', function (e) {
				e.preventDefault();
				var section_title = $(this).val();
				$(this).closest('.matrixaddons-repeater-item').find('.matrixaddons-repeater-text').text(section_title);

			});
			$('body').on('click', '.matrixaddons-repeater-add', function (e) {
				e.preventDefault();
				var parent = $(this).closest('.matrixaddons-field-group');
				var id = parent.attr('id');
				var totalLength = parent.find('.matrixaddons-repeater-wrapper').find('.matrixaddons-repeater-item').length
				var item_id = ((totalLength + 1) - 1);
				var replace_to = '___' + id + '[0]';
				var replace_with = id + '[' + item_id + ']';
				var tmpl = parent.find('.matrixaddons-repeater-item.matrixaddons-repeater-hidden').html();
				var replacedTemplate = _that._replaceAll(tmpl, replace_to, replace_with);
				var newTemplate = $('<div class="matrixaddons-repeater-item" data-item-id="' + item_id + '">').append(replacedTemplate);
				parent.find('.matrixaddons-repeater-wrapper').append(newTemplate);
				$(this).closest('.matrixaddons-field-group').trigger('easy_invoice_repeater_modify', $(this).closest('.matrixaddons-field-group').attr('id'));

			})
			$('body').on('click', '.matrixaddons-repeater-title', function (e) {
				if (!$(e.target).hasClass('matrixaddons-repeater-remove')) {
					$(this).closest('.matrixaddons-field-group').trigger('easy_invoice_repeater_modify', $(this).closest('.matrixaddons-field-group').attr('id'));
					var el = $(this).closest('.matrixaddons-repeater-item').find('.matrixaddons-repeater-content');
					if (el.hasClass('matrixaddons-hide')) {
						$(this).closest('.matrixaddons-repeater-item').find('.matrixaddons-repeater-header-icon').removeClass('dashicons dashicons-arrow-up-alt2').addClass('dashicons dashicons-arrow-down-alt2');

						el.removeClass('matrixaddons-hide');
					} else {
						$(this).closest('.matrixaddons-repeater-item').find('.matrixaddons-repeater-header-icon').removeClass('dashicons dashicons-arrow-down-alt2').addClass('dashicons dashicons-arrow-up-alt2');

						el.addClass('matrixaddons-hide');

					}
				}
			})

			$('body').on('click', '.matrixaddons-repeater-remove', function () {
				var min_item = parseInt($(this).attr('data-min-item'));
				var min_item_message = $(this).attr('data-min-item-message');
				var item_length = $(this).closest('.matrixaddons-repeater-wrapper').find('.matrixaddons-repeater-item').length;
				if (item_length <= min_item) {
					alert(min_item_message);
					return;
				}
				var confirm = $(this).attr('data-confirm');
				if (window.confirm((confirm))) {
					var wrap = $(this).closest('.matrixaddons-repeater-wrapper');
					var field_group = $(this).closest('.matrixaddons-field-group');
					$(this).closest('.matrixaddons-repeater-item').remove();
					field_group.trigger('easy_invoice_repeater_modify', field_group.attr('id'));

				}
			});

			if (typeof $().flatpickr !== 'undefined') {
				$('.easy-invoice-datepicker').flatpickr({
					dateFormat: 'F d, Y'
				});
			}
		},

		reindexRepeaterItems: function (wrap) {
			var _that = this;
			var items = $(wrap).find('.matrixaddons-repeater-item');
			var index_id = 0;
			$.each(items, function () {

				var old_index = $(this).attr('data-item-id');


				if (old_index != index_id) {

					var elements = $(this).find('[name*="[' + old_index + ']"], [id*="[' + old_index + ']"]');


					$.each(elements, function () {
						var element = $(this);

						if ($(this).attr("name")) {
							var name = element.attr('name');
							var new_name = _that._replaceAll(name, old_index, index_id);
							$(this).attr('name', new_name);
						}
						if ($(this).attr("id")) {
							var id = element.attr('id');
							var new_id = _that._replaceAll(id, old_index, index_id);
							$(this).attr('id', new_id);
						}
					})

				}
				$(this).attr('data-item-id', index_id);
				index_id++;
			});
		},


		_replaceAll: function (str, toReplace, replaceWith) {
			return str ? str.split(toReplace).join(replaceWith) : '';
		},
		initMediaUploader: function () {
			var _this = this;
			$('body').on('click', '.easy-invoice-image-field-add', function (event) {
				event.preventDefault();
				_this.uploadWindow($(this), $(this).closest('.easy-invoice-image-field-wrap'));
			});
			$('body').on('click', '.easy-invoice-image-delete', function (event) {
				event.preventDefault();
				var imageField = $(this).closest('.matrixaddons-field-image');
				imageField.find('.image-wrapper').attr('data-url', '');
				imageField.find('.image-container, .field-container').addClass('matrixaddons-hide');
				imageField.find('.easy-invoice-image-field-add').removeClass('matrixaddons-hide');


			});
		},
		uploadWindow: function (uploadBtn, wrapper) {

			var _this = this;
			if (this.image_upload_frame) this.image_upload_frame.close();

			this.image_upload_frame = wp.media.frames.file_frame = wp.media({
				title: uploadBtn.data('uploader-title'),
				button: {
					text: uploadBtn.data('uploader-button-text'),
				},
				multiple: false
			});

			this.image_upload_frame.on('select', function () {

				var selection = _this.image_upload_frame.state().get('selection');
				var selected_list_node = wrapper.find('.image-container');
				var imageHtml = '';
				var attachment_id = 0;
				selection.map(function (attachment_object, i) {
					var attachment = attachment_object.toJSON();
					attachment_id = attachment.id;

					var attachment_url = attachment.sizes.full.url;
					imageHtml = _this.getImageElement(attachment_url);

				});

				if (attachment_id > 0) {
					wrapper.find('.image-container, .field-container').removeClass('matrixaddons-hide');
					wrapper.find('.easy-invoice-image-field-add').addClass('matrixaddons-hide');
					selected_list_node.find('.image-wrapper').remove();
					selected_list_node.append(imageHtml);

				}
			});


			this.image_upload_frame.open();
		},
		getImageElement: function (src) {
			return '<div data-url="' + src + '" class="image-wrapper"><div class="image-content"><img src="' + src + '" alt=""><div class="image-overlay"><a class="easy-invoice-image-delete remove dashicons dashicons-trash"></a></div></div></div>';
		},


	};

	$(document).ready(function () {
		EasyInvoiceAdmin.init();
	});
}(jQuery));
