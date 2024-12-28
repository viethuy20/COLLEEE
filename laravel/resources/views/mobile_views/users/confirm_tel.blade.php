<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', '電話番号変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents">
    <div class="inner">
        <h2 class="contents__ttl u-mt-20">電話番号変更確認</h2>
        <section class="contents__box u-mt-small">
            <div class="users__center__box">
                <div class="users__center__box__main">
                    <h3>新しい電話番号</h3>
                    <p>{{ $ost_token->tel }}</p>
                </div>
                <div class="users__center__box__text">
                    <p>※期限2分※<br />新しい電話番号から、「発信認証電話番号」へ発信してください。<br />呼び出し音の後、自動的に通話が終了します。（音声アナウンス等は流れません）</p><br />
                    <p>上記完了後、「認証」ボタンを押してください。</p><br />
                    <p>※通話料金は無料です。<br />※電話番号の入力間違いにご注意ください。</p>
                </div>
            </div>
        </section>

        <h2 class="contents__ttl">発信認証電話番号</h2>
        <section class="contents__box u-mt-small">
            <section class="users__center__box">
                {!! Tag::formOpen(['url' => route('users.store_tel'), 'class' => 'users_auth__form']) !!}
                @csrf    
                {!! Tag::formHidden('email_token_id', $email_token->id) !!}
                    <div class="users__center__box">
                        <div class="users__center__box__main">
                            {!! Tag::link('tel:'.$ost_token->authentic_number, $ost_token->authentic_number, ['class' => 'text--15'], false, false) !!}
                            @if (Session::has('message'))
                            <p class="error_message"><span class="icon-attention"></span>{!! nl2br(Session::get('message')) !!}</p>
                            @endif
                        </div>
                    </div>
                    {!! Tag::formButton('認証', ['class' => 'users_auth__btn', 'type' => 'submit']) !!}
                {!! Tag::formClose() !!}
            </section>
        </section>
    </div>
</section>
@endsection
