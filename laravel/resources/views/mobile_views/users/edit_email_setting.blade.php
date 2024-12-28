<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'メールマガジン受信設定｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')


@section('layout.content')
<section class="contents">
    <div class="inner">
        <h2 class="contents__ttl u-mt-20">メールマガジン受信設定</h2>

        <?php $user = \Auth::user() ?>
        {!! Tag::formOpen(['url' => route('users.edit_email_setting')]) !!}
        @csrf    
        <section class="contents__box u-mt-small">
                <section class="users__center__box">
                    <div class="users__center__box__main">
                        <h3>メールマガジンの受信</h3>
                        <p class="select_radio">
                            <label>{!! Tag::formRadio('email_magazine', 1, $user->email_magazine == 1) !!}<span>受信する</span></label>
                            <label>{!! Tag::formRadio('email_magazine', 0, $user->email_magazine != 1) !!}<span>受信しない</span></label>
                        </p>
                    </div>
                    @if ($errors->has('email_magazine'))
                    <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('email_magazine') }}</p>
                    @endif
                </section>
            </section>
            <div class="mt_20">
                <section class="contents__box u-mt-small">
                    <section class="users__center__box">
                        <div class="users__center__box__main">
                            <h3>ご登録いただいているメールアドレス</h3>
                            <p>{{ $user->email }}</p>
                        </div><!--/address_new-->
                        <div class="users__center__box__text__custom u-mt-20">
                            <p>
                                {{ Tag::link(route('users.edit_email'), 'メールアドレス変更はこちら', ['class' => 'textlink'], null, false) }}
                            </p>
                        </div>
                        <!-- warning -->
                        <p class="error_message"><span class="icon-attention"></span>注意事項</p>
                        <p class="text--15">サービス上重要な通知となるメールは、メールマガジンの受信をされていない方にも配信させて頂くことがあります。ご了承ください。<br>
                        メールマガジンには、失効ポイントに関する通知メールが含まれます。こちらはメールマガジンの受信をされていない方には配信されません。</p>
                        {!! Tag::formButton('送信', ['class' => 'users_auth__btn', 'type' => 'submit']) !!}
                    </section>
                </section>
            </div>
        {!! Tag::formClose() !!}
    </div>
</section><!--/contentsbox--><!--/setting-->
@endsection
