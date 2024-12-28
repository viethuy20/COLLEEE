@php
$base_css_type = 'review';
@endphp
@extends('layouts.default')

@section('layout.title', $program->title.'への口コミ投稿確認'.' | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<div class="inner u-mt-7">
    <h2 class="contents__ttl">口コミ投稿確認</h2>
    <div class="contents__box u-mt-1">
        @if ($already_reviewed)
        <h2 class="programs_post__ttl">口コミ投稿ありがとうございます。既にポイントを獲得済みです。</h2>
        @else
        <h2 class="programs_post__ttl">口コミ投稿で<span class="large">{{ $review_point_management ? $review_point_management->point : 5}}</span><span>ポイント</span>ゲット！</h2>
        @endif
        <p class="text--14">以下の内容で投稿しますか？</p>
    </div>
    <ul class="programs_detail__review__list">
        <li class="programs_detail__review">
            <div class="programs_detail__list__head">
                <div class="programs_detail__review__profile">
                    @php
                    $user = Auth::user();
                    @endphp
                    <span>
                    {{ $user->nickname ?? $user->name }}
                    </span>
                    （{{ config('map.sex')[$user->sex] ?? 'その他' }}/{{ config('map.generation')[$user->generation] }}）
                </div>
                <div class="programs_detail__review__stars"><!--★-->
                    <ul><!--
                        @for ($i = 1; $i <= 5; $i++)
                        --><li>{{ Tag::image(($i <= $review['assessment']) ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}</li><!--
                        @endfor
                    -->
                    <span>（{{ $review['assessment'] }}/5）</span>
                    </ul>
                </div><!--programs_detail__list__star-->
            </div>
            <p class="programs_detail__review__txt">{{ $review['message'] }}</p>
            <p class="programs_detail__review__date">{{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
        </li>
    </ul>
    <div class="contents__box u-mt-1">

        {{ Tag::formOpen(['route' => 'reviews.store', 'method' => 'post', 'name' => 'confirm_review_form']) }}
        @csrf    
        <div class="programs_post__form__btn">
                {{ Tag::formSubmit('口コミを投稿する', ['class' => 'p_send', 'id' => 'post_review']) }}
            </div>
        {{ Tag::formClose() }}
        <div class="programs_post__form__btn__gray">
            {{ Tag::link(route('programs.show', ['program' => $program]), 'キャンセル', ['class' => 'p_cancel']) }}
        </div>
    </div>
</div>
@endsection
