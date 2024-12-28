@php
$base_css_type = 'skyflag';
@endphp
@extends('layouts.default')

@section('layout.title', 'アプリでポイ活｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,アプリでポイ活')
@section('layout.description', '「アプリでポイ活」は、株式会社Skyfallが運営するGMOポイ活ポイントが貯まる広告です。')
@section('og_type', 'website')

@section('layout.content')
<section class="contents">
    <div class="contents__box">
        <p class="skyflag_error__txt">
            <span>「アプリでポイ活」は<br />
                モバイル専用コンテンツです。<br />
            </span>
        </p>
        <p class="skyflag_error__txt__slim">表示されているQRコードをアプリなどを使用して読み取り、アクセスしてください。</p>
        <div class="skyflag_error__qrcode">{{ Tag::image(route('qr.image').'?'.http_build_query(['d' => route('skyflag.about'), 's' => 164]), '') }}</div>
        <p class="skyflag_error__txt__slim">QRコードの商標はデンソーウェーブの登録商標です。</p>
    </div>
</section>
@endsection
