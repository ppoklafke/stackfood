<?php

namespace App\Traits;


trait PaymentGatewayTrait
{
    public static function getPaymentGatewaySupportedCurrencies($key = null): array
    {
        $paymentGateway = [
            "amazon_pay" => [
                "USD" => "United States Dollar",
                "GBP" => "Pound Sterling",
                "EUR" => "Euro",
                "JPY" => "Japanese Yen",
                "AUD" => "Australian Dollar",
                "NZD" => "New Zealand Dollar",
                "CAD" => "Canadian Dollar",
                "DKK" => "Danish Krone",
                "HKD" => "Hong Kong Dollar",
                "SEK" => "Swedish Krona",
                "CHF" => "Swiss Franc"
            ],
            "bkash" => [
                "BDT" => "Bangladeshi Taka"
            ],
            "cashfree" => [
                "INR" => "Indian Rupee"
            ],
            "ccavenue" => [
                "INR" => "Indian Rupee",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "AUD" => "Australian Dollar",
                "CAD" => "Canadian Dollar",
                "JPY" => "Japanese Yen",
                "SGD" => "Singapore Dollar"
            ],
            "esewa" => [
                "NPR" => "Nepalese Rupee"
            ],
            "fatoorah" => [
                "KWD" => "Kuwaiti Dinar",
                "SAR" => "Saudi Riyal",
                "AED" => "United Arab Emirates Dirham",
                "BHD" => "Bahraini Dinar",
                "QAR" => "Qatari Riyal",
                "OMR" => "Omani Rial",
                "EGP" => "Egyptian Pound",
                "USD" => "United States Dollar"
            ],
            "flutterwave" => [
                "NGN" => "Nigerian Naira",
                "GHS" => "Ghanaian Cedi",
                "KES" => "Kenyan Shilling",
                "ZAR" => "South African Rand",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "RWF" => "Rwandan Franc",
                "UGX" => "Ugandan Shilling",
                "TZS" => "Tanzanian Shilling",
                "ZMW" => "Zambian Kwacha"
            ],
            "foloosi" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "USD" => "United States Dollar"
            ],
            "hubtel" => [
                "GHS" => "Ghanaian Cedi"
            ],
            "hyper_pay" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "EGP" => "Egyptian Pound",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar",
                "OMR" => "Omani Rial",
                "QAR" => "Qatari Riyal",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "JOD" => "Jordanian Dinar",
                "LBP" => "Lebanese Pound"
            ],
            "instamojo" => [
                "INR" => "Indian Rupee"
            ],
            "iyzi_pay" => [
                "TRY" => "Turkish Lira",
                "EUR" => "Euro",
                "USD" => "United States Dollar",
                "GBP" => "Pound Sterling"
            ],
            "liqpay" => [
                "UAH" => "Ukrainian Hryvnia",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "RUB" => "Russian Ruble",
                "BYN" => "Belarusian Ruble",
                "KZT" => "Kazakhstani Tenge"
            ],
            "maxicash" => [
                "PHP" => "Philippine Peso",
                "USD" => "United States Dollar"
            ],
            "mercadopago" => [
                "ARS" => "Argentine Peso",
                "BRL" => "Brazilian Real",
                "CLP" => "Chilean Peso",
                "COP" => "Colombian Peso",
                "MXN" => "Mexican Peso",
                "PEN" => "Peruvian Sol",
                "UYU" => "Uruguayan Peso",
                "USD" => "United States Dollar",
                "PYG" => "Paraguayan Guarani",
                "BOB" => "Bolivian Boliviano"
            ],
            "momo" => [
                "VND" => "Vietnamese Dong"
            ],
            "moncash" => [
                "HTG" => "Haitian Gourde",
                "USD" => "United States Dollar"
            ],
            "payfast" => [
                "ZAR" => "South African Rand",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling"
            ],
            "paymob_accept" => [
                "EGP" => "Egyptian Pound",
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "USD" => "United States Dollar",
                "EUR" => "Euro"
            ],
            "paypal" => [
                "AUD" => "Australian Dollar",
                "BRL" => "Brazilian Real",
                "CAD" => "Canadian Dollar",
                "CZK" => "Czech Koruna",
                "DKK" => "Danish Krone",
                "EUR" => "Euro",
                "HKD" => "Hong Kong Dollar",
                "HUF" => "Hungarian Forint",
                "INR" => "Indian Rupee",
                "ILS" => "Israeli New Shekel",
                "JPY" => "Japanese Yen",
                "MYR" => "Malaysian Ringgit",
                "MXN" => "Mexican Peso",
                "TWD" => "New Taiwan Dollar",
                "NZD" => "New Zealand Dollar",
                "NOK" => "Norwegian Krone",
                "PHP" => "Philippine Peso",
                "PLN" => "Polish Zloty",
                "GBP" => "Pound Sterling",
                "RUB" => "Russian Ruble",
                "SGD" => "Singapore Dollar",
                "SEK" => "Swedish Krona",
                "CHF" => "Swiss Franc",
                "THB" => "Thai Baht",
                "TRY" => "Turkish Lira",
                "USD" => "United States Dollar",
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "ZAR" => "South African Rand"
            ],
            "paystack" => [
                "NGN" => "Nigerian Naira",
                "KES" => "Kenyan Shilling",
                "GHS" => "Ghana Cedi",
                "ZAR" => "South African Rand",
                "XOF" => "West African CFA Franc",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling"
            ],
            "paytabs" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar",
                "OMR" => "Omani Rial",
                "QAR" => "Qatari Riyal",
                "EGP" => "Egyptian Pound",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "JOD" => "Jordanian Dinar",
                "LBP" => "Lebanese Pound"
            ],
            "paytm" => [
                "INR" => "Indian Rupee"
            ],
            "phonepe" => [
                "INR" => "Indian Rupee"
            ],
            "pvit" => [
                "NGN" => "Nigerian Naira"
            ],
            "razor_pay" => [
                "INR" => "Indian Rupee",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "SGD" => "Singapore Dollar",
                "AED" => "United Arab Emirates Dirham",
                "AUD" => "Australian Dollar",
                "CAD" => "Canadian Dollar",
                "JPY" => "Japanese Yen"
            ],
            "senang_pay" => [
                "MYR" => "Malaysian Ringgit"
            ],
            "sixcash" => [
                "BDT" => "Bangladeshi Taka"
            ],
            "ssl_commerz" => [
                "BDT" => "Bangladeshi Taka",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "CAD" => "Canadian Dollar",
                "AUD" => "Australian Dollar"
            ],
            "stripe" => [
                "USD" => "United States Dollar",
                "AUD" => "Australian Dollar",
                "CAD" => "Canadian Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "JPY" => "Japanese Yen",
                "NZD" => "New Zealand Dollar",
                "CHF" => "Swiss Franc",
                "DKK" => "Danish Krone",
                "NOK" => "Norwegian Krone",
                "SEK" => "Swedish Krona",
                "SGD" => "Singapore Dollar",
                "HKD" => "Hong Kong Dollar",
                "RON" => "Romanian Leu",
                "MXN" => "Mexican Peso",
                "BRL" => "Brazilian Real",
                "MYR" => "Malaysian Ringgit",
                "PLN" => "Polish Zloty",
                "THB" => "Thai Baht",
                "CZK" => "Czech Koruna",
                "HUF" => "Hungarian Forint",
                "ILS" => "Israeli New Shekel"
            ],
            "swish" => [
                "SEK" => "Swedish Krona"
            ],
            "tap" => [
                "AED" => "United Arab Emirates Dirham",
                "SAR" => "Saudi Riyal",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar",
                "OMR" => "Omani Rial",
                "QAR" => "Qatari Riyal",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "JOD" => "Jordanian Dinar",
                "EGP" => "Egyptian Pound"
            ],
            "thawani" => [
                "OMR" => "Omani Rial",
                "AED" => "United Arab Emirates Dirham",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "SAR" => "Saudi Riyal",
                "QAR" => "Qatari Riyal",
                "BHD" => "Bahraini Dinar",
                "KWD" => "Kuwaiti Dinar"
            ],
            "viva_wallet" => [
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "BGN" => "Bulgarian Lev",
                "RON" => "Romanian Leu",
                "PLN" => "Polish Zloty"
            ],
            "worldpay" => [
                "GBP" => "Pound Sterling",
                "USD" => "United States Dollar",
                "EUR" => "Euro",
                "JPY" => "Japanese Yen",
                "CAD" => "Canadian Dollar",
                "AUD" => "Australian Dollar",
                "NZD" => "New Zealand Dollar",
                "HKD" => "Hong Kong Dollar",
                "SGD" => "Singapore Dollar"
            ],
            "xendit" => [
                "IDR" => "Indonesian Rupiah",
                "PHP" => "Philippine Peso",
                "VND" => "Vietnamese Dong",
                "THB" => "Thai Baht",
                "MYR" => "Malaysian Ringgit",
                "SGD" => "Singapore Dollar",
                "USD" => "United States Dollar"
            ],
            "dlocal" => [
                "ARS" => "Argentine Peso",
                "BRL" => "Brazilian Real",
                "CLP" => "Chilean Peso",
                "COP" => "Colombian Peso",
                "MXN" => "Mexican Peso",
                "PEN" => "Peruvian Sol",
                "UYU" => "Uruguayan Peso",
                "EGP" => "Egyptian Pound",
                "INR" => "Indian Rupee",
                "NGN" => "Nigerian Naira",
                "ZAR" => "South African Rand"
            ],
            "mollie" => [
                "EUR" => "Euro",
                "GBP" => "Pound Sterling",
                "USD" => "United States Dollar",
                "CAD" => "Canadian Dollar",
                "AUD" => "Australian Dollar",
                "NZD" => "New Zealand Dollar",
                "CHF" => "Swiss Franc",
                "DKK" => "Danish Krone",
                "NOK" => "Norwegian Krone",
                "SEK" => "Swedish Krona",
                "PLN" => "Polish Zloty",
                "CZK" => "Czech Koruna",
                "HUF" => "Hungarian Forint"
            ]
        ];

        if ($key) {
            return array_key_exists($key,$paymentGateway) ?  $paymentGateway[$key] : [];
        }
        return $paymentGateway;
    }

}
