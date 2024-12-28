<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.head')
{!! Tag::script('/js/passwordchecker.js', ['type' => 'text/javascript']) !!}

<script type="text/javascript"><!--
var passwordLevelList = ['notyet', 'veryweak', 'weak', 'good', 'strong', 'verystrong'];

var setPasswordLevel = function(userPassword) {
    var level = getPasswordLevel(userPassword.val());
    console.log('password level:' + level);
    $('#UserPasswordLevel').attr("class", "bar " + passwordLevelList[level]);
};
var setTxtPasswordLevel = function(userPassword) {
    var level = getPasswordLevel(userPassword.val());
    $('#UserTxtPasswordLevel').attr("class", "txt " + passwordLevelList[level]);
};
$(function(){
    // パスワードチェッカー
    var userPassword = $('#UserPassword');
    setPasswordLevel(userPassword);
    userPassword.on('keyup', function(event) {
        setPasswordLevel($(this));
    });
    var userTxtPassword = $('#UserPassword');
    setTxtPasswordLevel(userTxtPassword);
    userTxtPassword.on('keyup', function(event) {
        setTxtPasswordLevel($(this));
    });
});
//-->
</script>
@endsection

@section('layout.title', 'パスワードリマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents__wrap">
    <div class="inner u-mt-20">
		<h2 class="text--24">パスワード再設定</h2>
	</div>

    <section class="inner u-mt-20">
        <div class="contents__box">
            {!! Tag::formOpen(['url' => route('reminders.store'), 'class' => 'remind__form']) !!}
            @csrf    
            {!! Tag::formHidden('email_token_id', $email_token_id) !!}
                <table>
                    <tr>
                        <th><span>新しいパスワード</span></th>
                        <td>
                            {!! Tag::formPassword('password', ['class' => 'form01', 'required' => 'required', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off', 'id' => 'UserPassword']) !!}
                            <p class="stint">半角英数8文字以上</p>
                            <p class="u-mt-small">{!! Tag::link('/support/?p=23', '使用可能な記号はこちら', ['class' => 'textlink__arrow']) !!}</p>
                            <div class="remind__form__pw">
                                <div class="bar" id="UserPasswordLevel">
                                    <div></div>
                                </div>
                                <div class="txt" id="UserTxtPasswordLevel">パスワード強度：</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><span>パスワード（確認用）</span></th>
                        <td>
                            {!! Tag::formPassword('password_confirmation', ['class' => 'form01', 'required' => 'required', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off']) !!}
                            <p class="stint">確認のため再入力をしてください。</p>
                            <!--エラーの場合はここに-->
                            @if ($errors->has('password'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('password') }}</p>
                            @endif
                            @if (Session::has('message'))
                            <p class="error_message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="remind__form__btn">
                    <button type="submit">変更</button>
                </div>
            {!! Tag::formClose() !!}
        </div>
    </section><!--/contents box-->
</section><!--/setting-->
@endsection
