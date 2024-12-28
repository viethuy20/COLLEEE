@php
$base_css_type = 'mypage';
$user = Auth::user();
@endphp
@extends('layouts.plane')

@section('layout.sidebar')

<h2 class="contents__ttl orange">マイページメニュー</h2>
<h2 class="contents__ttl u-mt-xsmall">獲得/交換</h2>
<ul class="sidebar__list">
    <li>{{ Tag::link(route('users.reward_list'), '獲得予定') }}</li>
    <li>{{ Tag::link(route('users.point_list'), '獲得履歴') }}</li>
    <li>{{ Tag::link(route('users.exchange_list'), '交換履歴') }}</li>
</ul>
<h2 class="contents__ttl">お気に入り</h2>
<ul class="sidebar__list">
    <li>{{ Tag::link(route('users.program_list'), 'お気に入りに追加した広告') }}</li>
    <li>{{ Tag::link(route('users.recipe_list'), 'クリップしたポイ活お得情報') }}</li>
</ul>
<h2 class="contents__ttl">会員情報</h2>
<ul class="sidebar__list">
    <li>{{ Tag::link(route('users.edit_email_setting'), 'メールマガジン受信設定') }}</li>
    <li>{{ Tag::link(route('users.edit'), '基本情報変更') }}</li>
    <li>{{ Tag::link(route('reviews.reviewer', ['user' => $user]), '投稿済みの口コミ一覧') }}</li>
    <li>{{ Tag::link(route('withdrawals.index'), '退会') }}</li>
</ul>
<ul class="sidebar__bnr">
    <li>{{ Tag::link(route('beginners'), Tag::image('/images/common/bnr_howto.png', 'GMOポイ活の使い方'), null, false, false) }}</li>
</ul>
@endsection
