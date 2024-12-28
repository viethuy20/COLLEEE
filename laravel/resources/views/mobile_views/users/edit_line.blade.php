<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', 'LINE連携編集｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <?php $user = Auth::user();
    $lineService = new \App\Services\Line\LineService();
    $urlLine = $lineService->getLoginBaseUrl();
    ?>
    <div class="inner u-mt-20">
        <h1 class="contents__ttl u-mt-20">LINE連携</h1>
    </div>

    <section class="inner">
        <section class="contents__box u-mt-20 content_line">
            @if(!empty($user->line_id))
                <div class="entries_form__privacy">
                    <span class="u-text-ac">
                        {!! Tag::formCheckbox('consent', 1, false, ['id' => 'consent']) !!} <label for="consent">連携済み</label>
                    </span>
                    <div class="users__change__btn__pink">
                        @if (empty($user->password))
                            {!! Tag::formButton('解除する', ['type' => 'button', 'id' => 'btn-cancel-line','data-link' => route('users.new_password', ['type' => '0'])]) !!}
                        @else
                            {!! Tag::formButton('解除する', ['type' => 'button', 'id' => 'btn-cancel-line','data-link' => route('line.cancel')]) !!}
                        @endif
                    </div>
                </div>
                <div class="login__form__disconnect_line">
                    <p class="stint">※連携を解除する場合は、チェックボックスにチェックを</p>
                    <p class="stint" style="margin-left: 1rem">入れてから解除するボタンをクリックしてください</p>
                </div>
            @else
                <div class="entries_form__privacy">
                <span class="u-text-ac">
                        未連携
                </span>
                    <div class="login__form__btn_line">
                        <a href="{{$urlLine}}">
                            {{ Tag::image('/images/login/btn_login_line.png')}}</a>
                    </div>
                </div>
            @endif
        </section>
        <div class="basic__change__btn">
            {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
        </div>
    </section>

    <script type="text/javascript">
        var check_box = $('.u-text-ac');
        $('#btn-cancel-line').prop('disabled', true);
        // サブメニューの検索をクリックしたら検索エリアを開閉する
        check_box.on('click',function(){
            if ($(".u-text-ac input[type='checkbox']").is(':checked')) {
                $("#btn-cancel-line").prop('disabled', false);
            } else {
                $("#btn-cancel-line").prop('disabled', true);
            }
        });

        $('#btn-cancel-line').on('click',function(){
            window.location.href = $(this).data('link');
        });
    </script>
@endsection
