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
<ul class="u-mb-15"><li class="u-mb-10 text--14">数億種類の品揃えの総合オンラインストアAmazon.co.jpにてお好きな商品をお買い求めいただけます。</li></ul>
<dl class="caution">
    <dt><span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
    <dd><ul class="trademark">
        <li class="text-link text--14 no-before">
            本キャンペーンはGMO NIKKO株式会社による提供です。<br />
            本キャンペーンについてのお問い合わせはAmazonではお受けしておりません。 <br />
            {{ Tag::link(route('inquiries.index', ['inquiry_id' => 4]), 'GMOポイ活問い合わせフォーム') }}までお願いいたします。
        </li>
        <li class="text--14 no-before">Amazon、Amazon.co.jpおよびそれらのロゴはAmazon.com, Inc.またはその関連会社の商標です。 </li>
    </ul></dd>
</dl>
@endcomponent
@endsection
