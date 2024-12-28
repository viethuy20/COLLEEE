<?php $base_css_type = 'exchange'; ?>
@extends('layouts.default')

@section('layout.title', 'ドットマネーへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、dポイントに交換することができます。')
@php
$name = config('exchange.point.'.App\ExchangeRequest::D_POINT_TYPE.'.label');
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
                @if (!$exist_flag)
                    <h3>dアカウントとの紐付けが完了しました。</h3>
                    <p>dポイントクラブ会員番号： {{ $d_pt_number }}</p>
                @else
                    <h3>dアカウントとの紐付けが既に登録済みです。</h3>
                    <p>dポイントクラブ会員番号： {{ $d_pt_number }}</p>
                @endif
            </div>
            <div class="btn_y">{{ Tag::link(route('d_point.exchange', ['number' => $d_pt_number]), '交換ページへ') }}</div>
        </div>
        <div class="btn_y">{!! Tag::link(route('exchanges.index'), '交換ページトップへ戻る') !!}</div>
    </section>
@endsection
