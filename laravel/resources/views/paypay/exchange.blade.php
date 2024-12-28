@extends('layouts.exchange')

@section('layout.title', 'PayPayポイントへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、PayPayポイントに交換することができます。')
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
@component('elements.gift_code_form', ['point_confirm_route'=>$point_confirm_route,'exchange_info' => $exchange_info, 'exchange' => $exchange])
<ul class="mb_15"><li class="mb_10">PayPayは、スマホで利用できるQR決済サービスです。<br>アプリをダウンロード・登録するだけで誰でも無料で使えます。<br>
    PayPayポイントは、1ポイント＝1円として全国のPayPayが使えるお店で利用することができます。</li></ul>
<dl class="caution u-mt-20">
    <dt>
        <span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項
    </dt>
    <dd>
        <ul class="trademark">
            <li class="text--14">1ポイント=1円分としてPayPayの加盟店でご利用いただけます。</li>
            <li class="text--14">PayPayポイントは出金や譲渡はできません。</li>
            <li class="text--14">PayPay公式ストアでも利用可能です。</li>
            <li class="text--14">交換申請後の変更やキャンセルはお受けできません。</li>
            <li class="text--14">詳細はこちらから<p class="ta_c text-link text--14 u-text-ac">{{ Tag::link(config('paypay.help_url'), config('paypay.help_url'), ['target' => '_blank', 'class' => 'icon-arrowr external']) }}</p></li>
            <li class="text-link text--14">
                本キャンペーンはGMO NIKKO株式会社による提供です。<br />
                本キャンペーンについてのお問い合わせはPayPayではお受けしておりません。 <br />
                {{ Tag::link(route('inquiries.index', ['inquiry_id' => 4]), 'GMOポイ活問い合わせフォーム') }}までお願いいたします。
            </li>
            <li class="text--14"> PayPay,PayPay.co.jpおよびそれらのロゴはPayPay, Inc.またはその関連会社の商標です。 </li>
        </ul>
    </dd>
</dl>
@endcomponent
@endsection
