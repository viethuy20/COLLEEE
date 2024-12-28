@php
    $base_css_type = 'gmo_tech';
@endphp
@extends('layouts.default')

@section('layout.title', 'アプリでもっとポイントゲット!!｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,アプリでもっとポイントゲット!!')
@section('layout.description', '「アプリでもっとポイントゲット!!」は、GMO TECH株式会社が運営するGMOポイ活ポイントが貯まる広告です。')
@section('og_type', 'website')

@section('layout.content')
    <section class="contents">
        <div class="contents__box">
            <p class="gmotech_error__txt">
            <span>「アプリでもっとポイントゲット!!」は<br />
                モバイル専用コンテンツです。<br />
            </span>
            </p>
            <p class="gmotech_error__txt__slim">表示されているQRコードをアプリなどを使用して読み取り、アクセスしてください。</p>
            <div class="gmotech_error__qrcode">{{ Tag::image(route('qr.image').'?'.http_build_query(['d' => route('mychips.about'), 's' => 164]), '') }}</div>
            <p class="gmotech_error__txt__slim">QRコードの商標はデンソーウェーブの登録商標です。</p>
        </div>
    </section>
@endsection
