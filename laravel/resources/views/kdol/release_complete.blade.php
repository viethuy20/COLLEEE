@extends('layouts.exchange')

@section('layout.title', 'KDOLハートへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、KDOLハートに交換することができます。')
@php
$name = config('exchange.point.'.App\ExchangeRequest::KDOL_TYPE.'.label');
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

<section class="contents">
    <h1 class="ttl_exchange">認証連携解除完了</h1>

    <section class="contents__box">
        <div class="contents__center__box">
            <div class="contents__center__box__main">
                <h3>KDOLアカウントとの連携解除が完了しました。</h3>
            </div>
        </div>

    </section>
    <div class="btn_y">{!! Tag::link(route('exchanges.index'), '交換ページトップへ戻る') !!}</div>
</section>
@endsection
