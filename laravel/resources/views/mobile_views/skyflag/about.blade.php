@php
$base_css_type = 'sky_flag';
@endphp
@extends('layouts.default')

    @section('layout.title', 'アプリでポイ活｜ポイントサイトならGMOポイ活')
    @section('layout.keywords', 'GMOポイ活,アプリでポイ活')
    @section('layout.description', '「アプリでポイ活」は、株式会社Skyfallが運営するGMOポイ活ポイントが貯まる広告です。')
    @section('og_type', 'website')

    @section('layout.content')
    <!-- page nav -->
    <div class="inner">
        <div class="contents__box u-mt-20 u-pt-remove u-pb-remove">
            <div class="skyflag__nav">
                <p class="text--15 u-text-ac">これから遷移するページは<br>株式会社Skyfallが提供する広告ページです</p>
            </div>
        </div>
    </div>

    <!-- about -->
    <div class="inner">
        <div class="contents__box u-mt-small u-pt-small" id="step">
            <!-- <div> -->
                {{ Tag::image('/images/skyflag/skyflag_app.png', 'アプリでポイ活') }}
            <!-- </div> -->

            <div class="skyflag__about">
                <p class="text--15 u-mt-20">「アプリでポイ活」は、株式会社Skyfallが運営するGMOポイ活ポイントが貯まる広告です。</p>
                <div class="u-mt-20">
                    {{ Tag::image('/images/skyflag/skyflag_flow.png', 'アプリでポイ活') }}
                </div>
                <div class="skyflag__about__btn">
                    {{ Tag::link(route('asps.click', ['asp' => \App\Asp::SKYFLAG_OFFER]), '「アプリでポイ活」でポイントを貯める', ['target' => '_blank', 'onmousedown' => "ga('send', 'event', 'アプリでポイ活', 'click', 'SKYFLAG OW', {'nonInteraction': 1});"]) }}
                </div>
            </div>
        </div>
    </div>

    <!-- contact -->
    <div class="inner">
        <div class="contents__box u-mt-small" id="step">
            <h2 class="text--22 orange u-text-ac">お問い合わせについて</h2>
            <div class="skyflag__contact">
                <p class="text--15 u-mt-40">「アプリでポイ活」に掲載されている広告のお問い合わせについては、株式会社Skyfallにて承ります。「アプリでポイ活」内のお問い合わせページからお願いいたします。</p>
                <div class="u-mt-20">
                    <ul>
                        <li>
                            {{ Tag::image('/images/skyflag/skyflag_contact1.png', '「アプリでポイ活」ページ左上の三本線マークをタップ') }}
                        </li>
                        <li>
                            {{ Tag::image('/images/skyflag/skyflag_contact2.png', 'お問い合わせをタップ') }}
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <p class="text--15 u-mt-3">「アプリでポイ活」ページ左上の三本線マークをタップ</p>
                        </li>
                        <li>
                            <p class="text--15 u-mt-3">お問い合わせをタップ</p>
                        </li>
                    </ul>
                </div>
                <p class="text--15 red u-mt-20">※「アプリでポイ活」に掲載の広告等について、GMOポイ活では成果調査等のお問い合わせ対応はしておりません。あらかじめご了承ください。</p>
            </div>
        </div>
    </div>

    <!-- 注意事項 -->
    <div class="inner">
        <div class="contents__box u-mt-small skyflag__notice">
            <h2 class="text--22 orange u-text-ac">注意事項</h2>

            <div class="u-mt-20">
                <p class="text--15">・GMOポイ活と「アプリでポイ活」で同じ広告が掲載されている場合がございます。ポイント数や獲得条件が異なる場合がございますので、ご注意ください。</p>
                <p class="text--15">・アプリはイントール時の条件が適用されますので、事前にポイントや獲得条件などをご確認の上、ご参加ください。</p>
                <p class="text--15">・「アプリでポイ活」によるポイント獲得は、GMOポイ活ランク制度の条件の対象です。ただし、同じく条件である広告参加回数は対象外ですので、ご注意ください。</p>
                <p class="text--15">・「アプリでポイ活」の挑戦中は90日経過した場合、表示が消えます。ただし、表示が消えるのみでデータは保持されていますので、ご安心ください。</p>
                <p class="text--15">・「アプリでポイ活」で挑戦中の広告につきまして、GMOポイ活のマイページの「現在の獲得予定ポイント」欄には表示されませんので、あらかじめご了承ください。</p>
            </div>
        </div>
    </div>

    @endsection
