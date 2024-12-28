<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', '発信認証｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。無料で会員登録して、いつもの生活を賢くお得に！')

@section('layout.content')
<div class="inner u-mt-20">
    <h1 class="contents__ttl u-mt-20">発信認証</h1>
</div>

<section class="inner">
    <div class="contents__box u-mt-20">
        <div class="users__center__box">
            <div class="users__center__box__text">
                <p>ご本人様確認の為、発信認証が必要です。<br />以下の登録端末の電話番号から発信認証を行いますか?</p>
                <p>※次の画面に「発信認証電話番号」が表示され、2分以内に発信、認証作業が必要です。<br />メモが必要な際は事前にご準備ください。</p>
            </div>
        </div>
        {!! Tag::formOpen(['url' => route('phones.init')]) !!}
        @csrf 
            {!! Tag::formHidden('referer', $referer) !!}
            <h1 class="contents__ttl u-mt-20">登録電話番号</h1>
            <div class="contents_box">
                <div class="users__center__box">
                    <div class="users__center__box__main">
                        <h3>{{ Auth::user()->masked_tel }}</h3>
                        @if (session()->has('message'))
                        <!--エラーの場合はここに-->
                        <p class="error_message"><span class="icon-attention"></span>{{ nl2br(e(Session::get('message'))) }}</p>
                        @endif
                    </div>
                    <div class="users__change__btn__pink">
                        <button type="submit">次へ</button>
                    </div>
                </div>
            </div>

        {!! Tag::formClose() !!}
    </div>
</section><!--/contentsbox--><!--/setting-->
@endsection
