@extends('layouts.exchange')

@section('layout.title', 'dポイントへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、dポイントに交換することができます。')

@section('layout.head')
    <script type="text/javascript"><!--
        $(function () {
            lockForm('ExchangeForm');
        });
        //-->
    </script>
@endsection
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
    <section class="contents">
        <h1 class="ttl_exchange">OAuth認証連携確認</h1>

        <section class="contents__box">
            Dアカウントと紐づけをしますか？
            {{ Tag::formOpen(['url' => route('d_point.oauth_complete'), 'id' => 'ExchangeForm', 'class' => 'banks__form']) }}
            @csrf
            {{ Tag::formHidden('type', $exchange_type) }}
            {{ Tag::formHidden('user_id', $user_id) }}
            {{ Tag::formHidden('sub', $sub) }}
            {{ Tag::formHidden('d_pt_number', $d_pt_number) }}
            {{ Tag::formSubmit('OK', ['class' => 'banks__auth__btn']) }}
            {{ Tag::formClose() }}
        </section><!--/detailinput-->
        <div class="btn_y">{{ Tag::link(route('d_point.index'), '戻る') }}</div>
    </section><!--/contents-->
@endsection
