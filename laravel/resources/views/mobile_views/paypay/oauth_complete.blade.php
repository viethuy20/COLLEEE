<?php $base_css_type = 'exchange'; ?>
@extends('layouts.default')

@section('layout.title', 'PayPayポイントへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、PayPayポイントに交換することができます。')
@php
$name = config('exchange.point.'.App\ExchangeRequest::PAYPAY_TYPE.'.label');
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
            {{ $name }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
    <h1 class="ttl_exchange">交換申請完了</h1>

    <section class="selectedbank">
        <div class="contents__center__box">
            <div class="contents__center__box__main">
                
                    <h3>PayPayアカウントとの紐付けが完了しました。</h3>
                    <p>PayPayポイント会員番号： {{ $profileIdentifier }}</p>
               
            </div>
            <div class="btn_y">{{ Tag::link(route('paypay.exchange', ['number' => $paypay_number]), '交換ページへ') }}</div>
        </div>
        <div class="btn_y">{!! Tag::link(route('exchanges.index'), '交換ページトップへ戻る') !!}</div>
    </section>
@endsection
