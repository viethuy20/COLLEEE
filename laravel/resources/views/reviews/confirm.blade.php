@php
$base_css_type = 'review';
@endphp
@extends('layouts.default')

@section('layout.title', $program->title.'への口コミ投稿確認'.' | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')
@section('layout.content')

<section class="contents">
    <h2 class="contents__ttl">口コミ投稿</h2>
    <div class="contents__box">
        @if ($already_reviewed)
        <h2 class="programs_post__ttl">口コミ投稿ありがとうございます。既にポイントを獲得済みです。</h2>
        @else
        <h2 class="programs_post__ttl">口コミ投稿で<span class="large">{{ $review_point_management ? $review_point_management->point : 5}}</span><span>ポイント</span>ゲット！</h2>
        @endif
        <h3 class="text--18 u-mt-20">以下の内容で投稿しますか？</h3>
    </div>
    <ul class="programs_detail__list">
        <li>
            <div class="programs_detail__list__head">
                <div class="programs_detail__list__name">
                    @php
                    $user = Auth::user();
                    @endphp
                    {{ Tag::link(route('reviews.reviewer', ['user' => $user]), $user->nickname ?? $user->name) }}
                    （{{ config('map.sex')[$user->sex] ?? 'その他' }}/{{ config('map.generation')[$user->generation] }}）
                </div>
                <div class="programs_detail__list__star"><!--★-->
                    <ul><!--
                        @for ($i = 1; $i <= 5; $i++)
                        --><li>{{ Tag::image(($i <= $review['assessment']) ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}</li><!--
                        @endfor
                    -->
                    <p class="programs_detail__list__star__txt">（{{ $review['assessment'] }}/5）</p>
                    </ul>
                </div><!--programs_detail__list__star-->
            </div>
            <p class="text--15">{{ $review['message'] }}</p>
            <p class="programs_detail__list__data">{{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
        </li>
    </ul>
    <div class="contents__box">
        {{ Tag::formOpen(['route' => 'reviews.store', 'method' => 'post', 'name' => 'confirm_review_form']) }}
        @csrf
        <div class="programs_post__form__btn">
        {{ Tag::formSubmit('口コミを投稿する', ['class' => 'p_send btn_more', 'id' => 'post_review']) }}
        </div>
        {{ Tag::formClose() }}
        <div class="programs_post__form__btn__gray">
            <p class="p_cancel">{{ Tag::link(route('programs.show', ['program' => $program]), 'キャンセル') }}</p>
        </div>
    </div>
</section><!--/contents-->

@endsection
