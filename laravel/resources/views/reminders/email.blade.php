<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.title', 'メールアドレスリマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

    <section class="contents">
        <h1 class="contents__ttl">メールアドレス変更</h1>

        <section class="contents__box u-mt-small">
            {!! Tag::formOpen(['url' => route('reminders.store_email'), 'class' => 'remind__form']) !!}
            @csrf
            {!! Tag::formHidden('email_token_id', $email_token_id) !!}
            <table>
                <tr>
                    <th><span>認証</span></th>
                    <td>
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
                    </td>
                </tr>
            </table>
            <div class="remind__form__btn">
                <button type="submit">次へ</button>
            </div>
            {!! Tag::formClose() !!}
        </section><!--/setting-->
    </section><!--/contents-->
@endsection
