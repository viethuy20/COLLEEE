@php
$base_css_type = 'exchange';
$name = config('exchange.point.'.App\ExchangeRequest::DOT_MONEY_POINT_TYPE.'.label');
@endphp
@extends('layouts.default')

@section('layout.title', 'ドットマネーへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、ドットマネーに交換することができます。')
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

@include('elements.dot_money_individual')

<h1 class="ttl_exchange">{{ $name }}への交換申請</h1>
<section class="major_bank">
    <section class="contents__box">
        <p class="img-dotmoney">{!! Tag::image('images/img-dotmoney.png', $name.'は、さまざまなポイントへ交換が可能です') !!}</p>
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
        <p class="u-mt-20">{!! Tag::link(route('dot_money.oauth'), 'ドットマネーに交換する', ['class' => 'banks__auth__btn__pink']) !!}</p>
        @endif
    </section><!--/detailinput-->
</section>

<section><div class="btn_y">{!! Tag::link(route('exchanges.index'), '戻る') !!}</div></section>
@endsection
