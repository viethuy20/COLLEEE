@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', 'PayPalへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、PayPalのポイントに交換することができます。')
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
@component('elements.digital_gift_paypal_form', ['exchange_info' => $exchange_info, 'exchange' => $exchange])
<ul class="u-mb-15"><li class="u-mb-10 text--14">
    PayPalならカードがなくても大丈夫。</br>
    デビット・クレジットカードに加え、銀行口座からも支払えるのでオンラインショッピングをもっと楽しめます。さらに、カードのポイントはそのまま貯まるなど他にもメリットがたくさんあります。
    </li></ul>
<dl class="caution">
    <dt><span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
    <dd><ul class="note">
        <li class="text--14">PayPalへの交換は、会員登録不要のかんたんポイント交換サービス「デジタルギフト®」にて行います。</li>
            <li class="text--14">GMOポイ活で交換の申請をすると、ご登録のメールアドレス宛てにメールが届きます。メールの記載のURLより「デジタルギフト®」に遷移し、交換手続きを行ってください。</li>
            <li class="text--14">PayPalに交換の際は、PayPalアカウントの連携が必要となります。</li>
            <li class="text--14">PayPalアカウントに制限がかかっている等の理由により、送金を受け取れない場合があります。</li>
            <li class="text-link text--14">その他PayPalについての詳細は、「デジタルギフト®」のPayPal交換ページや{{ Tag::link(config('url.paypal'), 'PayPal公式サイト', ['target' => '_blank', 'class' => 'external']) }}をご参照ください。</li>
    </ul></dd>
</dl>
@endcomponent
@endsection

