@php
$base_css_type = 'greeadsreward';
@endphp
@extends('layouts.default')

@section('layout.title', 'アプリでポイントざっくざく!!｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,アプリでポイントざっくざく!!')
@section('layout.description', '「アプリでポイントざっくざく!!」は、Glossom株式会社が運営するGMOポイ活ポイントが貯まる広告です。')
@section('og_type', 'website')

@section('layout.content')
<!-- cssのクラスがskyflagになっています -->
<section class="contents">
    <div class="contents__box">
        <p class="skyflag_error__txt">
            <span>「アプリでポイントざっくざく!!」は<br />
                モバイル専用コンテンツです。<br />
            </span>
        </p>
        <p class="skyflag_error__txt__slim">表示されているQRコードをアプリなどを使用して読み取り、アクセスしてください。</p>
        <div class="skyflag_error__qrcode">{{ Tag::image(route('qr.image').'?'.http_build_query(['d' => route('greeadsreward.about'), 's' => 164]), '') }}</div>
        <p class="skyflag_error__txt__slim">QRコードの商標はデンソーウェーブの登録商標です。</p>
    </div>
</section>
@endsection
