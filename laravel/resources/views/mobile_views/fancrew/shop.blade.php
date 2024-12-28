<?php
$shop_attributes = $shop->attributes();
$shop_name = $shop_attributes->name;
$rate = $monitor->Rate;
$map_url = \App\External\Google::getMapUrl($shop_attributes->latitude ?? null, $shop_attributes->longitude ?? null, $shop_attributes->address ?? null);
?>
@extends('layouts.fancrew')

@section('layout.title', $shop_name.'がモニター体験でお得になる！'.' | いつもの生活がちょっとお得になるGMOポイ活')
@section('layout.keywords', 'モニター,覆面調査')
@section('layout.description', 'お店・商品の紹介です。'.$shop_attributes->description)
@section('og_type', 'website')
@section('fancrew.title', $shop_name)
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "モニター（お店でお得）", "item": "' . $link . '"},';
$position++;
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "'.$shop_name.'", "item": "' . $link . '"},';

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
            {{ Tag::link(route('fancrew.pages'), 'モニター（お店でお得）') }}
        </li>
        <li>
            {{ $shop_name }}
        </li>
    </ol>
</section>
@endsection
@section('fancrew.content')
<section class="monitor_detail">
    <div class=" contentsbox">
        <div class="img_service">{!! Tag::image($shop_attributes->topImageUrl, $shop_name) !!}</div>
        @if (isset($rate))
        <?php $rate_attributes = $rate->attributes(); ?>
        <p class="point">
            <span class="icon-point"></span>
            @if ($rate_attributes->type == '固定')
            {{ number_format($rate_attributes->value * 10) }}pt
            @else
            お代金の{{ $rate_attributes->value }}%(上限
            @if (is_null($rate_attributes->limit) || $rate_attributes->limit == '')
            なし
            @else
            {{ number_format($rate_attributes->limit * 10) }}pt)
            @endif
            @endif
        </p>
        @endif
        <?php $monitor_attritures = $monitor->attributes(); ?>
        @if (isset($shop_attributes->viewMode) && $shop_attributes->viewMode != 0 && isset($monitor_attritures))
        <dl class="conditions">
            @if (isset($monitor_attritures->approvingPeriod))
            <dt><span class="icon-time"></span>ポイント獲得時期：</dt>
            <dd>{{ $monitor_attritures->approvingPeriod }}</dd>
            @endif
            @if (isset($monitor_attritures->enqueteSubmitExpires))
            <dt><span class="icon-deadline"></span>提出期限：</dt>
            <dd>当選確定した日から{{ $monitor_attritures->enqueteSubmitExpires }}日間</dd>
            @endif
            <dt><span class="icon-worksheet"></span>提出物：</dt>
            <dd>アンケート（10問）、来店証明</dd>
            <dt><span class="icon-friend"></span>月間派遣人数：</dt>
            <dd>
                @if (isset($monitor_attritures->numOfDispatch))
                {{ number_format(intval($monitor_attritures->numOfDispatch, 10)) }}名まで
                @endif
                &nbsp;
            </dd>
        </dl>
        @endif
        <p class="ta_r totop mt_5"><span class="icon-question"></span>&nbsp;{!! Tag::link(route('abouts.fancrew'), 'ご利用ガイド') !!}</p>
        <p class="btn_next">{!! Tag::link($shopEntryURL, '応募画面へ') !!}</p>
    </div><!--/contentsbox-->

    <h2>店舗情報</h2>
    <div class="contentsbox">
        <p class="pt_5">{!! nl2br(e($shop_attributes->description)) !!}</p>
        <dl class="monitor_element clearfix">
            <dt>ジャンル</dt>
            <dd>{{ $shop->Genre->attributes()->name ?? ''}}&nbsp;</dd>
            <dt>平均予算</dt>
            <dd>{{ $shop_attributes->averageBudget }}&nbsp;</dd>
            <dt>営業時間</dt>
            <dd>{{ $shop_attributes->businessHours }}&nbsp;</dd>
            <dt>休日</dt>
            <dd>{{ $shop_attributes->fixedHoliday }}&nbsp;</dd>
            <dt>電話番号</dt>
            <dd>{{ $shop_attributes->phoneNumber }}&nbsp;</dd>
            <dt>住所</dt>
            <dd>
                @if (isset($map_url) && isset($shop_attributes->address))
                {!! Tag::link($map_url, $shop_attributes->address, ['target' => '_blank']) !!}
                @endif
                &nbsp;
            </dd>
            <dt>アクセス</dt>
            <dd>{{ $shop_attributes->access }}&nbsp;</dd>
            <dt>ホームページ</dt>
            <dd>
                @if (isset($shop_attributes->pcUrl))
                {!! Tag::link($shop_attributes->pcUrl, $shop_attributes->pcUrl, ['target' => '_blank', 'class' => 'external']) !!}
                @endif
                &nbsp;
            </dd>
        </dl>
        <p class="pb_5 bbdotted">※ホームページから予約された場合、ポイント獲得の対象外となりますのでご注意ください。</p>

        <p class="btn_next">{!! Tag::link($shopEntryURL, '応募画面へ') !!}</p>
    </div><!--/contentsbox-->
    <p class="btn_y for_link" forUrl="{{ route('fancrew.pages', ['action' => 'pages']) }}">お店でお得トップに戻る</p>
</section><!--/monitor_detail-->
@endsection
