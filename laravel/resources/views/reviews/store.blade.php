@php
$base_css_type = 'review';
@endphp
@extends('layouts.default')

@section('layout.title', $program->title.'への口コミ投稿'.' | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<section class="contents">
    <h2 class="contents__ttl u-mt-40">口コミ投稿完了</h2>
    <div class="contents__box">
        <h2 class="programs_post__ttl">投稿が完了しました。</h2><br>
        <div class="programs_post__form__btn">
            <p class="btn_more">{{ Tag::link(route('programs.show', ['program' => $program]), '広告詳細へ戻る') }}</p>
        </div>
    </div><!--/contentsbox-->
</section><!--/reviewparts--><!--/contents-->

@endsection
