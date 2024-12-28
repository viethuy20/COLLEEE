@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.plane')

@section('layout.sidebar')
<section class="menu_exchange">
    <h2>ポイント交換先</h2>
    <ul>
    @php
        $exchange_info_map = \App\ExchangeInfo::getInfoMap();
        $exchange_map = [
            App\ExchangeRequest::BANK_TYPE => null,
            App\ExchangeRequest::AMAZON_GIFT_TYPE => null,
            App\ExchangeRequest::ITUNES_GIFT_TYPE => null,
            App\ExchangeRequest::NANACO_GIFT_TYPE => null,
            App\ExchangeRequest::PEX_GIFT_TYPE => null,
            App\ExchangeRequest::DOT_MONEY_POINT_TYPE => null,
            App\ExchangeRequest::EDY_GIFT_TYPE => null,
            App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE => [
                'tag' => '<span class="fontR">%s</span>',
            ],
            App\ExchangeRequest::WAON_GIFT_TYPE => null,
            App\ExchangeRequest::D_POINT_TYPE => null,
            // App\ExchangeRequest::LINE_PAY_TYPE => null,
            App\ExchangeRequest::PONTA_GIFT_TYPE => null,
            App\ExchangeRequest::PSSTICKET_GIFT_TYPE => null,

            App\ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE => null,
            App\ExchangeRequest::DIGITAL_GIFT_JALMILE_TYPE => null,

            App\ExchangeRequest::PAYPAY_TYPE => null,

            App\ExchangeRequest::KDOL_TYPE => null,

        ];
        @endphp
        @foreach ($exchange_map as $type => $data)
        @continue(!isset($exchange_info_map[$type]))
        @php
        $exchange_info = $exchange_info_map[$type];
        $tag = isset($data['tag']) ? sprintf($data['tag'], e($exchange_info->label)) : e($exchange_info->label);
        @endphp
        <li>{{ Tag::link($exchange_info->url, $tag, null, null, false) }}</li>
        @endforeach
    </ul>
</section><!--/menu_exchange-->
@endsection
