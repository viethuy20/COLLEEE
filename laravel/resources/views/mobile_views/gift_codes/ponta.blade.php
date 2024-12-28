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
    <li class="u-mb-10 text--14 text-link">Pontaは、いろんなお店でポイントがたまる・つかえる共通ポイントサービスです。</li>
    <br />
    <li class="u-mb-10 text--14 text-link">Pontaポイント コードは、Ponta会員IDにPontaポイントを加算できるサービスです。</li>
    <li class="u-mb-10 text--14 text-link">Pontaポイント コードの詳細は{{ Tag::link(config('url.ponta_about'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。</li>
</ul>
<dl class="caution">
    <dt>
        <span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項
    </dt>
    <dd class="text--14 text-link">
    ・Pontaポイントの加算登録には、PontaWeb会員登録が必要です。<br />
    ・PontaWeb会員登録の際には、Ponta会員IDとリクルートIDのご登録が必要です。<br />
    ・Pontaポイント コードには有効期限があります。有効期限を過ぎるとご利用できなくなります。<br />
    ・Pontaポイント コードの登録キャンセル・換金・返金・再発行等はできません。<br />
    ・Pontaポイント コードの紛失、盗難、破損、IDの漏洩等の責任は負いません。<br />
    ・Pontaポイント コードの登録サイトは、日本国外からのアクセスはできません。<br />
    ・その他注意事項は「{{ Tag::link(config('url.ponta_rule'), '利用規約', ['target' => '_blank', 'class' => 'external']) }}」をご確認ください。<br />
    <br />
    「Ponta」は、株式会社ロイヤリティ マーケティングの登録商標です。<br />
    「Pontaポイント コード」は、株式会社ロイヤリティ マーケティングとの発行許諾契約により、株式会社NTTカードソリューションが発行するサービスです。<br />
    </dd>
</dl>
@endcomponent
@endsection
