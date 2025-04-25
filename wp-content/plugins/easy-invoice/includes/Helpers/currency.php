<?php

use MatrixAddons\EasyInvoice\Constant;

if (!function_exists('easy_invoice_get_currency')) {
	function easy_invoice_get_currency()
	{
		return apply_filters('easy_invoice_currency', get_option('easy_invoice_currency', 'USD'));
	}
}

if (!function_exists('easy_invoice_get_currency_symbol_val')) {
	function easy_invoice_get_currency_symbol_val($currency_key = '')
	{
		$symbols = apply_filters('easy_invoice_currency_symbols_val', array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRO' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#x434;&#x438;&#x43d;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STD' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		));

		$currency_symbol = $symbols[$currency_key] ?? '';

		return apply_filters('easy_invoice_currency_symbol_val', $currency_symbol, $currency_key);
	}
}

if (!function_exists('easy_invoice_get_currencies')) {
	function easy_invoice_get_currencies($currency_key = '')
	{
		$currencies = array_unique(
			apply_filters('easy_invoice_currencies',
				array(
					'AED' => __('United Arab Emirates dirham', 'easy-invoice'),
					'AFN' => __('Afghan afghani', 'easy-invoice'),
					'ALL' => __('Albanian lek', 'easy-invoice'),
					'AMD' => __('Armenian dram', 'easy-invoice'),
					'ANG' => __('Netherlands Antillean guilder', 'easy-invoice'),
					'AOA' => __('Angolan kwanza', 'easy-invoice'),
					'ARS' => __('Argentine peso', 'easy-invoice'),
					'AUD' => __('Australian dollar', 'easy-invoice'),
					'AWG' => __('Aruban florin', 'easy-invoice'),
					'AZN' => __('Azerbaijani manat', 'easy-invoice'),
					'BAM' => __('Bosnia and Herzegovina convertible mark', 'easy-invoice'),
					'BBD' => __('Barbadian dollar', 'easy-invoice'),
					'BDT' => __('Bangladeshi taka', 'easy-invoice'),
					'BGN' => __('Bulgarian lev', 'easy-invoice'),
					'BHD' => __('Bahraini dinar', 'easy-invoice'),
					'BIF' => __('Burundian franc', 'easy-invoice'),
					'BMD' => __('Bermudian dollar', 'easy-invoice'),
					'BND' => __('Brunei dollar', 'easy-invoice'),
					'BOB' => __('Bolivian boliviano', 'easy-invoice'),
					'BRL' => __('Brazilian real', 'easy-invoice'),
					'BSD' => __('Bahamian dollar', 'easy-invoice'),
					'BTC' => __('Bitcoin', 'easy-invoice'),
					'BTN' => __('Bhutanese ngultrum', 'easy-invoice'),
					'BWP' => __('Botswana pula', 'easy-invoice'),
					'BYR' => __('Belarusian ruble (old)', 'easy-invoice'),
					'BYN' => __('Belarusian ruble', 'easy-invoice'),
					'BZD' => __('Belize dollar', 'easy-invoice'),
					'CAD' => __('Canadian dollar', 'easy-invoice'),
					'CDF' => __('Congolese franc', 'easy-invoice'),
					'CHF' => __('Swiss franc', 'easy-invoice'),
					'CLP' => __('Chilean peso', 'easy-invoice'),
					'CNY' => __('Chinese yuan', 'easy-invoice'),
					'COP' => __('Colombian peso', 'easy-invoice'),
					'CRC' => __('Costa Rican col&oacute;n', 'easy-invoice'),
					'CUC' => __('Cuban convertible peso', 'easy-invoice'),
					'CUP' => __('Cuban peso', 'easy-invoice'),
					'CVE' => __('Cape Verdean escudo', 'easy-invoice'),
					'CZK' => __('Czech koruna', 'easy-invoice'),
					'DJF' => __('Djiboutian franc', 'easy-invoice'),
					'DKK' => __('Danish krone', 'easy-invoice'),
					'DOP' => __('Dominican peso', 'easy-invoice'),
					'DZD' => __('Algerian dinar', 'easy-invoice'),
					'EGP' => __('Egyptian pound', 'easy-invoice'),
					'ERN' => __('Eritrean nakfa', 'easy-invoice'),
					'ETB' => __('Ethiopian birr', 'easy-invoice'),
					'EUR' => __('Euro', 'easy-invoice'),
					'FJD' => __('Fijian dollar', 'easy-invoice'),
					'FKP' => __('Falkland Islands pound', 'easy-invoice'),
					'GBP' => __('Pound sterling', 'easy-invoice'),
					'GEL' => __('Georgian lari', 'easy-invoice'),
					'GGP' => __('Guernsey pound', 'easy-invoice'),
					'GHS' => __('Ghana cedi', 'easy-invoice'),
					'GIP' => __('Gibraltar pound', 'easy-invoice'),
					'GMD' => __('Gambian dalasi', 'easy-invoice'),
					'GNF' => __('Guinean franc', 'easy-invoice'),
					'GTQ' => __('Guatemalan quetzal', 'easy-invoice'),
					'GYD' => __('Guyanese dollar', 'easy-invoice'),
					'HKD' => __('Hong Kong dollar', 'easy-invoice'),
					'HNL' => __('Honduran lempira', 'easy-invoice'),
					'HRK' => __('Croatian kuna', 'easy-invoice'),
					'HTG' => __('Haitian gourde', 'easy-invoice'),
					'HUF' => __('Hungarian forint', 'easy-invoice'),
					'IDR' => __('Indonesian rupiah', 'easy-invoice'),
					'ILS' => __('Israeli new shekel', 'easy-invoice'),
					'IMP' => __('Manx pound', 'easy-invoice'),
					'INR' => __('Indian rupee', 'easy-invoice'),
					'IQD' => __('Iraqi dinar', 'easy-invoice'),
					'IRR' => __('Iranian rial', 'easy-invoice'),
					'IRT' => __('Iranian toman', 'easy-invoice'),
					'ISK' => __('Icelandic kr&oacute;na', 'easy-invoice'),
					'JEP' => __('Jersey pound', 'easy-invoice'),
					'JMD' => __('Jamaican dollar', 'easy-invoice'),
					'JOD' => __('Jordanian dinar', 'easy-invoice'),
					'JPY' => __('Japanese yen', 'easy-invoice'),
					'KES' => __('Kenyan shilling', 'easy-invoice'),
					'KGS' => __('Kyrgyzstani som', 'easy-invoice'),
					'KHR' => __('Cambodian riel', 'easy-invoice'),
					'KMF' => __('Comorian franc', 'easy-invoice'),
					'KPW' => __('North Korean won', 'easy-invoice'),
					'KRW' => __('South Korean won', 'easy-invoice'),
					'KWD' => __('Kuwaiti dinar', 'easy-invoice'),
					'KYD' => __('Cayman Islands dollar', 'easy-invoice'),
					'KZT' => __('Kazakhstani tenge', 'easy-invoice'),
					'LAK' => __('Lao kip', 'easy-invoice'),
					'LBP' => __('Lebanese pound', 'easy-invoice'),
					'LKR' => __('Sri Lankan rupee', 'easy-invoice'),
					'LRD' => __('Liberian dollar', 'easy-invoice'),
					'LSL' => __('Lesotho loti', 'easy-invoice'),
					'LYD' => __('Libyan dinar', 'easy-invoice'),
					'MAD' => __('Moroccan dirham', 'easy-invoice'),
					'MDL' => __('Moldovan leu', 'easy-invoice'),
					'MGA' => __('Malagasy ariary', 'easy-invoice'),
					'MKD' => __('Macedonian denar', 'easy-invoice'),
					'MMK' => __('Burmese kyat', 'easy-invoice'),
					'MNT' => __('Mongolian t&ouml;gr&ouml;g', 'easy-invoice'),
					'MOP' => __('Macanese pataca', 'easy-invoice'),
					'MRO' => __('Mauritanian ouguiya', 'easy-invoice'),
					'MUR' => __('Mauritian rupee', 'easy-invoice'),
					'MVR' => __('Maldivian rufiyaa', 'easy-invoice'),
					'MWK' => __('Malawian kwacha', 'easy-invoice'),
					'MXN' => __('Mexican peso', 'easy-invoice'),
					'MYR' => __('Malaysian ringgit', 'easy-invoice'),
					'MZN' => __('Mozambican metical', 'easy-invoice'),
					'NAD' => __('Namibian dollar', 'easy-invoice'),
					'NGN' => __('Nigerian naira', 'easy-invoice'),
					'NIO' => __('Nicaraguan c&oacute;rdoba', 'easy-invoice'),
					'NOK' => __('Norwegian krone', 'easy-invoice'),
					'NPR' => __('Nepalese rupee', 'easy-invoice'),
					'NZD' => __('New Zealand dollar', 'easy-invoice'),
					'OMR' => __('Omani rial', 'easy-invoice'),
					'PAB' => __('Panamanian balboa', 'easy-invoice'),
					'PEN' => __('Peruvian nuevo sol', 'easy-invoice'),
					'PGK' => __('Papua New Guinean kina', 'easy-invoice'),
					'PHP' => __('Philippine peso', 'easy-invoice'),
					'PKR' => __('Pakistani rupee', 'easy-invoice'),
					'PLN' => __('Polish z&#x142;oty', 'easy-invoice'),
					'PRB' => __('Transnistrian ruble', 'easy-invoice'),
					'PYG' => __('Paraguayan guaran&iacute;', 'easy-invoice'),
					'QAR' => __('Qatari riyal', 'easy-invoice'),
					'RON' => __('Romanian leu', 'easy-invoice'),
					'RSD' => __('Serbian dinar', 'easy-invoice'),
					'RUB' => __('Russian ruble', 'easy-invoice'),
					'RWF' => __('Rwandan franc', 'easy-invoice'),
					'SAR' => __('Saudi riyal', 'easy-invoice'),
					'SBD' => __('Solomon Islands dollar', 'easy-invoice'),
					'SCR' => __('Seychellois rupee', 'easy-invoice'),
					'SDG' => __('Sudanese pound', 'easy-invoice'),
					'SEK' => __('Swedish krona', 'easy-invoice'),
					'SGD' => __('Singapore dollar', 'easy-invoice'),
					'SHP' => __('Saint Helena pound', 'easy-invoice'),
					'SLL' => __('Sierra Leonean leone', 'easy-invoice'),
					'SOS' => __('Somali shilling', 'easy-invoice'),
					'SRD' => __('Surinamese dollar', 'easy-invoice'),
					'SSP' => __('South Sudanese pound', 'easy-invoice'),
					'STD' => __('S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'easy-invoice'),
					'SYP' => __('Syrian pound', 'easy-invoice'),
					'SZL' => __('Swazi lilangeni', 'easy-invoice'),
					'THB' => __('Thai baht', 'easy-invoice'),
					'TJS' => __('Tajikistani somoni', 'easy-invoice'),
					'TMT' => __('Turkmenistan manat', 'easy-invoice'),
					'TND' => __('Tunisian dinar', 'easy-invoice'),
					'TOP' => __('Tongan pa&#x2bb;anga', 'easy-invoice'),
					'TRY' => __('Turkish lira', 'easy-invoice'),
					'TTD' => __('Trinidad and Tobago dollar', 'easy-invoice'),
					'TWD' => __('New Taiwan dollar', 'easy-invoice'),
					'TZS' => __('Tanzanian shilling', 'easy-invoice'),
					'UAH' => __('Ukrainian hryvnia', 'easy-invoice'),
					'UGX' => __('Ugandan shilling', 'easy-invoice'),
					'USD' => __('United States (US) dollar', 'easy-invoice'),
					'UYU' => __('Uruguayan peso', 'easy-invoice'),
					'UZS' => __('Uzbekistani som', 'easy-invoice'),
					'VEF' => __('Venezuelan bol&iacute;var', 'easy-invoice'),
					'VND' => __('Vietnamese &#x111;&#x1ed3;ng', 'easy-invoice'),
					'VUV' => __('Vanuatu vatu', 'easy-invoice'),
					'WST' => __('Samoan t&#x101;l&#x101;', 'easy-invoice'),
					'XAF' => __('Central African CFA franc', 'easy-invoice'),
					'XCD' => __('East Caribbean dollar', 'easy-invoice'),
					'XOF' => __('West African CFA franc', 'easy-invoice'),
					'XPF' => __('CFP franc', 'easy-invoice'),
					'YER' => __('Yemeni rial', 'easy-invoice'),
					'ZAR' => __('South African rand', 'easy-invoice'),
					'ZMW' => __('Zambian kwacha', 'easy-invoice'),
				)
			)
		);

		if (!empty($currency_key) && isset($currencies[$currency_key])) {

			return $currencies[$currency_key];
		}
		return $currencies;
	}
}

if (!function_exists('easy_invoice_get_all_currency_with_symbol')) {

	function easy_invoice_get_all_currency_with_symbol($currency_position = 'right')
	{
		$currency = easy_invoice_get_currencies();

		$currency_with_symbol = array();

		foreach ($currency as $currency_key => $currency_value) {

			$symbol = easy_invoice_get_currency_symbol_val($currency_key);

			$currency_position = easy_invoice_get_currency_position();

			$value = !empty($symbol) ? '[ ' . $symbol . ' ] ' : '';

			$value = $currency_position == "left" ? $value . $currency_value : $currency_value . $value;

			$currency_with_symbol[$currency_key] = $currency_key . ' - ' . $value;
		}

		return $currency_with_symbol;
	}
}


if (!function_exists('easy_invoice_get_currency_symbol')) {

	function easy_invoice_get_currency_symbol($currency = '')
	{
		$currency = $currency === '' ? easy_invoice_get_currency() : $currency;

		$symbol_type = get_option('easy_invoice_currency_symbol_type', 'symbol');

		if ($symbol_type === 'code') {

			return apply_filters('easy_invoice_currency_symbol', $currency);

		}
		return apply_filters('easy_invoice_currency_symbol', easy_invoice_get_currency_symbol_val($currency));
	}
}
if (!function_exists('easy_invoice_get_currency_positions')) {

	function easy_invoice_get_currency_positions()
	{


		return [
			'left' => __('Left', 'easy-invoice'),
			'right' => __('Right', 'easy-invoice'),
			'left_space' => __('Left with space', 'easy-invoice'),
			'right_space' => __('Right with space', 'easy-invoice')

		];
	}
}

if (!function_exists('easy_invoice_get_price_number_decimals')) {
	function easy_invoice_get_price_number_decimals()
	{
		return get_option('easy_invoice_price_number_decimals', 2);
	}
}

if (!function_exists('easy_invoice_get_decimal_separator')) {
	function easy_invoice_get_decimal_separator()
	{
		return get_option('easy_invoice_decimal_separator', '.');
	}
}

if (!function_exists('easy_invoice_get_thousand_separator')) {
	function easy_invoice_get_thousand_separator()
	{
		return get_option('easy_invoice_thousand_separator', ',');
	}
}

if (!function_exists('easy_invoice_get_currency_position')) {
	function easy_invoice_get_currency_position()
	{
		return get_option('easy_invoice_currency_position', 'left');
	}
}

if (!function_exists('easy_invoice_get_price')) {

	function easy_invoice_get_price($price, $currency = '', $object_id = '', $echo = false)
	{
		$currency_symbol = easy_invoice_get_currency_symbol($currency);

		if (absint($object_id) > 0) {

			$ei_post_item = get_post($object_id);

			if ($ei_post_item->post_type === Constant::INVOICE_POST_TYPE || $ei_post_item->post_type === Constant::QUOTE_POST_TYPE) {

				if ($ei_post_item->post_type === Constant::INVOICE_POST_TYPE) {

					$easy_invoice = new \MatrixAddons\EasyInvoice\Repositories\InvoiceRepository($object_id);

					$ei_currency = $easy_invoice->get_currency();

					$ei_currency_symbol = $easy_invoice->get_currency_symbol();
				} else {

					$quote = new \MatrixAddons\EasyInvoice\Repositories\QuoteRepository($object_id);

					$ei_currency = $quote->get_currency();

					$ei_currency_symbol = $quote->get_currency_symbol();
				}

				$ei_currency = $ei_currency === '' ? $currency : $ei_currency;

				$symbol_type = get_option('easy_invoice_currency_symbol_type', 'symbol');

				$ei_currency_symbol = $ei_currency_symbol === '' ? $currency_symbol : $ei_currency_symbol;

				$currency_symbol = $ei_currency_symbol;

				if ($symbol_type === 'code') {

					$currency_symbol = $ei_currency;

				}
			}
		}
		$args = array(

			'decimals' => easy_invoice_get_price_number_decimals(),

			'decimal_separator' => easy_invoice_get_decimal_separator(),

			'thousand_separator' => easy_invoice_get_thousand_separator(),

		);

		if (floatval($price) < 0.00000000001) {
			$price = 0;
		}

		$price = apply_filters('formatted_easy_invoice_price',
			number_format($price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator']), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'], $price);

		$currency_position = easy_invoice_get_currency_position();

		if ($currency_position === "left_space") {

			$price_string = ($currency_symbol . ' ' . $price);

		} else if ($currency_position === "right_space") {
			$price_string = ($price . ' ' . $currency_symbol);

		} else if ($currency_position === "right") {

			$price_string = ($price . $currency_symbol);

		} else {
			$price_string = ($currency_symbol . $price);

		}


		if (!$echo) {
			return $price_string;
		}
		if ($echo) {
			echo esc_html($price_string);
		}
	}
}







