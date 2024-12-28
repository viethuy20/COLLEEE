@php
$name = config('exchange.point.'.App\ExchangeRequest::KDOL_TYPE.'.label');
@endphp
@extends('layouts.exchange')

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
            {{ $name }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

<section class="contents">
    <h1 class="ttl_exchange">{{ $name }}のハートへのポイント交換</h1>

    @include('elements.kdol_individual')

    <h1 class="ttl_exchange u-mt-20 u-mb-10">{{ $name }}のハートへの交換申請</h1>
    <section class="contents__box u-mt-remove">
        <section class="detailinput_area">
            <p class="img-dpoint">{!! Tag::image('images/kdol_600x340.png', $name.'は、さまざまなポイントへ交換が可能です') !!}</p>
            @if (Session::has('message'))
            <p class="error_message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
            @endif
            @php
            $user = Auth::user();
            $min_point = config('exchange.point.'.App\ExchangeRequest::KDOL_TYPE.'.min');
            @endphp
            @if ($user->max_exchange_point < $min_point)
            <p class="error_message"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p>
            @else
            @if ($kdol_user)
            <p>{!! Tag::link(route('kdol.exchange'), 'KDOLハートに交換する', ['class' => 'banks__auth__btn']) !!}</p>
            @else
            <p>{!! Tag::link('#', 'KDOLハートに交換する', ['id'=>'oauth','class' => 'banks__auth__btn']) !!}</p>
            @endif
            @endif
            @if ($kdol_user)
            <p class="u-mt-20">{!! Tag::link('#', 'KDOLと連携を解除する', ['id'=> 'release','class' => 'banks__auth__btn']) !!}</p>
            @endif
            <p class="error_message" id="wait" style="display: none;"><span class="icon-attention"></span>処理中です。お待ちください。</p>
        </section><!--/detailinput_area-->
    </section><!--/detailinput-->
    {{ Tag::formHidden('session_key', $session_key, ['id'=>'session_key']) }}
    {{ Tag::formHidden('user_key', $user_key, ['id'=>'user_key']) }}
    <section><div class="btn_y">{!! Tag::link(route('exchanges.index'), '戻る') !!}</div></section>
</section><!--/contents-->
<script src="{{ asset('/js/kdol.js') }}"></script>
@endsection