<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'メールアドレス変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<div class="inner u-mt-20">
    <h1 class="contents__ttl u-mt-20">メール送信完了</h1>
</div>

<section class="inner">
    <section class="contents__box u-mt-20">
        <p class="u-font-bold u-text-ac text--18 red">送信が完了しました</p>
        @if (config('app.env') != 'production')
        <div class="text--15 u-mt-20">
            テスト環境用URL:{!! route('users.confirm_email', ['email_token_id' => $email_token_id]) !!}<br />
        </div>
        @endif

        <p class="mypage__back__btn">{!! Tag::link(route('users.edit'), '戻る') !!}</p>
    </section><!--/contentsbox-->
</section><!--/setting-->
@endsection
