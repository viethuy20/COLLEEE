<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.title', 'パスワードリマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <section class="contents__wrap">
        <div class="inner u-mt-20">
            <h2 class="text--24">メールアドレス確認</h2>
        </div>

        <section class="inner u-mt-20">
            <div class="contents__box">
                <p class="text--15">入力したメールアドレスに間違いが無いことを確認し「送信」ボタンを押して下さい。</p>
                {!! Tag::formOpen(['url' => route('reminders.send')]) !!}
                @csrf
                {!! Tag::formHidden('email', $email) !!}
                <div class="remind__center__box">
                    <div class="remind__center__box__main">
                        <h3>入力されたメールアドレス</h3>
                        <p class="">{{ $email }}</p>
                    </div>
                    <div class="remind__center__box__text">
                        <p>※パスワード再設定のメールが登録しているメールアドレスに届かない場合は、 お手数ですがサポート宛てまでご連絡いただきますようお願い致します。</p>
                        <p class="u-text-right u-mt-20">{!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせはこちら', ['class' => 'textlink__arrow']) !!}</p>
                    </div>
                </div>
                {!! Tag::formSubmit('送信', ['class' => 'remind__auth__btn']) !!}
                {!! Tag::formClose() !!}
            </div><!--/contentsbox-->
        </section><!--/setting-->
        <div class="btn_y">{!! Tag::link(route('reminders.index'), '戻る') !!}</div>
    </section>
@endsection
