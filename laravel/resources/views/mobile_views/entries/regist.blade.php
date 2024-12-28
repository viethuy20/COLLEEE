@php
$base_css_type = 'signup';
@endphp
@extends('layouts.default')

@section('layout.head')
{{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css') }}

{{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js', ['type' => 'text/javascript']) }}
@endsection
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('entries.regist');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

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
            新規会員登録
        </li>
    </ol>
</section>
@endsection

@section('layout.title', 'GMOポイ活にログインしてください | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,会員登録,無料')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')

@section('url', route('sp_programs.index') )
@section('layout.content')

<div class="regist__entry">
    <div class="regist__entry__img">{{ Tag::image('/images/common/logo.png', 'はじめてのポイ活はGMOポイ活') }}</div>
    <div class="regist__entry__btn">{{ Tag::link(route('entries.index'), '無料会員登録はこちら') }}</div>
</div>

@php
$aboutUrl = route('entries.about');
if (isset($referer)) {
    $aboutUrl = route('entries.about') . '?'. http_build_query(['referer' => $referer]);
    $loginUrl = route('login') . '?'. http_build_query(['referer' => $referer]);
} else {
    $aboutUrl = route('entries.about');
    $loginUrl = route('login', ['back' => 0]);
}
@endphp
<div class="inner u-mt-20">
    <div class="regist__about">
        <div class="regist__about__bnr">
            <a href="{{ $aboutUrl }}">
                {{ Tag::image('/images/regist/regist_bnr_sp.png', 'GMOポイ活ってどんなサービス？') }}
            </a>
        </div>
    </div>
    <p class="text--15 u-text-ac u-mt-20">すでに会員の方は<a href="{{ $loginUrl }}" target="_blank" class="textlink">こちら</a>からログインしてください</p>
</div>

<!-- お買い物ピックアップ -->
@php
$program_pickup_list = \App\Content::ofSpot(\App\Content::SPOT_PROGRAM_PICKUP)
    ->orderBy('priority', 'asc')
    ->get();

// 有効なプログラムを取得
$program_id_list = [];
foreach ($program_pickup_list as $program_pickup) {
    $program_pickup_data = json_decode($program_pickup->data);
    $program_id_list[] = $program_pickup_data->program_id;
}
$program_list = collect();
if (!empty($program_id_list)) {
    $program_list = \App\Program::ofEnable()
        ->whereIn('id', $program_id_list)
        ->orderBy('id', 'desc')
        ->get();
}
@endphp
@if (!$program_list->isEmpty())
@php
$program_list = $program_list->splice(0, 6);
$accept_days_map = config('map.accept_days');
@endphp
<div class="regist__pickup u-mt-20">

    <div class="regist__pickup__ttl">
        <div class="regist__pickup__ttl__jp">お買い物ピックアップ</div>
        <div class="regist__pickup__ttl__en">PICK UP</div>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <div class="swiper-container swiper2">
        <ul class="swiper-wrapper">
            @foreach ($program_list as $program)
            <li class="swiper-slide">
                <div class="slide-img">
                    {{ Tag::image($program->affiriate->img_url, $program->title, ['width' => '100%']) }}
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="swiper-pagination2"></div>
</div>
@endif


<!-- game -->
<div class="inner">

    <h2 class="text--24 u-mt-40">無料でポイントを貯める</h2>
    <h2 class="contents__ttl u-mt-20">ゲームでゲット</h2>

    <ul class="regist__list">
        <li>
            <p class="regist__list__ttl">まいにちクイズボックス</p>
            <div class="regist__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_quiz_banner.png', 'まいにちクイズボックス') }}
            </div>
            <div class="regist__list__txt">
                毎日3回開催されるクイズ大会に参加してスタンプを集めよう！スタンプはクイズに正解すると獲得でき12個集めると最大2,000ポイントが当たる抽選に参加できるよ！
            </div>
        </li>
        <li>
            <p class="regist__list__ttl">かんたんゲームボックス</p>
            <div class="regist__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_game_banner.png', 'かんたんゲームボックス') }}
            </div>
            <div class="regist__list__txt">
                100種類以上のミニゲームをプレイしてみよう！ゲームを遊んだ結果に応じて「抽選券」を獲得し、100枚貯めると最大1,000ポイントが当たる抽選に参加できるよ！
            </div>
        </li>
        <li>
            <p class="regist__list__ttl">魁！タイプ塾</p>
            <div class="regist__list__thumb">
                {{ Tag::image('/images/sansan_logo_sp.png', '魁！タイプ塾') }}
            </div>
            <div class="regist__list__txt">
                タイプ入力で無制限にポイントゲット！？デイリーランキング入賞でボーナスも貰える！
            </div>
        </li>
    </ul>
</div>

<script type="text/javascript">
	let swiper2 = new Swiper('.swiper2', {
		loop: true,
		speed: 500,
		slidesPerView: 'auto',
		spaceBetween: 10,
		centeredSlides : true,
		initialSlide: 1,
		effect:'slide',
		// autoplay: {
		// 	delay: 4000,
		// 	disableOnInteraction: false,
		// },
		pagination: {
			el: '.swiper-pagination2',
			type: 'bullets',
			clickable: true,
		},
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev"
		}
	});
</script>
@endsection
