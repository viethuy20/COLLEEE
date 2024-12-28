<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.title', 'メールアドレスリマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <section class="contents__wrap">
        <div class="inner u-mt-20">
            <h2 class="text--24">メールアドレス変更</h2>
        </div>

        <section class="inner u-mt-20">
            <div class="contents__box">
                {!! Tag::formOpen(['url' => route('reminders.store_email'), 'class' => 'remind__form']) !!}
                @csrf
                {!! Tag::formHidden('email_token_id', $email_token_id) !!}
                <table>
                    <table>
                        <th><span>認証</span></th>
                        <th>
                            {!! Tag::formPassword('password', ['class' => 'form01', 'required' => 'required', 'size' => '23', 'autocomplete' => 'off', 'placeholder' => 'パスワード']) !!}
                            <!--エラーの場合はここに-->
                            @if (Session::has('message'))
                                <p class="error_message"><span
                                        class="icon-attention"></span>{!! nl2br(Session::get('message')) !!}</p>
                            @endif
                            @if ($errors->has('email_token_id'))
                                <p class="error_message"><span
                                        class="icon-attention"></span>{{ $errors->first('email_token_id') }}</p>
                            @endif
                            @if ($errors->has('password'))
                                <p class="error_message"><span
                                        class="icon-attention"></span>{{ $errors->first('password') }}</p>
                            @endif
                        </th>
                    </table>
                </table>
                <div class="remind__form__btn">
                    <button type="submit">次へ</button>
                </div>
                {!! Tag::formClose() !!}
            </div>
        </section><!--/contents box-->
    </section><!--/setting-->
@endsection
