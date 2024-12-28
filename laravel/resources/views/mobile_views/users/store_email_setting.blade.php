<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'メールマガジン受信設定｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents">
    <section class="inner">
        <h2 class="contents__ttl u-mt-20">メールマガジン受信設定完了</h2>

        <div class="contents__box u-mt-small">
            <div class="users__center__box">
                <div class="users__center__box__text__custom">
                    <p class="text--15">メールマガジン受信設定が<br />完了しました。</p>
                </div>
            </div>
        </div>
        <div class="basic__change__btn">
            {!! Tag::link(route('users.show'), 'マイページへ戻る') !!}
        </div>
    </section>
</section><!--/setting-->
@endsection
