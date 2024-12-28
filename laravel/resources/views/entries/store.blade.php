<?php $base_css_type = 'signup'; ?>
@extends('layouts.base')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')

@section('layout.plane.head')

<meta http-equiv="refresh" content="5;URL='{{ $registration_source }}'" />

@if (config('app.env') == 'production')
@php
$data = App\Http\Middleware\SaveCookie::getData();
@endphp
@if (isset($data->pr_code) && Auth::check() && !isset(Auth::user()->old_id))
<script><!--
window.dataLayer = window.dataLayer || [];
dataLayer.push({
    'userId':'{{ Auth::user()->name }}',
    'yosooId':'{{ Auth::user()->old_id ?? '' }}',
});
//-->
</script>
@endif
@endif
@endsection
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
            新規会員登録
        </li>
    </ol>
</section>
@endsection

@section('layout.plane.body')
<section class="loading">
    <h1>会員登録処理中です。</h1>
    <p>{{ Tag::image('/images/img-loading.svg', '', ['width' => '100', 'height' => '100', 'class' => 'load']) }}</p>
</section>
@endsection
