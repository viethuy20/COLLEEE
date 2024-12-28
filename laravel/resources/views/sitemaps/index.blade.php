<?php $base_css_type = 'sitemap'; ?>
@extends('layouts.default')

@section('layout.title', 'サイトマップ｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,サイトマップ')
@section('layout.description', 'GMOポイ活のサイトマップです。GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪')
@section('og_type', 'website')
@section('url', route('sitemaps.index') )
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('sitemaps.index');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "サイトマップ", "item": "' . $link . '"},';

@endphp
@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>
        @endforeach
        <li>
            サイトマップ
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
{{--
<ul class="breadcrumb forwidth">
    <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('website.index'), 'トップページ') !!}</li>
    <li>＞サイトマップ</li>
</ul>
--}}

<div class="contents">
    <section class="sitemap">
        <h1 class="contents__ttl">サイトマップ</h1>

        <div class="contents__box u-mt-20 text--16">
            <ul>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('website.index'), 'GMOポイ活トップページ') !!}</li>
            </ul>

            <?php
            //各ラベル用の配列
            $label_map = [1 => 'ショッピングで探す', 2 => 'サービスで探す', 3 => '獲得方法', 4 => '人気条件', 5 => '参加上限'];
            ?>

            @foreach($label_map as $type => $label_title)
            <h2>{{ $label_title }}</h2>
            <ul>
                <?php $label_list = \App\Label::where('type', '=', $type)->where('label_id', '=', '0')->pluck('name', 'id')->all(); ?>
                @foreach($label_list as $label_id => $label)
                <li><a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$label_id]]) }}">
                    <span class="icon-arrowr"></span>&nbsp;{{ $label }}</a>
                </li>
                @endforeach
            </ul>
            @endforeach

            <h2>お得なコンテンツ</h2>
            <ul>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link('/article/', 'ポイ活お得情報') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('questions.index'), 'アンケート') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('shops.index'), 'ショッピング') !!}</li>
                <!--
                <li><span class="icon-arrowr"></span>&nbsp;<a href="#">キャンペーン情報</a></li>
                -->
                <!--
                <li><span class="icon-arrowr"></span>&nbsp;<a href="#">人気広告ランキング</a></li>
                -->
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('fancrew.pages'), 'お店でお得') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('credit_cards.list'), 'カード比較') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('sp_programs.index'), '毎日ゲット', null, null, false) !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('features.index'), '特集一覧') !!}</li>
            </ul>

            <h2>紹介</h2>
            <ul>

                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('friends.index'), 'お友達紹介', null, null, false) !!}</li>

            </ul>

            <h2>ポイント交換</h2>
            <ul>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('exchanges.index'), '交換') !!}</li>
            </ul>

            <h2>GMOポイ活について</h2>
            <ul>

                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(config('url.gmo_nikko'), '運営会社', ['target' => '_blank', 'class' => 'lnk_external']) !!}</span></li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('abouts.membership_contract'), '利用規約') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(config('url.privacy_policy'), '個人情報保護方針', ['target' => '_blank', 'class' => 'lnk_external']) !!}</span></li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('abouts.member_rank'), '会員ランク特典・条件') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('sitemaps.index'), 'サイトマップ') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link('/help/', 'ヘルプセンター') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせ') !!}</li>

            </ul>

            @if (Auth::check())
            <h2>会員メニュー</h2>
            <ul>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('users.show'), 'マイページ') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('users.point_list'), '獲得履歴') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('users.exchange_list'), '交換履歴') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('users.edit'), '基本情報変更') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('users.edit_email_setting'), 'メールマガジン受信設定') !!}</li>
                <li><span class="icon-arrowr"></span>&nbsp;{!! Tag::link(route('withdrawals.index'), '退会') !!}</li>
            </ul>
            @endif
        </div><!--/contentsbox-->
    </section><!--/sitemap-->
</div><!--/contents-->
@endsection
