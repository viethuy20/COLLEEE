<?php $base_css_type = 'exchange'; ?>
@extends('layouts.default')

@section('layout.title', '金融機関振込｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金に交換されて指定の銀行口座に振り込まれます。')
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
            金融機関振込
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
<section class="contents__wrap">
    <h1 class="ttl_exchange">振込口座選択</h1>

    <section class="major_bank">
        <p class="mb_10">振込口座を選択して下さい</p>
        <ul class="select_account mb_15">
            <li>{!! Tag::link(route('banks.select_account'), '前回と同じ振込口座', null, null, false) !!}</li>
            <li>{!! Tag::link(route('banks.bank_list'), '新たな振込口座', null, null, false) !!}</li>
        </ul>
        <p class="text-18 red u-font-bold">新たな振込口座を選択した場合、前回登録した情報は削除されます。</p>
    </section><!--/selectedbank-->
</section>
@endsection
