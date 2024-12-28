<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.title', 'パスワードリマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

    <section class="contents">
        <h1 class="contents__ttl">メール送信完了</h1>

        <section class="contents__box u-mt-small">
            <div class="remind__center__box">
                <div class="remind__center__box__text u-mt-remove">
                    <p class="done">送信が完了しました</p>
                    <p class="mb_15">ご入力頂いたアドレス宛にメールを送信しました。メール内に記載のURLにアクセスしてパスワードを再設定して下さい。</p>
                    @if (config('app.env') != 'production')
                        <p>テスト環境用URL:{!! route('reminders.password', ['email_token_id' => $email_token_id]) !!}<br/>
                        </p>
                    @endif
                </div>
            </div><!--/contents box-->
        </section><!--/setting-->
        <div class="btn_y">{!! Tag::link(route('website.index'), 'トップページへ戻る') !!}</div>
    </section><!--/contents-->
@endsection
