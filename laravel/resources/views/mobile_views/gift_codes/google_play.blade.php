@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', $exchange_info->label.'へのポイント交換 | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、' . $exchange_info->label . 'に交換することができます。')
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
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
            {{ Tag::link(route('exchanges.index'), 'ポイント交換') }}
        </li>
        <li>
            {{ $exchange_info->label }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
@component('elements.gift_code_form', ['exchange_info' => $exchange_info, 'exchange' => $exchange])
<ul class="u-mb-15">
    <li class="u-mb-10 text--14">
        ■ <span class="fontR">Google Play</span> <span class="fontNSJP">ギフトコード</span>とは<br>
        アプリやゲームをはじめ数百万のアイテムが揃った <span class="fontR">Google Play</span> なら欲しいものが必ず見つかります。<br>
        <span class="fontR">Google Play</span> <span class="fontNSJP">ギフトコード</span>を手に、無限に楽しめる世界を探検しましょう。<br>
        人気のゲームや毎日の生活に欠かせないアプリを、手数料なし、有効期限なし、クレジット カードなしで気軽に手に入れることができ、大切な人へのギフトとして最適です。<br>
        もちろん、自分へのご褒美としても。<br>
        <br>
        日本の <span class="fontR">Google Play</span> <span class="fontNSJP">ストア</span>でのみ使用できます。<br>
        利用規約が適用されます。<br>
        <span class="fontR">Google Play</span> <span class="fontNSJP">ギフトコード</span>は、Android&#8482; の公式アプリストアである Google Play ストアで、アプリやゲームなどの購入に使用できます。<br>
    </li>
    <br>
    <li class="text--14">
        ■  <span class="fontR">Google Play</span> <span class="fontNSJP">ギフトコード</span>の利用規約<br>
        このギフトコードは グーグル・ペイメント合同会社（「GPJ」）が発行するものです。<br>
        利用規約およびプライバシーポリシーは play.google.com/jp-card-terms をご覧ください。<br>
        13歳以上の日本の居住者にのみ有効です。<br>
        ご利用には Google&#8482; ペイメントのアカウントとインターネットアクセスが必要です。<br>
        利用するには、Play ストア アプリまたは play.google.com でコードを入力してください。<br>
        このギフトコードのコードは、<span class="fontR">Google Play</span> および YouTube&#8482;<span class="fontR"> でのみご利用いただけます。<br>
        コードに関するその他の要求はすべて詐欺の可能性があります。<br>
        詳しくは、play.google.com/giftcardscam をご覧ください。<br>
        デバイス、定期購読のご購入には使用できないことがあります。<br>
        その他の制限が適用される場合があります。<br>
        手数料や使用期限はありません。<br>
        法律上必要な場合を除き返金や他のカードとの交換はできません。<br>
        クレジットアカウントにはチャージできません。<br>
        カードの金額を補充または返金することはできません。<br>
        <span class="fontR">Google Play</span> 以外のアカウントにチャージすることはできません。<br>
        転売、交換、譲渡することはできません。<br>
        カードの紛失盗難等についてはお客様の責任となります。<br>
        残高確認等のお問い合わせは、 support.google.com/googleplay/go/cardhelp、Google Inc.,1600 Amphitheatre Parkway, Mountain View, CA 94043までお願いします。 <br>
        Google、Android、Google Play、YouTube は Google LLC の商標です。<br>
    </li>
</ul>
@endcomponent
@endsection
