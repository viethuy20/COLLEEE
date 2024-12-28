@php
$base_css_type = 'gmo_tech';
@endphp
@extends('layouts.default')

@section('layout.title', 'ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,アプリでもっとポイントゲット!!')
@section('layout.description', '「アプリでもっとポイントゲット!!」は、GMO TECH株式会社が運営するGMOポイ活ポイントが貯まる広告です。')
@section('og_type', 'website')

@section('layout.content')
<!-- page nav -->
<div class="inner">
    <div class="contents__box u-mt-20 u-pt-remove u-pb-remove">
        <div class="gmotech__nav">
            <p class="text--15 u-text-ac">これから遷移するページは<br>GMO TECH株式会社が提供する広告ページです</p>
        </div>
    </div>
</div>

<!-- about -->
<div class="inner">
    <div class="contents__box u-mt-small u-pt-small" id="step">
        <!-- <div> -->
        {{ Tag::image('/images/gmotech/gmotech_app.png', 'アプリでもっとポイントゲット') }}
        <!-- </div> -->

        <div class="gmotech__about">
            <p class="text--15 u-mt-20">「アプリでもっとポイントゲット!!」は、GMO TECH株式会社が運営するGMOポイ活ポイントが貯まる広告です。</p>
            <div class="u-mt-20">
                {{ Tag::image('/images/gmotech/gmotech_flow.png', 'アプリでもっとポイントゲット') }}
            </div>
            <div class="gmotech__about__btn">
                <a href="{{ route('asps.click', ['asp' => \App\Asp::GMO_TECH]) }}" target="_blank"
                    onmousedown="ga('send', 'event', 'アプリでもっとポイントゲット', 'click', 'GMO TECH', {'nonInteraction': 1});">
                    「アプリでもっとポイントゲット!!」<br>でポイントを貯める
                </a>
            </div>
        </div>
    </div>
</div>

<!-- contact -->
<div class="inner">
    <div class="contents__box u-mt-small" id="step">
        <h2 class="text--22 orange u-text-ac">お問い合わせについて</h2>
        <div class="gmotech__contact">
            <p class="text--15 u-mt-40">「アプリでもっとポイントゲット!!」に掲載されている広告のお問い合わせについては、GMO
                TECH株式会社にて承ります。「アプリでもっとポイントゲット!!」内のお問い合わせページからお願いいたします。</p>
            <div class="u-mt-20">
                <ul>
                    <li>
                        {{ Tag::image('/images/gmotech/gmotech_contact1.jpg', 'ページ左上の三本線マークをタップ') }}
                    </li>
                    <li>
                        {{ Tag::image('/images/gmotech/gmotech_contact2.jpg', 'お問い合わせをタップ') }}
                    </li>
                </ul>
                <ul>
                    <li>
                        <p class="text--15 u-mt-3">ページ左上の三本線マークをタップ</p>
                    </li>
                    <li>
                        <p class="text--15 u-mt-3">お問い合わせをタップ</p>
                    </li>
                </ul>
            </div>
            <p class="text--15 red u-mt-20">※「アプリでもっとポイントゲット!!」に掲載の広告等について、GMOポイ活では成果調査等のお問い合わせ対応はしておりません。あらかじめご了承ください。
            </p>
        </div>
    </div>
</div>

<!-- 注意事項 -->
<div class="inner">
    <div class="contents__box u-mt-small gmotech__notice">
        <h2 class="text--22 orange u-text-ac">注意事項</h2>

        <div class="u-mt-20">
            <p class="text--15">・GMOポイ活と「アプリでもっとポイントゲット!!」で同じ広告が掲載されている場合がございます。ポイント数や獲得条件が異なる場合がございますので、ご注意ください。</p>
            <p class="text--15">・アプリはイントール時の条件が適用されますので、事前にポイントや獲得条件などをご確認の上、ご参加ください。</p>
            <p class="text--15">・「アプリでもっとポイントゲット!!」によるポイント獲得は、GMOポイ活ランク制度の条件の対象です。ただし、同じく条件である広告参加回数は対象外ですので、ご注意ください。
            </p>
            <p class="text--15">・「アプリでもっとポイントゲット!!」の挑戦中は90日経過した場合、表示が消えます。ただし、表示が消えるのみでデータは保持されていますので、ご安心ください。</p>
            <p class="text--15">
                ・「アプリでもっとポイントゲット!!」で挑戦中の広告につきまして、GMOポイ活のマイページの「現在の獲得予定ポイント」欄には表示されません。また、獲得した場合、GMOポイ活の獲得履歴欄にアプリ名は表示されませんので、あらかじめご了承ください。
            </p>
        </div>
    </div>
</div>

@endsection