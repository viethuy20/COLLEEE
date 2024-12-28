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
    <li class="text--14">
        GMOポイ活から発行する{{ $exchange_info->label }}を、{{ $exchange_info->label }}チャージページからチャージすることで、PeXポイントへの交換が完了します。
        {{ $exchange_info->label }}は会員様のご登録メールアドレス宛にお送りさせていただきます。
    </li>
    @php
    $yen_map = $exchange_info->getYenLabelMap();
    @endphp
    <li class="text--14">発行される{{ $exchange_info->label }}コードは{{ head($yen_map) }}～{{ last($yen_map) }}ポイント単位のコードの組み合わせとなります。カートに入れた個数分のコードが発行されますのでご注意ください。</li>
</ul>
<p class="text-link text--14">
    {{ $exchange_info->label }}コードは、下記ページにてチャージが可能です。<br>
    {{ Tag::link(config('url.pex_charge'), $exchange_info->label.'チャージページ', ['target' => '_blank', 'class' => 'external']) }}<br>
    ※PeXへのログインが必要になります
</p>

<dl class="caution u-mt-20">
    <dt><span class="icon-attention"></span>&nbsp;PeXへのポイント交換にあたっての注意事項</dt>
    <dd><ul class="note">
        <li class="text--14">PeX会員でない方はPeXの無料会員登録が必要となります。</li>
        <li class="text-link text--14">{{ Tag::link(config('url.pex_entry'), 'PeXへの新規登録はこちら', ['target' => '_blank', 'class' => 'external']) }}</li>
        <li class="text-link text--14">{{ Tag::link('/support/?p=260', $exchange_info->label.'コードの使用手順はこちら', ['target' => '_blank']) }}</li>
    </ul></dd>
</dl>
@endcomponent
@endsection
