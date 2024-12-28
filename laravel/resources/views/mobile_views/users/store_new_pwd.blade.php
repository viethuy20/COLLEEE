<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'パスワード登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <section class="contents">
        <div class="inner u-mt-20">
            <h1 class="contents__ttl u-mt-20">パスワード登録</h1>
        </div>

        <section class="inner">
            <section class="contents__box u-mt-20">
                <p class="u-font-bold u-text-ac text--18 red">パスワードを登録しました。</p>
                <p class="basic__change__btn">{!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}</p>
            </section>
        </section>
    </section>
@endsection
