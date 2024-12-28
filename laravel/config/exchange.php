<?php
return [
    'yen_rate' => 100,
    'yen_max' => 50000,
    'max' => 50000,
    'point' => [
        App\ExchangeRequest::BANK_TYPE => [
            'config' => 'payment_gateway',
            'label' => '金融機関振込',
            'unit' => '円',
            'exchange_at' => '即日～（金融機関によって異なります）',
            'account' => '口座番号',
            'yen' => ['min' => 500, 'rate' => 100, 'step' => 50,],
        ],
        App\ExchangeRequest::AMAZON_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'Amazonギフトカード',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::ITUNES_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'Apple Gift Card',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::PEX_GIFT_TYPE => [
            'config' => 'voyage',
            'label' => 'PeXポイントギフト',
            'unit' => 'PeXポイント',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 1000, 'list' => [1000, 3000, 5000, 10000, 20000, 50000],],
        ],
        App\ExchangeRequest::DOT_MONEY_POINT_TYPE => [
            'config' => 'dot_money',
            'label' => 'ドットマネー',
            'unit' => 'マネー',
            'exchange_at' => 'リアルタイム',
            'account' => 'ドットマネー口座番号',
            'yen' => ['min' => 300, 'rate' => 100, 'step' => 50,],
        ],
        App\ExchangeRequest::NANACO_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'nanacoギフト',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::EDY_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'EdyギフトID',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'Google Play ギフトコード',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::WAON_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'WAONポイントID',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::D_POINT_TYPE => [
            'config' => 'd_point',
            'label' => 'dポイント',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'step' => 50,],
        ],
        App\ExchangeRequest::LINE_PAY_TYPE => [
            'config' => 'line_pay',
            'label' => 'LINE Pay',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 1050, 'rate' => 1000, 'step' => 100,],
        ],
        App\ExchangeRequest::PONTA_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'Pontaポイント コード',
            'unit' => 'ポイント',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],
        App\ExchangeRequest::PSSTICKET_GIFT_TYPE => [
            'config' => 'ntt_card',
            'label' => 'プレイステーション ストアチケット',
            'unit' => '円分',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 1100, 'rate' => 100, 'list' => [1100, 3000, 5000, 10000,],],
        ],



        App\ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE => [
            'config' => 'digital_gift',
            'label' => 'PayPal',
            'unit' => '円',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],

        App\ExchangeRequest::DIGITAL_GIFT_JALMILE_TYPE => [
            'config' => 'digital_gift',
            'label' => 'JALマイレージバンク',
            'unit' => '円',
            'exchange_at' => '交換申請日の翌月中旬頃',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],

        App\ExchangeRequest::PAYPAY_TYPE => [
            'config' => 'paypay',
            'label' => 'PayPayポイント',
            'unit' => 'ポイント',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 300, 'rate' => 100, 'list' => [100, 500, 1000,],],
        ],



        App\ExchangeRequest::KDOL_TYPE => [
            'config' => 'kdol',
            'label' => 'KDOL',
            'unit' => 'ハート',
            'exchange_at' => 'リアルタイム',
            'yen' => ['min' => 100, 'rate' => 10000, 'list' => [10000, 50000, 100000,],],
        ],

    ],
];
