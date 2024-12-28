<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'メールアドレス変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<section class="contents">
    <div class="inner">
        <h2 class="contents__ttl u-mt-20">メールアドレス変更完了</h2>
        <section class="contents__box u-mt-small">
            <div class="users__center__box">
                <div class="users__center__box__main">
                    <p class="text--15">メールアドレスの変更が完了しました。</p>
                    <p>
                        {{ Tag::link(route('users.edit_email_setting'), 'メールマガジンの受信設定はこちら', ['class' => 'textlink'], null, false) }}
                    </p>
                </div>
            </div>
        </section><!--/contentsbox-->

        <div class="basic__change__btn">
            {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
        </div>
    </div><!--/setting-->
</section>
@endsection
