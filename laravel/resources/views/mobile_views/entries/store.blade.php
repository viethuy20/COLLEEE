<?php $base_css_type = 'signup'; ?>
@extends('layouts.base')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')

@section('layout.plane.head')

<meta http-equiv="refresh" content="5;URL='{{ $registration_source }}'" />

<style>
.load {
margin:0 auto;
-webkit-animation: spin 1.8s linear infinite;
-moz-animation: spin 1.8s linear infinite;
-ms-animation: spin 1.8s linear infinite;
-o-animation: spin 1.8s linear infinite;
animation: spin 1.8s linear infinite;
}

@-webkit-keyframes spin {
0% {-webkit-transform: rotate(0deg);}
100% {-webkit-transform: rotate(360deg);}
}
@-moz-keyframes spin {
0% {-moz-transform: rotate(0deg);}
100% {-moz-transform: rotate(360deg);}
}
@-ms-keyframes spin {
0% {-ms-transform: rotate(0deg);}
100% {-ms-transform: rotate(360deg);}
}
@-o-keyframes spin {
0% {-o-transform: rotate(0deg);}
100% {-o-transform: rotate(360deg);}
}
@keyframes spin {
0% {transform: rotate(0deg);}
100% {transform: rotate(360deg);}
}
#loading {margin: 100px auto;text-align: center;}
#loading h1 {margin-bottom: 30px;font-size: 20px;}
</style>

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
<section id="loading">
    <h1>会員登録処理中です。</h1>
    <p>{{ Tag::image('/images/img-loading.svg', '', ['width' => '70', 'height' => '70', 'class' => 'load']) }}</p>
</section>
@endsection
