<?php
return [
   'media' => [
        '1' => [
            'name' => 'GMOリサーチ株式会社',
            'contact_url' => env('GMO_RESEARCH_INQUIRY_HOST') . '/jyonp/inquiry/inquiryInit.do?inqkey=314733c50048b68ad0c173263145de59344eab28376b8213&crypt={DYNAMIC_VALUE}',
            'api_host' => env('GMO_RESEARCH_API_HOST')
        ],
        '2' => [
            'name' => '株式会社セレス',
            'contact_url' => '/inquiries/10' // セレスの媒体には問い合わせ先がないためポイ活側で用意した問い合わせ先を設定
        ],
        '120' => [
            'name' => '株式会社アイブリッジ',
            'contact_url' => '/inquiries/10' // アイブリッジの媒体には問い合わせ先がないためポイ活側で用意した問い合わせ先を設定
        ]
    ]
];

