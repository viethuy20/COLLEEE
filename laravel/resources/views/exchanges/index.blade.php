@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.plane')
{!! Tag::style('/css/common_20240613.css') !!}
@section('layout.title', 'ポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$position = 1;
$application_json = '';
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('exchanges.index');
$application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ポイント交換", "item": "' . $link . '"}';

@endphp
@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>    
        @endforeach
        <li>
            ポイント交換
        </li>
    </ol>
</section>
@endsection
<?php $user = Auth::user(); ?>

@section('layout.content')
<section class="contents__wrap">
    <div class="contents">
        <h3 class="">{{ Tag::image('/images/exchanges_ttl.png', 'ポイント交換') }}</h3>

        <!-- ポイント交換状況 -->
        <h2 class="contents__ttl">ポイント交換状況</h2>
        <div class="contents__box">
            <dl class="exchanges__status__list">
                <dt>申込中のポイント：</dt>
                <dd><span class="red">{{ number_format($user->exchanging_point) }}</span>ポイント</dd>
                <dt>交換済みのポイント：</dt>
                <dd><span>{{ number_format($user->exchanged_point) }}</span>ポイント</dd>
            </dl>
            <div class="u-text-right u-mt-20">
                <p class="exchanges__poss__attention__ttl u-mt-small">※2022年12月1日よりポイントレートを1ポイント＝1円相当に変更しました。</p>
            </div>
            <div class="exchanges__status__btn">{{ Tag::link(route('users.exchange_list'), '交換履歴') }}</div>
        </div>

        <!-- 交換先を選択 -->
        <h2 class="contents__ttl">交換先を選択<span class="exchanges__select__meta">ポイント交換の手数料については{{ Tag::link('/support/?p=258', 'こちら', ['class' => 'tohelp', 'target' => '_blank']) }}をご覧ください。</span></h2>
        @php
        $voyage_point = App\ExchangeRequest::ofEnable()
            ->ofVoyageGiftCode()
            ->where('requested_at', '>=', Carbon\Carbon::today())
            ->sum('point');
        @endphp
        @if ($voyage_point >= 4000000)
        <p class="attention">
            現在、ギフトコードの交換手続きが混みあっている為、遅延が発生しております。<br />
            ご迷惑をおかけいたしますが、ギフトコード発送に数日のお時間をいただく場合がございますので何卒ご容赦ください。
        </p>
        @endif
        <ul class="exchanges__select__tab">
            <li class="active"><a href="#tab_all">すべて</a></li>
            <li><a href="#tab_bank">銀行</a></li>
            <li><a href="#tab_e-money">電子マネー</a></li>
            <li><a href="#tab_gift">ギフト券</a></li>
            <li><a href="#tab_other">他社ポイント</a></li>
        </ul>

        @php
        $exchange_info_map = \App\ExchangeInfo::getInfoMap();
        $tab_map = ['tab_all', 'tab_bank', 'tab_e-money', 'tab_gift', 'tab_other'];
        $exchange_map = [
            App\ExchangeRequest::BANK_TYPE => [
                'img' => 'icon_cash.png',
                'id'  => 'tab_bank',
            ],
            App\ExchangeRequest::AMAZON_GIFT_TYPE => [
                'img' => 'icon_amazon.png',
                'id'  => 'tab_gift',
                'note_list' => [
                    '本キャンペーンはGMO NIKKO株式会社による提供です。<br />'.
                    '本キャンペーンについてのお問い合わせはAmazonではお受けしておりません。<br />'.
                    Tag::link(route('inquiries.index', ['inquiry_id' => 4]), 'GMOポイ活問い合わせフォーム').'までお願いいたします。',
                    'Amazon、Amazon.co.jpおよびそれらのロゴはAmazon.com, Inc.またはその関連会社の商標です。',
                ],
            ],
            App\ExchangeRequest::ITUNES_GIFT_TYPE => [
                'img' => 'icon_itunes.png',
                'id'  => 'tab_gift',
                'note_list' => ['&copy; '.\Carbon\Carbon::now()->year.' iTunes K.K. All rights reserved.',],
            ],
            App\ExchangeRequest::NANACO_GIFT_TYPE => [
                'img' => 'icon_nanaco.png',
                'id'  => 'tab_e-money',
                'note_list' => [
                    '「nanaco(ナナコ)」と「nanacoギフト」は株式会社セブン・カードサービスの登録商標です。',
                    '「nanacoギフト」は、株式会社セブン・カードサービスとの発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトサービスです。',
                ],
            ],
            App\ExchangeRequest::PEX_GIFT_TYPE => [
                'img' => 'icon_pex.png',
                'id'  => 'tab_other',
            ],
            App\ExchangeRequest::DOT_MONEY_POINT_TYPE => [
                'img' => 'icon_money.png',
                'id'  => 'tab_other',
            ],
            App\ExchangeRequest::PAYPAY_TYPE => [
                'img' => 'img_paypay.png',
                'id'  => 'tab_e-money',
                'note_list' => [
                ],
            ],
            App\ExchangeRequest::EDY_GIFT_TYPE => [
                'img' => 'icon_edy.png',
                'id'  => 'tab_e-money',
                'note_list' => [
                    '「EdyギフトID」は、楽天Edy株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトサービスです。',
                    '「楽天Edy（ラクテンエディ）」は、楽天グループのプリペイド型電子マネーです。',
                    '「おサイフケータイ」は株式会社NTTドコモの登録商標です。',
                ],
            ],
            App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE => [
                'img' => 'icon_google.png',
                'id'  => 'tab_gift',
                'note_list' => [
                    '<span class="fontR">Google Play</span> <span class="fontNSJP">は</span> <span class="fontR">Google LLC</span> <span class="fontNSJP">の商標です。</span>',
                ],
            ],
            App\ExchangeRequest::WAON_GIFT_TYPE => [
                'img' => 'icon_waon.png',
                'id'  => 'tab_e-money',
                'note_list' => [
                    '「WAONポイントID」は、イオンリテール株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトです。',
                    '「WAON（ワオン）」は、イオン株式会社の登録商標です。',
                ],
            ],
            App\ExchangeRequest::D_POINT_TYPE => [
                'img' => 'icon_dpoint.png',
                'id'  => 'tab_e-money',
                'note_list' => [
                    '「dポイント」は、ドコモ株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトです。',
                    '「dポイント」は、ドコモ株式会社の登録商標です。',
                ],
            ],
            App\ExchangeRequest::DIGITAL_GIFT_JALMILE_TYPE => [
                'img' => 'icon_jal.png',
                'id'  => 'tab_other',
            ],
            App\ExchangeRequest::DIGITAL_GIFT_PAYPAL_TYPE => [
                'img' => 'icon_paypal.png',
                'id'  => 'tab_other',
                'note_list' => [
                    'デジタルギフト（R）は、株式会社デジタルプラスの商標です。'
                ],
            ],
            // App\ExchangeRequest::LINE_PAY_TYPE => [
            //     'img' => 'icon_line_pay.png',
            //     'id'  => 'tab_e-money',
            //     'note_list' => [
            //         '「LINE Pay」は、LINE Pay株式会社との発行許諾契約により、LINE Pay株式会社が発行する電子マネーギフトです。',
            //         '「LINE Pay」は、LINE Pay株式会社の登録商標です。',
            //     ],
            // ],
            App\ExchangeRequest::PONTA_GIFT_TYPE => [
                'img' => 'icon_ponta.png',
                'id'  => 'tab_gift',
                'note_list' => [
                    '「Ponta」は、株式会社ロイヤリティ マーケティングの登録商標です。',
                    '「Pontaポイント コード」は、株式会社ロイヤリティ マーケティングとの発行許諾契約により、株式会社NTTカードソリューションが発行するサービスです。',
                ],
            ],
            App\ExchangeRequest::PSSTICKET_GIFT_TYPE => [
                'img' => 'icon_pssticket.png',
                'id'  => 'tab_gift',
                'note_list' => [
                    '「プレイステーション ファミリーマーク」、「PlayStation」および「プレイステーション」は株式会社ソニー・インタラクティブエンタテインメントの登録商標または商標です。',
                ],
            ],

            App\ExchangeRequest::KDOL_TYPE => [
                'img' => 'img_kdol_list.png',
                'id'  => 'tab_other',
                'note_list' => [
                ],
            ],
        ];
        $note_index = 0;
        $note_map = [];
        $note_data = [];
        @endphp


        @foreach ($tab_map as $tab_type)
        @if ($tab_type == 'tab_all')
        <div class="exchanges__select active" id="{{ $tab_type }}">
        @else
        <div class="exchanges__select" id="{{ $tab_type }}">
        @endif
            <ul class="exchanges__select__list">
            @foreach ($exchange_map as $type => $data)
            @continue (!isset($exchange_info_map[$type]) OR ($tab_type != 'tab_all' AND $tab_type != $data['id']))
                @php
                $exchange_info = $exchange_info_map[$type];
                @endphp
                <li>
                    <a href="{{ $exchange_info->url }}">
                        <div class="exchanges__select__list__img" style="text-align: center;">
                            {{ Tag::image('/images/'.$data['img'], $exchange_info->label) }}
                        </div>
                        <p class="exchanges__select__list__ttl">
                            @if ($exchange_info->status != \App\ExchangeInfo::SUCCESS_STATUS)
                            <span class="icon-attention"></span>
                            @endif
                            {{ $exchange_info->label }}
                        @if ($tab_type == 'tab_all' AND isset($data['note_list']))
                            @foreach ($data['note_list'] as $note)
                                @php
                                    ++$note_index;
                                    $note_data[$type][$note_index] = $note;
                                @endphp
                            @endforeach
                        @endif

                        </p>

                        <dl class="exchanges__select__list__chart">
                            <dt>最低交換P</dt>
                            <dd>{{ number_format($exchange_info->min_point) }}ポイント〜</dd>
                            <dt>交換日</dt>
                            <dd>{{ $exchange_info->exchange_at }}</dd>
                            <dt>交換レート</dt>
                            <!-- LINE Payのみ手数料がかかるので交換レートの表記方法が異なる -->
                            @if ($type == App\ExchangeRequest::LINE_PAY_TYPE)
                            <dd>{{ number_format($exchange_info->chargePoint(1050)) }}ポイント→{{ number_format($exchange_info->exchangeAmount(100)).$exchange_info->unit }}</dd>
                            @else
                            <dd>{{ number_format($exchange_info->chargePoint(100)) }}ポイント→{{ number_format($exchange_info->exchangeAmount(100)).$exchange_info->unit }}</dd>
                            @endif
                        </dl>
                    </a>
                </li>
            @endforeach
            </ul>
        </div>
        @endforeach

    </div>
</section>
@endsection
@section('layout.footer_notes')
@php
    $footNotes = 'exchange';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection
<!-- jQuery -->
{!! Tag::script('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', ['type' => 'text/javascript']) !!}

<!-- MyStyle -->
{!! Tag::script('/js/exchanges.js', ['type' => 'text/javascript']) !!}

