<?php $base_css_type = 'about'; ?>
@extends('layouts.default')

@section('layout.title', '会員利用規約｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,会員規約')
@section('layout.description', 'GMOポイ活の会員利用規約を提示しています。この会員規約に基づいてサービスを提供しています。GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪')
@section('og_type', 'website')
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('abouts.membership_contract');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "会員利用規約", "item": "' . $link . '"},';

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
            会員利用規約
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
<div class="inner">
    <h2 class="contents__ttl u-mt-20">GMOポイ活会員利用規約</h2>
</div>
<div class="inner">
    <textarea class="agreement" readonly>{{ config('text.membership_contract') }}</textarea>
</div>
@endsection

