@php
$base_css_type = 'review';
@endphp
@extends('layouts.default')

@section('layout.title', $program->title.'への口コミ投稿確認'.' | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<div class="inner u-mt-7">
    <h2 class="contents__ttl">口コミ投稿完了</h2>
    <div class="contents__box u-mt-1">
        <p class="programs_post__ttl">投稿が完了しました。</p>
        <div class="programs_post__form__btn">
            {{ Tag::link(route('programs.show', ['program' => $program]), '広告詳細へ戻る') }}
        </div><!--/contentsbox-->
    </div>
</div>
@endsection
