<?php $base_css_type = 'mypage'; ?>
@extends('layouts.default')

@section('layout.title', '基本情報変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')

<?php $user = Auth::user(); ?>

<div class="inner">
    <h1 class="contents__ttl u-mt-20">基本情報</h1>
</div>

<section class="inner">
    <div class="users__box">
        <dl class="user_info__list">
            <dt><h2 class="user_info__list__ttl">メールアドレス</h2></dt>
            <dd class="user_info__list__item">
                <p>{{ $user->email }}</p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_email'), '変更') !!}</p>
            </dd>
            <dt><h2 class="user_info__list__ttl u-mt-small">パスワード</h2></dt>
            <dd class="user_info__list__item">
                <p></p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_password'), '変更') !!}</p>
            </dd>
            <dt><h2 class="user_info__list__ttl u-mt-small">電話番号</h2></dt>
            <dd class="user_info__list__item">
                <p>{{ $user->masked_tel }}</p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_tel'), '変更') !!}</p>
            </dd>
            <dt><h2 class="user_info__list__ttl u-mt-small">LINE連携</h2></dt>
            <dd class="user_info__list__item">
                <p>{{ !empty($user->line_id) ? '連携済み' : '未連携' }}</p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_line'), '変更') !!}</p>
            </dd>
            <dt><h2 class="user_info__list__ttl">Googleアカウント連携</h2></dt>
            <dd class="user_info__list__item">
                <p>{{ !empty($user->google_id) ? '連携済み' : '未連携' }}</p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_google'), '変更') !!}</p>
            </dd>
            <dt><h2 class="user_info__list__ttl u-mt-small">ニックネーム</h2></dt>
            <dd class="user_info__list__item">
                <p>{{ $user->nickname ?? '' }}</p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_nickname'), '変更') !!}</p>
            </dd>
            <dt><h2 class="user_info__list__ttl u-mt-small">お住まいの都道府県</h2></dt>
            <dd class="user_info__list__item">
                <p>{{ config('map.prefecture')[$user->prefecture_id] }}</p>
                <p class="mypage_change__btn">{!! Tag::link(route('users.edit_prefecture'), '変更') !!}</p>
            </dd>
        </dl>
    </div><!--/contentsbox-->
    <div class="mypage__btn">
        {!! Tag::link(route('users.show'), 'マイページへ戻る') !!}
    </div>
</section>

<section class="userinfo">
</section><!--/userinfo-->
@endsection
