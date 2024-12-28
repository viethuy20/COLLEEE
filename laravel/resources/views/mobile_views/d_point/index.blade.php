@php
$base_css_type = 'exchange';
$name = config('exchange.point.'.App\ExchangeRequest::D_POINT_TYPE.'.label');
@endphp
@extends('layouts.default')

@section('layout.title', 'dポイントへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、dポイントに交換することができます。')
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
            {{ $name }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
<h1 class="ttl_exchange">{{ $name }}へのポイント交換</h1>

@include('elements.d_point_individual')

<h1 class="ttl_exchange">{{ $name }}への交換申請</h1>
<section class="major_bank">
    <section class="contents__box u-mt-20">
        <p class="img-dpoint">{!! Tag::image('images/img-dpoint.png', $name.'は、さまざまなポイントへ交換が可能です') !!}</p>
        @if (Session::has('message'))
        <p class="error"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
        @endif
        @php
        $user = Auth::user();
        $min_point = config('exchange.point.'.App\ExchangeRequest::DOT_MONEY_POINT_TYPE.'.min');
        @endphp

        @if ($user->max_exchange_point < $min_point)
        <p class="error"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p>
        @else
            @if (empty($logout_url))
            <p class="u-mt-20">{!! Tag::link(route('d_point.oauth'), 'dポイントに交換する', ['class' => 'banks__auth__btn__pink']) !!}</p>
            @else
            <p class="u-mt-20">{!! Tag::link($logout_url, 'Dアカウントからログアウトする', ['class' => 'banks__auth__btn__pink', 'target' => '_blank']) !!}</p>
            @endif
        @endif
    </section><!--/detailinput-->
</section>

<section><div class="btn_y">{!! Tag::link(route('exchanges.index'), '戻る') !!}</div></section>
@endsection
