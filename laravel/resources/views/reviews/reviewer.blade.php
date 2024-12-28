@php
$base_css_type = 'review';
$reviewer_name = $reviewer->nickname ?? $reviewer->name
@endphp
@extends('layouts.default')

@section('layout.title', $reviewer_name.'さんの投稿した口コミ一覧'.' | ポイントサイトならGMOポイ活')
@section('layout.keywords', '口コミ,一覧')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')
@section('og_type', 'website')

@section('layout.content')

<div class="contents">
    @if ($review_total < 1)
        @if(Auth::check() && Auth::user()->id == $reviewer->id)
        <div class="revirew__title">
            <h2 class="contents__ttl">{{ $reviewer_name }}さんの口コミ一覧（{{ $review_total }}件）</h2>
        </div>
        <div class="revirew__box">
            <ul>
                <li>
                    <div class="revirew__box__head">
                        <div class="revirew__box__name">あなたも口コミを投稿してみませんか？</div>
                    </div>
                    <p class="text--15">参加した広告の口コミを書いてみませんか？</p>
                    <p class="text--15">口コミが掲載されると、初回<span>{{ $review_point_management ? $review_point_management->point : 5}}ポイント</span>をプレゼント！</p>
                </li>
                <div class="revirew__box__button">
                    {{ Tag::link(route('users.point_list'), '獲得済み一覧へ', null, null, false) }}
                </div>
            </ul>
        </div>
        @endif
    @else
        @include('elements.reviewer_review_list', ['reviewer' => $reviewer, 'condition' => $condition, 'review_list' => $review_list])
    @endif
</div><!--contents -->
@endsection
