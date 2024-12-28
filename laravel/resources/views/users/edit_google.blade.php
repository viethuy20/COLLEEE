@extends('layouts.mypage')
<link href="{{ asset('/css/entries.css') }}" rel="stylesheet">
@section('layout.title', 'GOOGLE連携編集｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <?php
    $user = Auth::user();
    ?>
    <section class="contents">
        <h2 class="contents__ttl">Googleアカウント連携</h2>

        <section class="contents__box content_line">
            @if(session('message_error_email'))
            <div class="form-error__message js-error-message" style="color: red">
                    <span class="icon-attention"></span> {{ session('message_error_email') }}
            </div>
            @endif
            @if(!empty($user->google_id))
            <div class="entries_form__privacy">
                <span class="u-text-ac">
                    {!! Tag::formCheckbox('consent', 1, false, ['id' => 'consent']) !!} <label for="consent">連携済み</label>
                </span>
                <div class="users__change__btn__pink">
                    @if (empty($user->password))
                        {!! Tag::formButton('解除する', ['type' => 'button', 'id' => 'btn-cancel-google','data-link' => route('users.new_password',['type' => '1'])]) !!}
                    @else
                        {!! Tag::formButton('解除する', ['type' => 'button', 'id' => 'btn-cancel-google','data-link' => route('google.cancel')]) !!}
                    @endif
                </div>
            </div>
            <div class="login__form__disconnect_line">
                <p class="stint">※連携を解除する場合は、チェックボックスにチェックを</p>
                <p class="stint" style="margin-left: 1rem">入れてから解除するボタンをクリックしてください</p>
            </div>
            @else
                <div class="entries_form__privacy">
                <span class="u-text-ac" style="margin: 0 30px 0 0">
                        未連携
                </span>
                    <div class="login__form__btn_google entries__sns__btn google" style="width: 60%;">
                        <a href="{{ route('users.create.google') }}" class=""><i><img src="/images/common/ico_google.svg"></i><p>Googleでログイン</p></a>
                    </div>
                </div>
           @endif
           @if($errors->has('error_login_gg'))
           <p class="form-error__message js-error-message red">
                    <span class="icon-attention"></span> {{ $errors->first('error_login_gg') }}
           </p>
            @endif

        </section><!--/setting-->
        <div class="basic__change__btn">
            {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
        </div>
    </section><!--/contents-->

    <script type="text/javascript">
        var check_box = $('.u-text-ac');
        $('#btn-cancel-google').prop('disabled', true);
        // サブメニューの検索をクリックしたら検索エリアを開閉する
        check_box.on('click',function(){
            if ($(".u-text-ac input[type='checkbox']").is(':checked')) {
                $("#btn-cancel-google").prop('disabled', false);
            } else {
                $("#btn-cancel-goole").prop('disabled', true);
            }
        });

        $('#btn-cancel-google').on('click',function(){
            window.location.href = $(this).data('link');
        });
    </script>
@endsection
