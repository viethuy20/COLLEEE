@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', 'KDOLハートへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、KDOLハートに交換することができます。')
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
<ul class="u-mb-15"><li class="u-mb-10 text--14">KDOLはグローバルKーPOPのファン投票サービスです。
    <br>アプリをダウンロードして登録するだけで、誰でも無料で利用できます。<br>
    ハートを貯めて応援するアイドルの広告をプレゼントすることができます。</li></ul>
<dl class="caution">
    <dt><span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}のハートへのポイント交換にあたっての注意事項</dt>
    <dd><ul class="trademark">
        <li class="text--14">GMOポイ活ポイント1ポイントあたり100ハートに交換されます。</li>
        <li class="text--14">交換されたハートはKDOLサービス内でのみ利用可能で、交換申請後の変更やキャンセルはできません。</li>
        <li class="text-link text--14 no-before">
            本キャンペーンはGMO NIKKO株式会社による提供です。<br />
            本キャンペーンについてのお問い合わせはKDOLではお受けしておりません。 <br />
            {{ Tag::link(route('inquiries.index', ['inquiry_id' => 4]), 'GMOポイ活問い合わせフォーム') }}までお願いいたします。
        </li>
        <li class="text--14 no-before">KDOL,kdol.meおよびそれらのロゴはKDOL.Inc.またはその関連会社の商標です。 </li>
    </ul></dd>
</dl>
@endcomponent
@endsection
