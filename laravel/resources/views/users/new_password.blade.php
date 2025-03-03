@extends('layouts.mypage')

@section('layout.head')
    {!! Tag::script('/js/passwordchecker.js', ['type' => 'text/javascript']) !!}

    <script type="text/javascript"><!--
        var passwordLevelList = ['notyet', 'veryweak', 'weak', 'good', 'strong', 'verystrong'];

        var setPasswordLevel = function(userPassword) {
            var level = getPasswordLevel(userPassword.val());
            $('#UserPasswordLevel').attr("class", "clearfix " + passwordLevelList[level]);
        };
        $(function(){
            // パスワードチェッカー
            var userPassword = $('#UserPassword');
            setPasswordLevel(userPassword);
            userPassword.on('keyup', function(event) {
                setPasswordLevel($(this));
            });
        });
        //-->
    </script>
@endsection

@section('layout.title', 'パスワード登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <section class="contents">
        <h2 class="contents__ttl">パスワード登録</h2>

        <section class="contents__box">
            {!! Tag::formOpen(['url' => route('users.new_password'), 'class' => 'users__form custom-table u-mt-20']) !!}
            @csrf
            {!! Tag::formHidden('type', request('type') ?? '') !!}
            <table>
                <tr>
                    <th>
                        <span>新しいパスワード</span>
                    </th>
                    <td>
                        {!! Tag::formPassword('password', ['class' => 'form01 custom', 'required' => 'required', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off', 'id' => 'UserPassword']) !!}
                        <div class="clearfix notyet" id="UserPasswordLevel">
                            <div class="bar_strength"><div></div></div>
                            <p class="strength text--12">パスワード強度：</p>
                        </div>
                        <p class="stint">半角英数8文字以上</p>
                        <p>
                            {{ Tag::link('/support/?p=23', '使用可能な記号はこちら', ['class' => 'textlink'], null, false) }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <span>パスワード（確認用）</span>
                    </th>
                    <td>
                        {!! Tag::formPassword('password_confirmation', ['class' => 'form01', 'required' => 'required', 'minlength' => 8, 'maxlength' => '20', 'autocomplete' => 'off']) !!}
                        <p class="stint">確認のため再入力をしてください。</p>
                    </td>
                    @if ($errors->has('password'))
                        <!--エラーの場合はここに-->
                        <td>
                            <p class="error_message">
                                <span class="icon-attention"></span>{{ $errors->first('password') }}
                            </p>
                        </td>
                    @endif
                </tr>
                @if (session()->has('message'))
                    <tr>
                        <td>
                            <p class="error_message">
                                <span class="icon-attention"></span>{{ Session::get('message') }}
                            </p>
                        </td>
                    </tr>
                @endif
            </table>
            <div class="users__change__btn__pink">
                {!! Tag::formButton('変更', ['type' => 'submit']) !!}
            </div>
            {!! Tag::formClose() !!}
        </section>
        <div class="basic__change__btn">
            {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
        </div>
    </section>
@endsection
