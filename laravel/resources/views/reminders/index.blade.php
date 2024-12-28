<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.title', 'パスワードリマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

    <section class="contents">
        <h1 class="contents__ttl">パスワードリマインダー</h1>

        <section class="contents__box u-mt-small">
            <p class="text--15">パスワードをお忘れになった方は、ご登録のメールアドレスをご入力の上「確認」ボタンを押して下さい。<br/>
                パスワード再設定用URLをお送りします。</p>
            {!! Tag::formOpen(['url' => route('reminders.confirm'), 'class' => 'remind__form']) !!}
            @csrf
            <table>
                <tr>
                    <th style="display: block !important; width: 100% !important" class="u-pb-remove"><span class="">ご登録のメールアドレス</span>
                    </th>
                    <td style="display: block !important; width: 100% !important" class="u-pt-remove u-mt-small">
                        {!! Tag::formText('email', '', ['class' => 'form01', 'required' => 'required', 'placeholder' => 'メールアドレスを入力してください']) !!}
                        @if ($errors->has('email'))
                            <!--エラーの場合はここに-->
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('email') }}
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
            {!! Tag::formSubmit('確認', ['class' => 'remind__auth__btn']) !!}
            {!! Tag::formClose() !!}
        </section><!--/setting-->
        <div class="btn_y">{!! Tag::link(route('website.index'), 'トップページへ戻る') !!}</div>
    </section><!--/contents-->
@endsection
