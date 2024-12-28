@extends('layouts.exchange')

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

<section class="contents">
    <h1 class="ttl_exchange">OAuth認証連携完了</h1>

    <section class="contents__box">
        <div class="contents__center__box">
            <div class="contents__center__box__main">
                <h3>paypayアカウントとの紐付けが完了しました。</h3>
                <p>paypayアカウント： {{ $profileIdentifier }}</p>
            </div>
        </div>

        <div class="btn_y">{{ Tag::link(route('paypay.exchange', ['number' => $paypay_number]), '交換ページへ') }}</div>

    </section>
    <div class="btn_y">{!! Tag::link(route('exchanges.index'), '交換ページトップへ戻る') !!}</div>
</section>
@endsection
