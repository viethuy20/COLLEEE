<?php $base_css_type = 'withdrawal'; ?>
@extends('layouts.default')

@section('layout.title', '退会｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents__wrap">
    <div class="inner u-mt-20">
		<h2 class="text--24">退会完了</h2>
	</div>

    <section class="inner u-mt-20">
        <div class="contents__box">
            <div class="withdrawal__center__box">
                <div class="withdrawal__center__box__text">
                    <p class="done">退会手続きが<br />完了いたしました。</p>

                    <p>ご利用頂き、ありがとうございました。<br />
                    退会後数日間はメールが届く場合がございますがご了承下さい。
                    もしまたご利用頂くことができましたら、嬉しいです。</p>
                </div>
            </div>
        </div>
    </section><!--/contentsbox--><!--/leaving-->
    <div class="btn_y">{!! Tag::link(route('website.index'), 'トップページへ戻る') !!}</div>
</section>
@endsection
