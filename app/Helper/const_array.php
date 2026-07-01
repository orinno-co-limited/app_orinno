<?php

use App\Models\Currency;

function currency_array($currency = null)
{
    if ($currency == null) {
        return Currency::get();
    } else {
        return Currency::where('currency', '!=', $currency)->get();
    }
}

function allowMimes()
{
    $output = [
        'image/png',
        'image/jpeg',
        'image/jpeg',
        'image/jpeg',
        'image/gif',
        'image/vnd.microsoft.icon',
        'image/svg+xml',
        'application/pdf',
        'application/vnd.ms-excel',
        'application/msword',
        'application/octet-stream',
        'font/sfnt'
    ];
    return $output;
}

function allowExtensions()
{
    $output = [
        'png',
        'jpeg',
        'jpg',
        'gif',
        'ttf',
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
    ];
    return $output;
}

function imageExtensionList()
{
    $output = [
        'png',
        'jpeg',
        'jpeg',
        'jpg',
        'gif'
    ];
    return $output;
}

function month($input = null)
{
    $output = [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function emailTemplates($input = null)
{
    $output = [
        EMAIL_TEMPLATE_CUSTOM => 'Custom',
        EMAIL_TEMPLATE_INVOICE => 'Invoice',
        EMAIL_TEMPLATE_REMINDER => 'Reminder',
        EMAIL_TEMPLATE_SIGN_UP => 'Sign Up',
        EMAIL_TEMPLATE_SUBSCRIPTION_SUCCESS => 'Subscription Success',
        EMAIL_TEMPLATE_THANK_YOU => 'Thank You',
        EMAIL_TEMPLATE_EMAIL_VERIFY => 'Email Verify',
        EMAIL_TEMPLATE_WELCOME => 'Welcome Mail',
        EMAIL_TEMPLATE_LISTING_REPLY => 'Listing Reply',
        EMAIL_TEMPLATE_LISTING_CONTACT => 'Listing Contact',
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function emailTemplateFields($input = null)
{
    $output  = [
        '{{amount}}' => 'john doe',
        '{{due_date}}' => date('Y-m-d'),
        '{{month}}' => '',
        '{{invoice_no}}' => '',
        '{{status}}' => '',
        '{{gateway}}' => '',
        '{{duration}}' => '',
        '{{app_name}}' => '',
        '{{email}}' => '',
        '{{otp}}' => '',
        '{{verify_link}}' => '',
        '{{user_name}}' => '',
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getPaymentServiceClass($input = null)
{
    $output = array(
        PAYPAL => 'App\Services\Payment\PaypalService',
        STRIPE => 'App\Services\Payment\StripeService',
        RAZORPAY => 'App\Services\Payment\RazorpayService',
        INSTAMOJO => 'App\Services\Payment\InstamojoService',
        MOLLIE => 'App\Services\Payment\MollieService',
        COINBASE => 'App\Services\Payment\CoinbaseService',
        PAYSTACK => 'App\Services\Payment\PaystackService',
        SSLCOMMERZ => 'App\Services\Payment\SslCommerzService',
        MERCADOPAGO => 'App\Services\Payment\MercadoPagoService',
        FLUTTERWAVE => 'App\Services\Payment\FlutterwaveService',
        IYZIPAY => 'App\Services\Payment\IyzipayService',
        BITPAY => 'App\Services\Payment\BitPayService',
        ZITOPAY => 'App\Services\Payment\ZitoPayService',
        BINANCE => 'App\Services\Payment\BinancePaymentService',
        PAYTM => 'App\Services\Payment\PaytmService',
        PAYHERE => 'App\Services\Payment\PayHerePaymentService',
        MAXICASH => 'App\Services\Payment\MaxiCashService',
        CINETPAY => 'App\Services\Payment\CinetPayService',
        VOGUEPAY => 'App\Services\Payment\VoguePayService',
        TOYYIBPAY => 'App\Services\Payment\ToyyibPayService',
        PAYMOB => 'App\Services\Payment\PaymobService',
        AUTHORIZE  => 'App\Services\Payment\AuthorizeNetService',
        ALIPAY => 'App\Services\Payment\AlipayService',
        XENDIT => 'App\Services\Payment\XenditService',
        PADDLE => 'App\Services\Payment\PaddleService',
        BANK => 'App\Services\Payment\BankService',  // Add the bank service here
        CASH => 'App\Services\Payment\CashService',  // Add the bank service here
    );
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input] ?? '';
    }
}

if (!function_exists("getGatewaySupportedCurrencies")) {
    function getGatewaySupportedCurrencies($gateway = null)
    {
        $supported_currencies = array(
            PAYPAL => [
                'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR',
                'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP',
                'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD', 'VND', 'ZAR'
            ],
            STRIPE => [
                // Comprehensive global currency support
                'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
                'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BRL',
                'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY',
                'COP', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD',
                'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GGP', 'GHS',
                'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF',
                'IDR', 'ILS', 'IMP', 'INR', 'IQD', 'IRR', 'ISK', 'JEP', 'JMD', 'JOD',
                'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT',
                'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD',
                'MMK', 'MNT', 'MOP', 'MRU', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN',
                'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK',
                'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR',
                'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STN',
                'SVC', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD',
                'TVD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VES', 'VND',
                'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW', 'ZWL'
            ],
            RAZORPAY => ['INR', 'USD', 'EUR', 'GBP', 'AED', 'AUD', 'CAD', 'SGD'],
            INSTAMOJO => ['INR'],
            MOLLIE => ['EUR', 'GBP', 'USD', 'CHF', 'PLN', 'SEK', 'NOK', 'DKK', 'AUD', 'CAD'],
            COINBASE => ['BTC', 'ETH', 'LTC', 'BCH', 'XRP', 'USDC', 'USDT', 'ADA', 'DOGE', 'MATIC', 'SHIB', 'APE', 'SOL', 'DOT', 'UNI', 'ATOM'],
            PAYSTACK => ['NGN', 'USD', 'ZAR', 'GHS', 'EUR', 'GBP'],
            SSLCOMMERZ => ['BDT', 'USD', 'INR', 'EUR', 'GBP'],
            MERCADOPAGO => ['ARS', 'BRL', 'CLP', 'COP', 'MXN', 'PEN', 'UYU', 'USD'],
            FLUTTERWAVE => ['NGN', 'USD', 'KES', 'GHS', 'ZAR', 'GBP', 'EUR'],
            IYZIPAY => ['TRY', 'USD', 'EUR', 'GBP'],
            BITPAY => ['BTC', 'BCH', 'ETH', 'USDT', 'DOGE', 'SHIB', 'LTC', 'WBTC', 'GUSD', 'USDC', 'DAI', 'EUROC'],
            ZITOPAY => ['USD', 'EUR', 'GBP', 'NGN'],  // Assuming major currencies based on typical global coverage
            BINANCE => ['BTC', 'ETH', 'BNB', 'USDT', 'BUSD', 'ADA', 'DOT', 'SOL'],  // Cryptocurrencies
            PAYTM => ['INR'],
            PAYHERE => ['LKR', 'USD', 'EUR', 'GBP'],
            MAXICASH => ['USD', 'XAF', 'XOF'],
            CINETPAY => ['XOF', 'XAF', 'EUR', 'USD'],
            VOGUEPAY => ['NGN', 'USD', 'GBP'],
            TOYYIBPAY => ['MYR'],
            PAYMOB => ['EGP'],
            AUTHORIZE => ['USD', 'CAD', 'GBP', 'EUR', 'AUD', 'NZD'],
            ALIPAY => [
                'CNY', 'USD', 'EUR', 'GBP', 'HKD', 'JPY', 'AUD', 'SGD', 'CAD', 'NZD',
                'KRW', 'THB'
            ],
            XENDIT => ['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR','SGD'],
            PADDLE => ['USD','EUR','GBP','AUD','CAD']
        );

        if (is_null($gateway)) {
            return $supported_currencies;
        } else {
            return $supported_currencies[$gateway] ?? [];
        }
    }
}

function country($input = null)
{
    $output = [
        'af' => 'Afghanistan',
        'al' => 'Albania',
        'dz' => 'Algeria',
        'ds' => 'American Samoa',
        'ad' => 'Andorra',
        'ao' => 'Angola',
        'ai' => 'Anguilla',
        'aq' => 'Antarctica',
        'ag' => 'Antigua and Barbuda',
        'ar' => 'Argentina',
        'am' => 'Armenia',
        'aw' => 'Aruba',
        'au' => 'Australia',
        'at' => 'Austria',
        'az' => 'Azerbaijan',
        'bs' => 'Bahamas',
        'bh' => 'Bahrain',
        'bd' => 'Bangladesh',
        'bb' => 'Barbados',
        'by' => 'Belarus',
        'be' => 'Belgium',
        'bz' => 'Belize',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bt' => 'Bhutan',
        'bo' => 'Bolivia',
        'ba' => 'Bosnia and Herzegovina',
        'bw' => 'Botswana',
        'br' => 'Brazil',
        'io' => 'British Indian Ocean Territory',
        'bn' => 'Brunei',
        'bg' => 'Bulgaria',
        'bf' => 'Burkina ',
        'bi' => 'Burundi',
        'kh' => 'Cambodia',
        'cm' => 'Cameroon',
        'ca' => 'Canada',
        'cv' => 'Cape Verde',
        'ky' => 'Cayman Islands',
        'cf' => 'Central African Republic',
        'td' => 'Chad',
        'cl' => 'Chile',
        'cn' => 'China',
        'cx' => 'Christmas Island',
        'cc' => 'Cocos Islands',
        'co' => 'Colombia',
        'km' => 'Comoros',
        'ck' => 'Cook Islands',
        'cr' => 'Costa Rica',
        'hr' => 'Croatia',
        'cu' => 'Cuba',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'cg' => 'Congo',
        'dk' => 'Denmark',
        'dj' => 'Djibouti',
        'dm' => 'Dominica',
        'tp' => 'East Timor',
        'ec' => 'Ecuador',
        'eg' => 'Egypt',
        'sv' => 'El Salvador',
        'gq' => 'Equatorial Guinea',
        'er' => 'Eritrea',
        'ee' => 'Estonia',
        'et' => 'Ethiopia',
        'fk' => 'Falkland Islands',
        'fo' => 'Faroe ',
        'fj' => 'Fiji',
        'fi' => 'Finland',
        'fr' => 'France',
        'pf' => 'French Polynesia',
        'ga' => 'Gabon',
        'gm' => 'Gambia',
        'ge' => 'Georgia',
        'de' => 'Germany',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gr' => 'Greece',
        'gl' => 'Greenland',
        'gd' => 'Grenada',
        'gu' => 'Guam',
        'gt' => 'Guatemala',
        'gk' => 'Guernsey',
        'gn' => 'Guinea',
        'gw' => 'Guinea-',
        'gy' => 'Guyana',
        'ht' => 'Haiti',
        'hn' => 'Honduras',
        'hk' => 'Hong Kong',
        'hu' => 'Hungary',
        'is' => 'Iceland',
        'in' => 'India',
        'id' => 'Indonesia',
        'ir' => 'Iran',
        'iq' => 'Iraq',
        'ie' => 'Ireland',
        'im' => 'Isle of ',
        'il' => 'Israel',
        'it' => 'Italy',
        'ci' => 'Ivory ',
        'jm' => 'Jamaica',
        'jp' => 'Japan',
        'je' => 'Jersey',
        'jo' => 'Jordan',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'ki' => 'Kiribati',
        'kp' => 'North Korea',
        'kr' => 'South Korea',
        'xk' => 'Kosovo',
        'kw' => 'Kuwait',
        'kg' => 'Kyrgyzstan',
        'la' => 'Laos',
        'lv' => 'Latvia',
        'lb' => 'Lebanon',
        'ls' => 'Lesotho',
        'lr' => 'Liberia',
        'ly' => 'Libya',
        'li' => 'Liechtenstein',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'mo' => 'Macau',
        'mk' => 'Macedonia',
        'mg' => 'Madagascar',
        'mw' => 'Malawi',
        'my' => 'Malaysia',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malta',
        'mh' => 'Marshall Islands',
        'mr' => 'Mauritania',
        'mu' => 'Mauritius',
        'ty' => 'Mayotte',
        'mx' => 'Mexico',
        'fm' => 'Micronesia',
        'md' => 'Moldova, Republic of',
        'mc' => 'Monaco',
        'mn' => 'Mongolia',
        'me' => 'Montenegro',
        'ms' => 'Montserrat',
        'ma' => 'Morocco',
        'mz' => 'Mozambique',
        'mm' => 'Myanmar',
        'na' => 'Namibia',
        'nr' => 'Nauru',
        'np' => 'Nepal',
        'nl' => 'Netherlands',
        'an' => 'Netherlands Antilles',
        'nc' => 'New Caledonia',
        'nz' => 'New Zealand',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'nu' => 'Niue',
        'mp' => 'Northern Mariana Islands',
        'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan',
        'pw' => 'Palau',
        'ps' => 'Palestine',
        'pa' => 'Panama',
        'pg' => 'Papua New Guinea',
        'py' => 'Paraguay',
        'pe' => 'Peru',
        'ph' => 'Philippines',
        'pn' => 'Pitcairn',
        'pl' => 'Poland',
        'pt' => 'Portugal',
        'qa' => 'Qatar',
        're' => 'Reunion',
        'ro' => 'Romania',
        'ru' => 'Russian',
        'rw' => 'Rwanda',
        'kn' => 'Saint Kitts and Nevis',
        'lc' => 'Saint Lucia',
        'vc' => 'Saint Vincent and the Grenadines',
        'ws' => 'Samoa',
        'sm' => 'San Marino',
        'st' => 'Sao Tome and ',
        'sa' => 'Saudi Arabia',
        'sn' => 'Senegal',
        'rs' => 'Serbia',
        'sc' => 'Seychelles',
        'sl' => 'Sierra ',
        'sg' => 'Singapore',
        'sk' => 'Slovakia',
        'si' => 'Slovenia',
        'sb' => 'Solomon Islands',
        'so' => 'Somalia',
        'za' => 'South Africa',
        'es' => 'Spain',
        'lk' => 'Sri Lanka',
        'sd' => 'Sudan',
        'sr' => 'Suriname',
        'sj' => 'Svalbard and Jan Mayen ',
        'sz' => 'Swaziland',
        'se' => 'Sweden',
        'ch' => 'Switzerland',
        'sy' => 'Syria',
        'tw' => 'Taiwan',
        'tj' => 'Tajikistan',
        'tz' => 'Tanzania',
        'th' => 'Thailand',
        'tg' => 'Togo',
        'tk' => 'Tokelau',
        'to' => 'Tonga',
        'tt' => 'Trinidad and Tobago',
        'tn' => 'Tunisia',
        'tr' => 'Turkey',
        'tm' => 'Turkmenistan',
        'tc' => 'Turks and Caicos Islands',
        'tv' => 'Tuvalu',
        'ug' => 'Uganda',
        'ua' => 'Ukraine',
        'ae' => 'United Arab Emirates',
        'gb' => 'United ',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu',
        'va' => 'Vatican City State',
        've' => 'Venezuela',
        'vn' => 'Vietnam',
        'vi' => 'Virgin Islands (U.S.)',
        'wf' => 'Wallis and Futuna Islands',
        'eh' => 'Western ',
        'ye' => 'Yemen',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe'
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function languageIsoCode($input = null)
{
    $output = [
        "af" => "Afrikaans",
        "sq" => "shqip",
        "am" => "አማርኛ",
        "ar" => "العربية",
        "an" => "aragonés",
        "hy" => "հայերեն",
        "ast" => "asturianu",
        "az" => "azərbaycan dili",
        "eu" => "euskara",
        "be" => "беларуская",
        "bn" => "বাংলা",
        "bs" => "bosanski",
        "br" => "brezhoneg",
        "bg" => "български",
        "ca" => "català",
        "ckb" => "کوردی (دەستنوسی عەرەبی)",
        "zh" => "中文",
        "zh-HK" => "中文（香港）",
        "zh-CN" => "中文（简体）",
        "zh-TW" => "中文（繁體）",
        "co" => "Corsican",
        "hr" => "hrvatski",
        "cs" => "čeština",
        "da" => "dansk",
        "nl" => "Nederlands",
        "en" => "English",
        "en-AU" => "English (Australia)",
        "en-CA" => "English (Canada)",
        "en-IN" => "English (India)",
        "en-NZ" => "English (New Zealand)",
        "en-ZA" => "English (South Africa)",
        "en-GB" => "English (United Kingdom)",
        "en-US" => "English (United States)",
        "eo" => "esperanto",
        "et" => "eesti",
        "fo" => "føroyskt",
        "fil" => "Filipino",
        "fi" => "suomi",
        "fr" => "français",
        "fr-CA" => "français (Canada)",
        "fr-FR" => "français (France)",
        "fr-CH" => "français (Suisse)",
        "gl" => "galego",
        "ka" => "ქართული",
        "de" => "Deutsch",
        "de-AT" => "Deutsch (Österreich)",
        "de-DE" => "Deutsch (Deutschland)",
        "de-LI" => "Deutsch (Liechtenstein)",
        "de-CH" => "Deutsch (Schweiz)",
        "el" => "Ελληνικά",
        "gn" => "Guarani",
        "gu" => "ગુજરાતી",
        "ha" => "Hausa",
        "haw" => "ʻŌlelo Hawaiʻi",
        "he" => "עברית",
        "hi" => "हिन्दी",
        "hu" => "magyar",
        "is" => "íslenska",
        "id" => "Indonesia",
        "ia" => "Interlingua",
        "ga" => "Gaeilge",
        "it" => "italiano",
        "it-IT" => "italiano (Italia)",
        "it-CH" => "italiano (Svizzera)",
        "ja" => "日本語",
        "kn" => "ಕನ್ನಡ",
        "kk" => "қазақ тілі",
        "km" => "ខ្មែរ",
        "ko" => "한국어",
        "ku" => "Kurdî",
        "ky" => "кыргызча",
        "lo" => "ລາວ",
        "la" => "Latin",
        "lv" => "latviešu",
        "ln" => "lingála",
        "lt" => "lietuvių",
        "mk" => "македонски",
        "ms" => "Bahasa Melayu",
        "ml" => "മലയാളം",
        "mt" => "Malti",
        "mr" => "मराठी",
        "mn" => "монгол",
        "ne" => "नेपाली",
        "no" => "norsk",
        "nb" => "norsk bokmål",
        "nn" => "nynorsk",
        "oc" => "Occitan",
        "or" => "ଓଡ଼ିଆ",
        "om" => "Oromoo",
        "ps" => "پښتو",
        "fa" => "فارسی",
        "pl" => "polski",
        "pt" => "português",
        "pt-BR" => "português (Brasil)",
        "pt-PT" => "português (Portugal)",
        "pa" => "ਪੰਜਾਬੀ",
        "qu" => "Quechua",
        "ro" => "română",
        "mo" => "română (Moldova)",
        "rm" => "rumantsch",
        "ru" => "русский",
        "gd" => "Scottish Gaelic",
        "sr" => "српски",
        "sh" => "Croatian",
        "sn" => "chiShona",
        "sd" => "Sindhi",
        "si" => "සිංහල",
        "sk" => "slovenčina",
        "sl" => "slovenščina",
        "so" => "Soomaali",
        "st" => "Southern Sotho",
        "es" => "español",
        "es-AR" => "español (Argentina)",
        "es-419" => "español (Latinoamérica)",
        "es-MX" => "español (México)",
        "es-ES" => "español (España)",
        "es-US" => "español (Estados Unidos)",
        "su" => "Sundanese",
        "sw" => "Kiswahili",
        "sv" => "svenska",
        "tg" => "тоҷикӣ",
        "ta" => "தமிழ்",
        "tt" => "Tatar",
        "te" => "తెలుగు",
        "th" => "ไทย",
        "ti" => "ትግርኛ",
        "to" => "lea fakatonga",
        "tr" => "Türkçe",
        "tk" => "Turkmen",
        "tw" => "Twi",
        "uk" => "українська",
        "ur" => "اردو",
        "ug" => "Uyghur",
        "uz" => "o‘zbek",
        "vi" => "Tiếng Việt",
        "wa" => "wa",
        "cy" => "Cymraeg",
        "fy" => "Western Frisian",
        "xh" => "Xhosa",
        "yi" => "Yiddish",
        "yo" => "Èdè Yorùbá",
        "zu" => "isiZulu"
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getCurrency($currency = null, $only_symbol = false)
{
    $currency_list = array(
        "AFA" => array("name" => "Afghan Afghani", "symbol" => "؋"),
        "ALL" => array("name" => "Albanian Lek", "symbol" => "Lek"),
        "DZD" => array("name" => "Algerian Dinar", "symbol" => "دج"),
        "AOA" => array("name" => "Angolan Kwanza", "symbol" => "Kz"),
        "ARS" => array("name" => "Argentine Peso", "symbol" => "$"),
        "AMD" => array("name" => "Armenian Dram", "symbol" => "֏"),
        "AWG" => array("name" => "Aruban Florin", "symbol" => "ƒ"),
        "AUD" => array("name" => "Australian Dollar", "symbol" => "$"),
        "AZN" => array("name" => "Azerbaijani Manat", "symbol" => "m"),
        "BSD" => array("name" => "Bahamian Dollar", "symbol" => "B$"),
        "BHD" => array("name" => "Bahraini Dinar", "symbol" => ".د.ب"),
        "BDT" => array("name" => "Bangladeshi Taka", "symbol" => "৳"),
        "BBD" => array("name" => "Barbadian Dollar", "symbol" => "Bds$"),
        "BYR" => array("name" => "Belarusian Ruble", "symbol" => "Br"),
        "BEF" => array("name" => "Belgian Franc", "symbol" => "fr"),
        "BZD" => array("name" => "Belize Dollar", "symbol" => "$"),
        "BMD" => array("name" => "Bermudan Dollar", "symbol" => "$"),
        "BTN" => array("name" => "Bhutanese Ngultrum", "symbol" => "Nu."),
        "BTC" => array("name" => "Bitcoin", "symbol" => "฿"),
        "BOB" => array("name" => "Bolivian Boliviano", "symbol" => "Bs."),
        "BAM" => array("name" => "Bosnia", "symbol" => "KM"),
        "BWP" => array("name" => "Botswanan Pula", "symbol" => "P"),
        "BRL" => array("name" => "Brazilian Real", "symbol" => "R$"),
        "GBP" => array("name" => "British Pound Sterling", "symbol" => "£"),
        "BND" => array("name" => "Brunei Dollar", "symbol" => "B$"),
        "BGN" => array("name" => "Bulgarian Lev", "symbol" => "Лв."),
        "BIF" => array("name" => "Burundian Franc", "symbol" => "FBu"),
        "KHR" => array("name" => "Cambodian Riel", "symbol" => "KHR"),
        "CAD" => array("name" => "Canadian Dollar", "symbol" => "$"),
        "CVE" => array("name" => "Cape Verdean Escudo", "symbol" => "$"),
        "KYD" => array("name" => "Cayman Islands Dollar", "symbol" => "$"),
        "XOF" => array("name" => "CFA Franc BCEAO", "symbol" => "CFA"),
        "XAF" => array("name" => "CFA Franc BEAC", "symbol" => "FCFA"),
        "XPF" => array("name" => "CFP Franc", "symbol" => "₣"),
        "CLP" => array("name" => "Chilean Peso", "symbol" => "$"),
        "CNY" => array("name" => "Chinese Yuan", "symbol" => "¥"),
        "COP" => array("name" => "Colombian Peso", "symbol" => "$"),
        "KMF" => array("name" => "Comorian Franc", "symbol" => "CF"),
        "CDF" => array("name" => "Congolese Franc", "symbol" => "FC"),
        "CRC" => array("name" => "Costa Rican ColÃ³n", "symbol" => "₡"),
        "HRK" => array("name" => "Croatian Kuna", "symbol" => "kn"),
        "CUC" => array("name" => "Cuban Convertible Peso", "symbol" => "$, CUC"),
        "CZK" => array("name" => "Czech Republic Koruna", "symbol" => "Kč"),
        "DKK" => array("name" => "Danish Krone", "symbol" => "Kr."),
        "DJF" => array("name" => "Djiboutian Franc", "symbol" => "Fdj"),
        "DOP" => array("name" => "Dominican Peso", "symbol" => "$"),
        "XCD" => array("name" => "East Caribbean Dollar", "symbol" => "$"),
        "EGP" => array("name" => "Egyptian Pound", "symbol" => "ج.م"),
        "ERN" => array("name" => "Eritrean Nakfa", "symbol" => "Nfk"),
        "EEK" => array("name" => "Estonian Kroon", "symbol" => "kr"),
        "ETB" => array("name" => "Ethiopian Birr", "symbol" => "Nkf"),
        "EUR" => array("name" => "Euro", "symbol" => "€"),
        "FKP" => array("name" => "Falkland Islands Pound", "symbol" => "£"),
        "FJD" => array("name" => "Fijian Dollar", "symbol" => "FJ$"),
        "GMD" => array("name" => "Gambian Dalasi", "symbol" => "D"),
        "GEL" => array("name" => "Georgian Lari", "symbol" => "ლ"),
        "DEM" => array("name" => "German Mark", "symbol" => "DM"),
        "GHS" => array("name" => "Ghanaian Cedi", "symbol" => "GH₵"),
        "GIP" => array("name" => "Gibraltar Pound", "symbol" => "£"),
        "GRD" => array("name" => "Greek Drachma", "symbol" => "₯, Δρχ, Δρ"),
        "GTQ" => array("name" => "Guatemalan Quetzal", "symbol" => "Q"),
        "GNF" => array("name" => "Guinean Franc", "symbol" => "FG"),
        "GYD" => array("name" => "Guyanaese Dollar", "symbol" => "$"),
        "HTG" => array("name" => "Haitian Gourde", "symbol" => "G"),
        "HNL" => array("name" => "Honduran Lempira", "symbol" => "L"),
        "HKD" => array("name" => "Hong Kong Dollar", "symbol" => "$"),
        "HUF" => array("name" => "Hungarian Forint", "symbol" => "Ft"),
        "ISK" => array("name" => "Icelandic KrÃ³na", "symbol" => "kr"),
        "INR" => array("name" => "Indian Rupee", "symbol" => "₹"),
        "IDR" => array("name" => "Indonesian Rupiah", "symbol" => "Rp"),
        "IRR" => array("name" => "Iranian Rial", "symbol" => "﷼"),
        "IQD" => array("name" => "Iraqi Dinar", "symbol" => "د.ع"),
        "ILS" => array("name" => "Israeli New Sheqel", "symbol" => "₪"),
        "ITL" => array("name" => "Italian Lira", "symbol" => "L,£"),
        "JMD" => array("name" => "Jamaican Dollar", "symbol" => "J$"),
        "JPY" => array("name" => "Japanese Yen", "symbol" => "¥"),
        "JOD" => array("name" => "Jordanian Dinar", "symbol" => "ا.د"),
        "KZT" => array("name" => "Kazakhstani Tenge", "symbol" => "лв"),
        "KES" => array("name" => "Kenyan Shilling", "symbol" => "KSh"),
        "KWD" => array("name" => "Kuwaiti Dinar", "symbol" => "ك.د"),
        "KGS" => array("name" => "Kyrgystani Som", "symbol" => "лв"),
        "LAK" => array("name" => "Laotian Kip", "symbol" => "₭"),
        "LVL" => array("name" => "Latvian Lats", "symbol" => "Ls"),
        "LBP" => array("name" => "Lebanese Pound", "symbol" => "£"),
        "LSL" => array("name" => "Lesotho Loti", "symbol" => "L"),
        "LRD" => array("name" => "Liberian Dollar", "symbol" => "$"),
        "LYD" => array("name" => "Libyan Dinar", "symbol" => "د.ل"),
        "LTL" => array("name" => "Lithuanian Litas", "symbol" => "Lt"),
        "MOP" => array("name" => "Macanese Pataca", "symbol" => "$"),
        "MKD" => array("name" => "Macedonian Denar", "symbol" => "ден"),
        "MGA" => array("name" => "Malagasy Ariary", "symbol" => "Ar"),
        "MWK" => array("name" => "Malawian Kwacha", "symbol" => "MK"),
        "MYR" => array("name" => "Malaysian Ringgit", "symbol" => "RM"),
        "MVR" => array("name" => "Maldivian Rufiyaa", "symbol" => "Rf"),
        "MRO" => array("name" => "Mauritanian Ouguiya", "symbol" => "MRU"),
        "MUR" => array("name" => "Mauritian Rupee", "symbol" => "₨"),
        "MXN" => array("name" => "Mexican Peso", "symbol" => "$"),
        "MDL" => array("name" => "Moldovan Leu", "symbol" => "L"),
        "MNT" => array("name" => "Mongolian Tugrik", "symbol" => "₮"),
        "MAD" => array("name" => "Moroccan Dirham", "symbol" => "MAD"),
        "MZM" => array("name" => "Mozambican Metical", "symbol" => "MT"),
        "MMK" => array("name" => "Myanmar Kyat", "symbol" => "K"),
        "NAD" => array("name" => "Namibian Dollar", "symbol" => "$"),
        "NPR" => array("name" => "Nepalese Rupee", "symbol" => "₨"),
        "ANG" => array("name" => "Netherlands Antillean Guilder", "symbol" => "ƒ"),
        "TWD" => array("name" => "New Taiwan Dollar", "symbol" => "$"),
        "NZD" => array("name" => "New Zealand Dollar", "symbol" => "$"),
        "NIO" => array("name" => "Nicaraguan CÃ³rdoba", "symbol" => "C$"),
        "NGN" => array("name" => "Nigerian Naira", "symbol" => "₦"),
        "KPW" => array("name" => "North Korean Won", "symbol" => "₩"),
        "NOK" => array("name" => "Norwegian Krone", "symbol" => "kr"),
        "OMR" => array("name" => "Omani Rial", "symbol" => ".ع.ر"),
        "PKR" => array("name" => "Pakistani Rupee", "symbol" => "₨"),
        "PAB" => array("name" => "Panamanian Balboa", "symbol" => "B/."),
        "PGK" => array("name" => "Papua New Guinean Kina", "symbol" => "K"),
        "PYG" => array("name" => "Paraguayan Guarani", "symbol" => "₲"),
        "PEN" => array("name" => "Peruvian Nuevo Sol", "symbol" => "S/."),
        "PHP" => array("name" => "Philippine Peso", "symbol" => "₱"),
        "PLN" => array("name" => "Polish Zloty", "symbol" => "zł"),
        "QAR" => array("name" => "Qatari Rial", "symbol" => "ق.ر"),
        "RON" => array("name" => "Romanian Leu", "symbol" => "lei"),
        "RUB" => array("name" => "Russian Ruble", "symbol" => "₽"),
        "RWF" => array("name" => "Rwandan Franc", "symbol" => "FRw"),
        "SVC" => array("name" => "Salvadoran ColÃ³n", "symbol" => "₡"),
        "WST" => array("name" => "Samoan Tala", "symbol" => "SAT"),
        "SAR" => array("name" => "Saudi Riyal", "symbol" => "﷼"),
        "RSD" => array("name" => "Serbian Dinar", "symbol" => "din"),
        "SCR" => array("name" => "Seychellois Rupee", "symbol" => "SRe"),
        "SLL" => array("name" => "Sierra Leonean Leone", "symbol" => "Le"),
        "SGD" => array("name" => "Singapore Dollar", "symbol" => "$"),
        "SKK" => array("name" => "Slovak Koruna", "symbol" => "Sk"),
        "SBD" => array("name" => "Solomon Islands Dollar", "symbol" => "Si$"),
        "SOS" => array("name" => "Somali Shilling", "symbol" => "Sh.so."),
        "ZAR" => array("name" => "South African Rand", "symbol" => "R"),
        "KRW" => array("name" => "South Korean Won", "symbol" => "₩"),
        "XDR" => array("name" => "Special Drawing Rights", "symbol" => "SDR"),
        "LKR" => array("name" => "Sri Lankan Rupee", "symbol" => "Rs"),
        "SHP" => array("name" => "St. Helena Pound", "symbol" => "£"),
        "SDG" => array("name" => "Sudanese Pound", "symbol" => ".س.ج"),
        "SRD" => array("name" => "Surinamese Dollar", "symbol" => "$"),
        "SZL" => array("name" => "Swazi Lilangeni", "symbol" => "E"),
        "SEK" => array("name" => "Swedish Krona", "symbol" => "kr"),
        "CHF" => array("name" => "Swiss Franc", "symbol" => "CHf"),
        "SYP" => array("name" => "Syrian Pound", "symbol" => "LS"),
        "STD" => array("name" => "São Tomé and Príncipe Dobra", "symbol" => "Db"),
        "TJS" => array("name" => "Tajikistani Somoni", "symbol" => "SM"),
        "TZS" => array("name" => "Tanzanian Shilling", "symbol" => "TSh"),
        "THB" => array("name" => "Thai Baht", "symbol" => "฿"),
        "TOP" => array("name" => "Tongan pa'anga", "symbol" => "$"),
        "TTD" => array("name" => "Trinidad & Tobago Dollar", "symbol" => "$"),
        "TND" => array("name" => "Tunisian Dinar", "symbol" => "ت.د"),
        "TRY" => array("name" => "Turkish Lira", "symbol" => "₺"),
        "TMT" => array("name" => "Turkmenistani Manat", "symbol" => "T"),
        "UGX" => array("name" => "Ugandan Shilling", "symbol" => "USh"),
        "UAH" => array("name" => "Ukrainian Hryvnia", "symbol" => "₴"),
        "AED" => array("name" => "United Arab Emirates Dirham", "symbol" => "إ.د"),
        "UYU" => array("name" => "Uruguayan Peso", "symbol" => "$"),
        "USD" => array("name" => "US Dollar", "symbol" => "$"),
        "UZS" => array("name" => "Uzbekistan Som", "symbol" => "лв"),
        "VUV" => array("name" => "Vanuatu Vatu", "symbol" => "VT"),
        "VEF" => array("name" => "Venezuelan BolÃvar", "symbol" => "Bs"),
        "VND" => array("name" => "Vietnamese Dong", "symbol" => "₫"),
        "YER" => array("name" => "Yemeni Rial", "symbol" => "﷼"),
        "ZMK" => array("name" => "Zambian Kwacha", "symbol" => "ZK"),
        "maxiDollar" => array("name" => "Maxi Dollar", "symbol" => "$"),
    );
    if (is_null($currency)) {
        $all_currency = [];
        foreach ($currency_list as $key => $item) {
            if ($only_symbol) {
                $all_currency[$key] = $item['symbol'];
            } else {
                $all_currency[$key] = $item['name'] . '(' . $item['symbol'] . ')';
            }
        }
        return $all_currency;
    } else {
        if ($only_symbol) {
            return $currency_list[$currency]['symbol'];
        } else {
            return $currency_list[$currency]['name'] . '(' . $currency_list[$currency]['symbol'] . ')';
        }
    }
}

function getRoleName($input = null)
{
    $output = [
        USER_ROLE_ADMIN => 'Admin',
        USER_ROLE_OWNER => 'Owner',
        USER_ROLE_TENANT => 'Tenant',
        USER_ROLE_MAINTAINER => 'Maintainer',
        USER_ROLE_TEAM_MEMBER => 'Team Member',
    ];


    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getDurationName($input = null)
{
    $output = [
        PACKAGE_DURATION_TYPE_MONTHLY => __('Monthly'),
        PACKAGE_DURATION_TYPE_YEARLY => __('Yearly')
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getDurationTypes($input = null)
{
    $output = [
        DURATION_TYPE_MONTHLY => __('Monthly'),
        DURATION_TYPE_YEARLY => __('Yearly')
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getPropertyAmenities($input = null)
{
    $output = [
        PROPERTY_AMENITY_FIRE_SECURITY => __('File Security'),
        PROPERTY_AMENITY_ELECTRICITY => __('Electricity'),
        PROPERTY_AMENITY_KITCHEN => __('Kitchen'),
        PROPERTY_AMENITY_GARAGE => __('Garage'),
        PROPERTY_AMENITY_SWIMMING_POOL => __('Swimming Pool'),
        PROPERTY_AMENITY_SECURITY_PRIVACY => __('Security Privacy'),
        PROPERTY_AMENITY_ECO_FRIENDLY_ENERGY => __('Eco - Friendly Energy'),
    ];


    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getPropertyAdvantages($input = null)
{
    $output = [
        PROPERTY_ADVANTAGE_PETS_ALLOWED => __('Pets Allowed'),
    ];


    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}
