<?php $base_css_type = 'withdrawal'; ?>
@extends('layouts.plane')

@section('layout.title', '退会｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents">
    <h1 class="contents__ttl">退会完了</h1>

    <div class="contents__box">
        <div class="withdrawal__center__box">
            <div class="withdrawal__center__box__text">
                <p class="">退会手続きが完了いたしました。</p>
                <p class="">
                    ご利用頂き、ありがとうございました。<br />
                    退会後数日間はメールが届く場合がございますがご了承ください。<br />
                    もしまたご利用頂くことができましたら、嬉しいです。
                </p>
            </div>
        </div>
    </div><!--/contentsbox-->
    <p class="btn_y">{!! Tag::link(route('website.index'), 'トップページへ戻る') !!}</p>
</section><!--/leaving-->
@endsection
