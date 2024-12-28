@php
$name = config('exchange.point.'.App\ExchangeRequest::PAYPAY_TYPE.'.label');
@endphp
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
            {{ $name }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

<section class="contents">
    <h1 class="ttl_exchange">{{ $name }}へのポイント交換</h1>

    @include('elements.paypay_individual')

    <h1 class="ttl_exchange u-mt-20 u-mb-10">{{ $name }}への交換申請</h1>
    <section class="contents__box u-mt-remove">
        <section class="detailinput_area">
            <p>{!! Tag::image('images/img_paypay.png', $name.'は、さまざまなポイントへ交換が可能です') !!}</p>
            @if (Session::has('message'))
            <p class="error_message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
            @endif
            @php
            $user = Auth::user();
            $min_point = config('exchange.point.'.App\ExchangeRequest::PAYPAY_TYPE.'.min');
            @endphp
            @if ($user->max_exchange_point < $min_point)
            <p class="error_message"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p>
            @else
            <p>{!! Tag::link(route('paypay.oauth'), 'paypayポイントに交換する', ['class' => 'banks__auth__btn']) !!}</p>
            @endif
        </section><!--/detailinput_area-->
    </section><!--/detailinput-->

    <section><div class="btn_y">{!! Tag::link(route('exchanges.index'), '戻る') !!}</div></section>
</section><!--/contents-->
@endsection
