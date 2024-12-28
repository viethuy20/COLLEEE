<?php $base_css_type = 'remind'; ?>
@extends('layouts.default')

@section('layout.title', $item .'リマインダー｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
    <section class="contents__wrap">
        <div class="inner u-mt-20">
            <h2 class="text--24">{{ $item }}再設定完了</h2>
        </div>

        <section class="inner u-mt-20">
            <div class="contents__box">
                <div class="remind__center__box">
                    <div class="remind__center__box__text u-mt-remove">
                        <p class="done">{{ $item }}の<br/>再設定が完了しました。</p>
                    </div>
                </div>
            </div><!--/contents box-->
        </section>
        <div class="btn_y">{!! Tag::link(route('website.index'), 'トップページへ戻る') !!}</div>
    </section><!--/setting-->
@endsection
