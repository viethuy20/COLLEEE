@php
$base_css_type = 'top';
@endphp
@extends('layouts.default')

@section('og_type', 'website')

@section('layout.head')

{{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css') }}
{!! Tag::style('/css/sp_common_20240613.css') !!}
{!! Tag::style('/css/sp_modal.css') !!}
{!! Tag::style('/css/sp_popup_ad_modal.css') !!}
{{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js', ['type' => 'text/javascript']) }}


<script type="text/javascript">
$(function(){
    $('.txt_ureview').collapser({
        mode: 'chars',
        truncate: 70,
        showText: '続きを読む',
        hideText: '閉じる'
    });
});
</script>
@endsection

@section('layout.content.header')
@if (!Auth::check())
@include('mobile_views.fixed_btn')

<section class="top__intro">
	<div class="top__intro__inner">
		<div class="top__intro__ttl">
			<p>学生・会社員・主婦・シニアまで</p>
			<h1><img src="/images/top/intro/intro_heading_sp.svg" width="340" height="100" alt="はじめてのポイ活はGMOポイ活"></h1>
		</div>
		<ul class="top__intro__emblems">
			<li><img src="/images/top/intro/intro__emblem_1.svg" width="134" height="108" alt="利用・登録料 ずーっと0円"></li>
			<li><img src="/images/top/intro/intro__emblem_2.svg" width="134" height="108" alt="運営実績 20年超えの老舗サイト"></li>
			<li><img src="/images/top/intro/intro__emblem_3.svg" width="134" height="108" alt="運営会社 GMOインターネットグループ会社"></li>
		</ul>
		<div class="top__intro__cv__wrap">
			@php
                $cv = '';
            @endphp
            @include('mobile_views.inc.cv-btn',['cv' => $cv])
			<div class="top__intro__deco">
				<div class="charas">
					<figure class="chara-l"><img src="/images/top/intro/deco_chara_2.png" width="150" height="190" alt=""></figure>
					<figure class="chara-r"><img src="/images/top/intro/deco_chara_1.png" width="150" height="190" alt=""></figure>
				</div>
				<div class="coins">
					<figure class="coin-l">
						<picture>
							<source media="(min-width: 48em)" sizes="100vw" srcset="/images/top/intro/deco_coin_2_pc.png 60w">
							<source media="(max-width: 48em)" sizes="100vw" srcset="/images/top/intro/deco_coin_2_sp.png 215w">
							<img alt="" width="120" height="270" src="/images/top/intro/deco_coin_2_sp.png">
						</picture>
					</figure>
					<figure class="coin-r">
						<picture>
							<source media="(min-width: 48em)" sizes="100vw" srcset="/images/top/intro/deco_coin_1_pc.png 60w">
							<source media="(max-width: 48em)" sizes="100vw" srcset="/images/top/intro/deco_coin_1_sp.png 215w">
							<img alt="" width="120" height="270" src="/images/top/intro/deco_coin_1_sp.png">
						</picture>
					</figure>
				</div>
			</div>
		</div>
		<div class="top__intro__feature">
			<ul class="top__intro__feature__bubbles">
				<li>
					<i><img src="/images/top/intro/ico_baggage-delay.svg" alt=""></i>
					<p>旅行予約<span>で</span></p>
				</li>
				<li>
					<i><img src="/images/top/intro/ico_iphone.svg" alt=""></i>
					<p>アプリDL<span>で</span></p>
				</li>
				<li>
					<i><img src="/images/top/intro/ico_bank-card-one.svg" alt=""></i>
					<p>クレカ発行<span>で</span></p>
				</li>
				<li>
					<i><img src="/images/top/intro/ico_shopping.svg" alt=""></i>
					<p>お買い物<span>で</span></p>
				</li>
				<li>
					<i><img src="/images/top/intro/ico_clipboard.svg" alt=""></i>
					<p>アンケート<span>で</span></p>
				</li>
			</ul>
			<p class="top__intro__feature__txt">貯めたポイントは<strong>1ポイント1円分</strong>で<br><strong>現金</strong>・<strong>電子マネー</strong>などに交換！</p>
			<div class="top__intro__feature__exchanges swiper-container js-intro-ex-swiper1">
				<ul class="swiper-wrapper">
					<li class="swiper-slide"><img src="/images/exchanges/img_bank.png" alt="現金"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_jp-bank.png" alt="ゆうちょ銀行"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_smbc.png" alt="三井住友銀行"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_mizuho.png" alt="みずほ銀行"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_mufg.png" alt="三菱UFJ銀行"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_gmo-aozora.png" alt="GMOあおぞらネット銀行"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_rakuten-bank.png" alt="楽天銀行"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_amazon_txt.png" alt="Amazonギフトカード"></li>
                    <li class="swiper-slide"><img src="/images/exchanges/img_apple_txt.png" alt="Apple Gift Card"></li>
                    <li class="swiper-slide"><img src="/images/exchanges/img_ponta.png" alt="Pontaポイント"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_pssticket.png" alt="プレイステーション ストアチケット"></li>
                    
				</ul>
			</div>
			<div class="top__intro__feature__exchanges swiper-container js-intro-ex-swiper2">
				<ul class="swiper-wrapper">
					<li class="swiper-slide"><img src="/images/exchanges/img_pex.png" alt="PeXポイントギフト"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_money.png" alt="ドットマネー"></li>
                    <li class="swiper-slide"><img src="/images/exchanges/img_paypay.png" alt="PayPayポイント"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_edy.png" alt="楽天Edy"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_google.png" alt="Google Play ギフトコード"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_waon.png" alt="WAONポイント"></li>
					<li class="swiper-slide"><img src="/images/exchanges/img_dpoint.png" alt="d Point"></li>
                    <li class="swiper-slide"><img src="/images/exchanges/img_digital-gift.png" alt="デジタルギフト" /></li>
                    <li class="swiper-slide"><img src="/images/exchanges/img_jal.png" alt="JALマイル" /></li>
                    <li class="swiper-slide"><img src="/images/exchanges/img_paypal.png" alt="PayPal" /></li>
                    {{-- <li class="swiper-slide"><img src="/images/exchanges/img_linepay.png" alt="LINE Pay"></li> --}}
                    <li class="swiper-slide"><img src="/images/exchanges/img_kdol.png" alt="KDOL" /></li>
				</ul>
			</div>
		</div>
		<figure class="top__intro__cloud"></figure>
	</div>
</section>

@endif

<section class="contents__wrap">
	<div class="search-box">
        {!! Tag::formOpen(['url' => route('programs.list'), 'method' => 'get', 'id' => 'form1', 'name' => 'form1', 'class' => 'search-box__form']) !!}
        @csrf    
        {!! Tag::formText('keywords', '', ['class' => 'search-box__input', 'placeholder' => 'キーワードでさがす']) !!}
            {!! Tag::formButton('', ['type' => 'submit', 'class' => 'search-box__submit']) !!}
        {!! Tag::formClose() !!}
	</div>
</section>

@php
$top_content_list = \App\Content::ofSpot(\App\Content::SPOT_SP_TOP)
    ->limit(5)
    ->get();

// get popup ad
$priority = \App\PopupAds::ofEnableDevice()->min('priority');
$popup_ads = \App\PopupAds::ofEnableDevice()
    ->where('priority', $priority)
    ->inRandomOrder()
    ->first();
$program_details = $popup_ads ? \App\Program::ofEnableDevice()
    ->where('id', $popup_ads->program_id)->first() : null;

@endphp
@if (!$top_content_list->isEmpty())
<section class="top__fv" id="js-fixed-point">
	<div class="swiper-container swiper1">
        <ul class="swiper-wrapper">
            @foreach ($top_content_list as $content)
                @php
                $content_data = json_decode($content->data);
                @endphp
                <li class="swiper-slide">
                    <div class="slide-img">
                        {{ Tag::link($content_data->url, Tag::image($content_data->img_url, $content->title), null, null, false) }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="swiper-pagination1"></div>
</section>
<!-- start mini banner  -->
@php
    $top_mini_banner_list = \App\Content::ofSpot(\App\Content::SPOT_MINI_BANNER_SP)
        ->orderBy('priority', 'asc')
        ->orderBy('start_at', 'desc')
        ->limit(8)
        ->get();
@endphp

@if (!$top_mini_banner_list->isEmpty())
<section class="mini-banner">
    <div class="swiper-container swiper3">
		<ul class="swiper-wrapper">
            @foreach ($top_mini_banner_list as $content)
                    <?php $content_data = json_decode($content->data); ?>
			<li class="swiper-slide">
				<div class="slide-img">
                    {!! Tag::link($content_data->url, Tag::image($content_data->img_url, $content->title), null, null, false) !!}
				</div>
			</li>
            @endforeach
		</ul>
	</div>
</section>
@endif
<!-- end mini banner -->
@endif<!-- /.top__fv -->

@endsection

@section('layout.content')

@php
$accept_days_map = config('map.accept_days');
$now = \Carbon\Carbon::now();
@endphp

@php
$categoryColletsions = [
    [
        "ttl" => "新着",
        "image" => "ico_hd_cat_new.svg",
        "link" => \App\Search\ProgramCondition::getStaticListUrl((object) ['sort' => 3]),
    ],
    [
        "ttl" => "高還元",
        "image" => "ico_hd_cat_sale.svg",
        "link" => \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['高還元']]),
    ],
    [
        "ttl" => "無料",
        "image" => "ico_hd_cat_foc.svg",
        "link" => \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [80]]),
    ],
    [
        "ttl" => "即日還元",
        "image" => "ico_hd_cat_soku.svg",
        "link" => \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [78]]),
    ],
    [
        "ttl" => "アンケート",
        "image" => "ico_hd_cat_pen.svg",
        "link" => route('questions.index'),
    ],
    [
        "ttl" => "レシ活",
        "image" => "ico_hd_receipt_color.svg",
        "link" => route('receipt.list'),
    ],
    [
        "ttl" => "ポイ活<br>お得情報",
        "image" => "ico_hd_cat_book.svg",
        "link" => '/article',
    ],
    [
        "ttl" => "ポイント<br><span>（貯まる・使えるお店）</span>",
        "image" => "ico_hd_cat_point.svg",
        "link" => "/article/category/pointlist/",
    ],
    [
        "ttl" => "キャンペーン<br>情報",
        "image" => "ico_hd_cat_camp.svg",
        "link" => "/article/category/campaignlist/",
    ],
];
@endphp


@php
$rakuten_program_list = \App\Program::ofList()
                    ->ofKeyword(["楽天サービス"])
                    ->take(8)
                    ->get();

$yahoo_program_list = \App\Program::ofList()
                    ->ofKeyword(["Yahoo!サービス"])
                    ->take(8)
                    ->get();

$docomo_program_list = \App\Program::ofList()
                    ->ofKeyword(["docomoサービス"])
                    ->take(8)
                    ->get();

$au_program_list = \App\Program::ofList()
                ->ofKeyword(["auサービス"])
                ->take(8)
                ->get();

$ranking_banner_list = \App\Content::ofSpot(\App\Content::SPOT_LOWER_RANKING_BANNER)
    ->orderBy('priority', 'asc')
    ->orderBy('start_at', 'desc')
    ->take(2)
    ->get();
@endphp
<nav class="top__search">
    <div class="inner">
        <div class="top__search__inner">

            <!-- サービスから検索 -->
            <ul>
                <li>
                    <div class="top__search__service">
                        <!-- 検索タブ -->
                        <ul class="top__search__service__tab">
                            @if (!$rakuten_program_list->isEmpty())
                            <li class="active"><a href="#search_tab_rakuten">{{ Tag::image("/images/common/ico_hd_serv_rakuten.png", "楽天") }}</a></li>
                            @endif
                            @if (!$yahoo_program_list->isEmpty())
                            <li><a href="#search_tab_yahoo">{{ Tag::image("/images/common/ico_hd_serv_yahoo.png", "Yahoo!") }}</a></li>
                            @endif
                            @if (!$docomo_program_list->isEmpty())
                            <li><a href="#search_tab_docomo">{{ Tag::image("/images/common/ico_hd_serv_docomo.png", "docomo") }}</a></li>
                            @endif
                            @if (!$au_program_list->isEmpty())
                            <li><a href="#search_tab_au">{{ Tag::image("/images/common/ico_hd_serv_au.png", "au") }}</a></li>
                            @endif
                        </ul>

                        <!-- 楽天サービス一覧 -->
                        @if (!$rakuten_program_list->isEmpty())
                        <div class="top__search__service__inner active" id="search_tab_rakuten">
                            <div class="top__search__service__inner__ttl">
                                <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['楽天サービス']]) }}">
                                    <i>{{ Tag::image("/images/common/logo_small_rakuten.png") }}</i>楽天サービス一覧
                                </a>
                            </div>
                            <ul class="top__search__service__list">
                                @foreach ($rakuten_program_list as $rakuten_program)
                                @php
                                // アフィリエイト情報
                                $affiriate = $rakuten_program->affiriate;
                                @endphp
                                <li>
                                    <a href="{{ route('programs.show', ['program'=> $rakuten_program]) }}">
                                        <p class="top__search__service__list__ttl">{{ $rakuten_program->title }}</p>
                                        <div class="top__search__service__list__thumb">{{ Tag::image($affiriate->img_url, $rakuten_program->title) }}</div>
                                        @php
                                        $point = $rakuten_program->point;
                                        $point_class = "top__search__service__list__point";
                                        if ($point->fee_type == 2)
                                            $point_class = $point_class. " percent_point";
                                        @endphp
                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="top__search__service__btn">
                                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['楽天サービス']]), 'その他の楽天サービス') }}
                            </div>
                        </div><!-- /.top__search__service__inner rakuten -->
                        @endif

                        <!-- Yahoo!サービス一覧 -->
                        @if (!$yahoo_program_list->isEmpty())
                        <div class="top__search__service__inner" id="search_tab_yahoo">
                            <div class="top__search__service__inner__ttl">
                                <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['Yahoo!サービス']]) }}"><i>{{ Tag::image("/images/common/logo_small_yahoo.png") }}</i>Yahoo!サービス一覧</a>
                            </div>
                            <ul class="top__search__service__list">
                                @foreach ($yahoo_program_list as $yahoo_program)
                                @php
                                // アフィリエイト情報
                                $affiriate = $yahoo_program->affiriate;
                                @endphp
                                <li>
                                    <a href="{{ route('programs.show', ['program'=> $yahoo_program]) }}">
                                        <p class="top__search__service__list__ttl">{{ $yahoo_program->title }}</p>
                                        <div class="top__search__service__list__thumb">{{ Tag::image($affiriate->img_url, $yahoo_program->title) }}</div>
                                        @php
                                        $point = $yahoo_program->point;
                                        $point_class = "top__search__service__list__point";
                                        if ($point->fee_type == 2)
                                            $point_class = $point_class. " percent_point";
                                        @endphp
                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="top__search__service__btn">
                                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['Yahoo!サービス']]), 'その他のYahoo!サービス') }}
                            </div>
                        </div><!-- /.top__search__service__inner yahoo -->
                        @endif

                        <!-- docomoサービス一覧 -->
                        @if (!$docomo_program_list->isEmpty())
                        <div class="top__search__service__inner" id="search_tab_docomo">
                            <div class="top__search__service__inner__ttl">
                                <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['docomoサービス']]) }}"><i>{{ Tag::image("/images/common/logo_small_docomo.png") }}</i>docomoサービス一覧</a>
                            </div>
                            <ul class="top__search__service__list">
                                @foreach ($docomo_program_list as $docomo_program)
                                @php
                                // アフィリエイト情報
                                $affiriate = $docomo_program->affiriate;
                                @endphp
                                <li>
                                    <a href="{{ route('programs.show', ['program'=> $docomo_program]) }}">
                                        <p class="top__search__service__list__ttl">{{ $docomo_program->title }}</p>
                                        <div class="top__search__service__list__thumb">{{ Tag::image($affiriate->img_url, $docomo_program->title) }}</div>
                                        @php
                                        $point = $docomo_program->point;
                                        $point_class = "top__search__service__list__point";
                                        if ($point->fee_type == 2)
                                            $point_class = $point_class. " percent_point";
                                        @endphp
                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="top__search__service__btn">
                                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['docomoサービス']]), 'その他のdocomoサービス') }}
                            </div>
                        </div><!-- /.top__search__service__inner docomo -->
                        @endif

                        <!-- auサービス一覧 -->
                        @if (!$au_program_list->isEmpty())
                        <div class="top__search__service__inner" id="search_tab_au">
                            <div class="top__search__service__inner__ttl">
                                <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['auサービス']]) }}"><i>{{ Tag::image("/images/common/logo_small_au.png") }}</i>auサービス一覧</a>
                            </div>
                            <ul class="top__search__service__list">
                                @foreach ($au_program_list as $au_program)
                                @php
                                // アフィリエイト情報
                                $affiriate = $au_program->affiriate;
                                @endphp
                                <li>
                                    <a href="{{ route('programs.show', ['program'=> $au_program]) }}">
                                        <p class="top__search__service__list__ttl">{{ $au_program->title }}</p>
                                        <div class="top__search__service__list__thumb">{{ Tag::image($affiriate->img_url, $au_program->title) }}</div>
                                        @php
                                        $point = $au_program->point;
                                        $point_class = "top__search__service__list__point";
                                        if ($point->fee_type == 2)
                                            $point_class =  $point_class. " percent_point";
                                        @endphp
                                        <p class="{{ $point_class }}">{{ $au_program->point->fee_label }}P</p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="top__search__service__btn">
                                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['auサービス']]), 'その他のauサービス') }}
                            </div>
                        </div><!-- /.top__search__service__inner au -->
                        @endif
                    </div><!-- /.top__search__service -->
                </li>

                <!-- カテゴリーから検索 -->
                <li>
                    <div class="top__search__category">
                        <ul class="top__search__category__list">
                            @foreach ($categoryColletsions as $categoryItem)
                            <li>
                                <a href="{!! $categoryItem['link'] !!}">
                                    <div class="top__search__category__list__ico">{{ Tag::image('/images/common/'. $categoryItem['image']) }}</div>
                                    <p class="top__search__category__list__ttl">{!! $categoryItem['ttl'] !!}</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div><!-- End カテゴリーから検索 -->
                </li>
            </ul>
        </div><!-- /.top__search__inner -->
    </div><!-- /.inner -->
</nav><!-- /.top__search -->

<!-- 高還元セール -->
@php
$today_only_time_sale_program = \App\Program::ofTimeSale(true)
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->first();

$time_sale_query = \App\Program::ofTimeSale()
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->take(6);
if (isset($today_only_time_sale_program->id)) {
    $time_sale_query = $time_sale_query
        ->where('id', '<>', $today_only_time_sale_program->id);
}
$time_sale_program_list = $time_sale_query->get();

$recommend_program_list = \App\Program::ofList()
    ->ofSort(4)
    ->take(3)
    ->get();

$feature_category_list = \App\Content::ofSpot(\App\Content::SPOT_FEATURE_CATEGORY)
    ->orderBy('id', 'asc')
    ->take(4)
    ->get();
@endphp
@if (!$time_sale_program_list->isEmpty())
<div class="top__sale">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">高還元セール</span><a class="top__ttl__en__link" href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['タイムセール']]) }}">SALE</a></h2>
            </div>
            <div class="top__ttl__r">
                <p class="top__ttl__tag">期間限定UP中！</p>
            </div>
        </div>
    </div>
    <ul class="top__list__flex">
        @foreach($time_sale_program_list as $program)
        @php
        // アフィリエイト情報
        $affiriate = $program->affiriate;
        // 現在のポイント
        $point = $program->point;
        @endphp
        <li>
            <a href="{{ route('programs.show', ['program'=> $program]) }}">
                <div class="top__list__flex__thumb">{{ Tag::image($affiriate->img_url, $program->title, ['loading' => 'lazy']) }}</div>
                <p class="top__list__flex__countdown counter" timestamp="{{ $point->stop_at->timestamp }}">
                    残り<span class="countDownDay"></span>日<span class="countDownTime"></span>
                </p>
                <p class="top__list__flex__ttl">{{ $program->title }}</p>
                <p class="top__list__flex__txt2">{!! $program->description !!}</p>
                <p class="top__list__flex__linethrough">{{ $point->previous_point->fee_label }}p</p>
                <p class="top__list__flex__point arrow">{{ $point->fee_label }}P</p>
            </a>
        </li>
        @endforeach
    </ul>
</div><!-- /.top__sale -->
@endif

<!-- アプリでポイントゲット -->
@php
$_rankCollections = array();
$rankCollections = [
    'rank_credit' => array(),
    'rank_all' => array(),
    'rank_app' => array(),
    'rank_service' => array(),
    'rank_travel' => array(),
];

$testOtherCollections = [
    'ttl' => 'ここに~名が入りますここにアプリ名が入ります',
    'image' => "/images/top/img_app.png",
    'point' => '100',
    'description' => 'ここに条件が入りますここに条件が入りますここに条件が入りますここに条件が入ります',
];

for ($i = 0; $i <= 4; $i++) {
    $_rankCollections[] = $testOtherCollections;
}

foreach ($rankCollections as $category => $dummy) {
    $rankCollections[$category] = $_rankCollections;
}
@endphp

@php
$app_program_list = \App\Program::ofList()
    ->ofLabel([108])
    ->orderBy('id', 'desc')
    ->take(8)
    ->get();
@endphp
<div class="top__app">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">アプリでポイントゲット</span>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [108]]), 'APP', ['class' => 'top__ttl__en__link']) }}
                </h2>
            </div>
        </div>
    </div>
    <ul class="top__app__list">
        @foreach ($app_program_list as $app_program)
        @php
        // アフィリエイト情報
        $affiriate = $app_program->affiriate;
        @endphp
        <li>
            <a href="{{ route('programs.show', ['program'=> $app_program]) }}">
                <p class="top__app__list__ttl">{{ $app_program->title }}</p>
                <div class="top__app__list__thumb">{{ Tag::image($affiriate->img_url, $app_program->title, ['loading' => 'lazy']) }}</div>
                @php
                $point = $app_program->point;
                $point_class = "top__app__list__point";
                if ($point->fee_type == 2)
                    $point_class = $point_class. " percent_point";
                @endphp
                <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
            </a>
        </li>
        @endforeach
    </ul>

    <!-- OWバナー -->
    <div class="top__app__bnr">
        <div class="inner u-mt-20">
            {{ Tag::link(route('skyflag.about'), Tag::image("/images/common/bnr_skyflag_ow.png", '', ['loading' => 'lazy']), null, null, false) }}
        </div>
    </div>
</div><!-- /.top__app -->

<!-- ランキング -->
@php
$credit_labels = [125];
$app_labels = [136];
$service_labels = [122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136];
$travel_labels = [122];

$credit_rank_programs = \App\Program::ofList()
    ->whereIn('programs.id', function ($query) use ($credit_labels) {
        $query->select('program_labels.program_id')
            ->from('program_labels')
            ->where('program_labels.status', '=', 0)
            ->whereIn('program_labels.label_id', function ($query) use ($credit_labels) {
                $query->select('labels.id')
                    ->from('labels')
                    ->orWhere(function ($query) use ($credit_labels) {
                        $query->whereIn('labels.label_id', $credit_labels)
                                ->orWhere(function ($query) use ($credit_labels) {
                                    $query->whereIn('labels.id', $credit_labels);
                                });
                    });
            });
    })
    ->ofRank()
    ->take(5)
    ->get();

$all_rank_programs = \App\Program::ofList()
    ->ofRank()
    ->take(5)
    ->get();

$app_rank_programs = \App\Program::ofList()
    ->whereIn('programs.id', function ($query) use ($app_labels) {
        $query->select('program_labels.program_id')
            ->from('program_labels')
            ->where('program_labels.status', '=', 0)
            ->whereIn('program_labels.label_id', function ($query) use ($app_labels) {
                $query->select('labels.id')
                    ->from('labels')
                    ->orWhere(function ($query) use ($app_labels) {
                        $query->whereIn('labels.label_id', $app_labels)
                                ->orWhere(function ($query) use ($app_labels) {
                                    $query->whereIn('labels.id', $app_labels);
                                });
                    });
            });
    })
    ->ofRank()
    ->take(5)
    ->get();

$service_rank_programs = \App\Program::ofList()
    ->whereIn('programs.id', function ($query) use ($service_labels) {
        $query->select('program_labels.program_id')
            ->from('program_labels')
            ->where('program_labels.status', '=', 0)
            ->whereIn('program_labels.label_id', function ($query) use ($service_labels) {
                $query->select('labels.id')
                    ->from('labels')
                    ->orWhere(function ($query) use ($service_labels) {
                        $query->whereIn('labels.label_id', $service_labels)
                                ->orWhere(function ($query) use ($service_labels) {
                                    $query->whereIn('labels.id', $service_labels);
                                });
                    });
            });
    })
    ->ofRank()
    ->take(5)
    ->get();

$travel_rank_programs = \App\Program::ofList()
    ->whereIn('programs.id', function ($query) use ($travel_labels) {
        $query->select('program_labels.program_id')
            ->from('program_labels')
            ->where('program_labels.status', '=', 0)
            ->whereIn('program_labels.label_id', function ($query) use ($travel_labels) {
                $query->select('labels.id')
                    ->from('labels')
                    ->orWhere(function ($query) use ($travel_labels) {
                        $query->whereIn('labels.label_id', $travel_labels)
                                ->orWhere(function ($query) use ($travel_labels) {
                                    $query->whereIn('labels.id', $travel_labels);
                                });
                    });
            });
    })
    ->ofRank()
    ->take(5)
    ->get();

$rank_programs = [
    'rank_credit' => [
        'title' => 'クレカ',
        'program' => $credit_rank_programs,
    ],
    'rank_all' => [
        'title' => '総合',
        'program' => $all_rank_programs,
    ],
    'rank_app' => [
        'title' => 'アプリ',
        'program' => $app_rank_programs,
    ],
    'rank_service' => [
        'title' => 'サービス',
        'program' => $service_rank_programs,
    ],
    'rank_travel' => [
        'title' => '旅行',
        'program' => $travel_rank_programs,
    ],
];

$rank_exists = array_filter($rank_programs, function ($rank_program) {
        return !$rank_program['program']->isEmpty();
});
@endphp
@if (in_array(true, $rank_exists))
<div class="top__ranking__wrap">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">ランキング</span>RANKING</h2>
            </div>
        </div>
        <ul class="top__ranking__tab">
            @php
            $rank_tab_class = 'active';
            @endphp

            @foreach ($rank_programs as $key => $rank_program)
            @if ($rank_program['program']->isNotEmpty())
            <li class='{{ $rank_tab_class }}'><a href={{ '#'. $key }}>{{ $rank_program['title'] }}</a></li>
            @php
            $rank_tab_class = '';
            @endphp
            @endif
            @endforeach
        </ul>

        @php
        $rank_list_class = 'top__ranking active';
        @endphp
        @foreach ($rank_programs as $key => $rank_program)
        @if ($rank_program['program']->isNotEmpty())
        <div class='{{ $rank_list_class }}' id={{ $key }}>
            <ul class="top__ranking__list">
                @foreach ($rank_program['program'] as $idx => $program)
                <li>
                    <a href="{{ route('programs.show', ['program' => $program]) }}">
                        <div class="top__ranking__list__num">{{ Tag::image("/images/top/ico_rank".($idx+1).".svg", '', ['loading' => 'lazy']) }}</div>
                        <div class="top__ranking__list__thumb">{{ Tag::image($program->affiriate->img_url, $program->title, ['loading' => 'lazy']) }}</div>
                        <div class="top__ranking__list__contents">
                            <p class="top__ranking__list__contents__ttl">{{ $program->title }}</p>
                            <p class="top__ranking__list__contents__txt">{{ $program->fee_condition }}</p>
                            <p class="top__ranking__list__contents__point">{{ $program->point->fee_label }}P</p>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @php
        $rank_list_class = 'top__ranking';
        @endphp
        @endif
        @endforeach
    </div>
</div><!-- /.top__ranking__wrap -->
@endif

<!-- low-ranking-banner -->
@if (!$recommend_program_list->isEmpty())
<ul class="low-ranking-banner">
    @foreach ($ranking_banner_list as $content)
        @php
            $content_data = json_decode($content->data);
        @endphp
    <li class="img1">
        {{ Tag::link($content_data->url, Tag::image($content_data->img_url, $content->title, ['loading' => 'lazy']), null, null, false) }}
    </li>
    @endforeach
</ul>

@endif

@php
use App\External\Logrecoai;
use App\External\History;
use App\External\RecommendProgram;
@endphp


<!-- スタッフおすすめ -->
@php
$recommend_program = new RecommendProgram();
$num = 6;
$login_recommend_program_list = $recommend_program->getRecommendPrograms($num,'sp');
@endphp
@if ($login_recommend_program_list)
<div class="top__shopping">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">スタッフおすすめ</span>RECOMMENDED</h2>
            </div>
        </div>
        <ul class="top__shopping__list2">
            @foreach ($login_recommend_program_list as $recommend_program)
            @php
            // ポイント
            $point = $recommend_program->point;
            // アフィリエイト
            $affiriate = $recommend_program->affiriate;
            // logrecoai_item_id
            $logrecoai_item_id = 'pg' . $recommend_program->id;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $recommend_program]) }}">
                    <div class="top__shopping__list__thumb">{!! Tag::image($affiriate->img_url, $recommend_program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__shopping__list__ttl">{{ $recommend_program->title }}</p>
                    <p class="top__shopping__list__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div><!-- /.top__shopping -->
@endif

@if (Auth::check())
<!-- 最近チェックした広告 -->
@php
$history_data = History::getProgramHistoriesData(6);
$history_program_list_top_bottom = [];
if ($history_data) {
    $history_program_list_top_bottom = $history_data;
}
@endphp
@if (!empty($history_program_list_top_bottom))
<div class="top__shopping">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">最近チェックした広告</span>HISTORY</h2>
            </div>
        </div>
        <ul class="top__shopping__list2">
            @foreach ($history_program_list_top_bottom as $history_program)
            @php
            // ポイント
            $point = $history_program->point;
            // アフィリエイト
            $affiriate = $history_program->affiriate;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $history_program]) }}">
                    <div class="top__shopping__list__thumb">{!! Tag::image($affiriate->img_url, $history_program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__shopping__list__ttl">{{ $history_program->title }}</p>
                    <p class="top__shopping__list__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div><!-- /.top__shopping -->
@endif
@endif

<!-- 特集・キャンペーン -->
@if (!$feature_category_list->isEmpty())
<div class="top__campaign">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">特集・キャンペーン</span>CAMPAIGN</h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(route('features.index'), 'もっと見る', ['class' => 'top__ttl__link']) }}
            </div>
        </div>
        <ul class="top__campaign__list">
            @foreach($feature_category_list as $category)
            @php
            $category_data = $category->json_data;
            @endphp
            <li>
                <a href="{{ route('features.show', ['feature_id' => $category->id]) }}">
                    <div class="">{{ Tag::image($category_data->banner_img_url, $category_data->banner_img_alt, ['loading' => 'lazy']) }}</div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div><!-- /.top__campaign -->
@endif

<!-- 新着広告 -->
<div class="top__news">
    <div class="inner">
        <div class="top__ttl u-mt-remove">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">新着広告</span>NEW</h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(route('programs.list', ['sort' => 3]), 'もっと見る', ['class' => 'top__ttl__link']) }}
            </div>
        </div>
        <ul class="top__list__flex top__news__list">
            @php
            // 新着情報取得
            $new_program_list = \App\Program::ofList()
                ->ofSort(\App\Program::NEW_SORT)
                ->take(5)
                ->get();
            @endphp
            @foreach($new_program_list as $program)
            @php
            // アフィリエイト情報
            $affiriate = $program->affiriate;
            @endphp

            <li>
                <a href="{{ route('programs.show', ['program'=> $program]) }}">
                    <div class="top__list__flex__thumb">{{ Tag::image($affiriate->img_url, $program->title, ['loading' => 'lazy']) }}</div>
                    <p class="top__list__flex__ttl">{{ $program->title }}</p>
                    <p class="top__list__flex__txt1">{{ $program->fee_condition }}</p>
                    <p class="top__list__flex__point">{{ $program->point->fee_label }}P</p>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div><!-- /.top__news -->

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
    $program_list = \App\Program::ofList()
        ->whereIn('id', $program_id_list)
        ->orderBy('id', 'desc')->limit(12)
        ->get();
}
@endphp
@if (!$program_list->isEmpty())
    <div class="top__pickup">
        <div class="inner">
            <div class="top__pickup__ttl">
                <h2 class="top__pickup__ttl__en"><span class="top__ttl__jp">お買い物ピックアップ</span>PICK UP</h2>
            </div>
            <ul class="top__list__pick_up">
                @foreach ($program_list as $program)
                    @php
                        // ポイント
                        $point = $program->point;
                        // アフィリエイト
                        $affiriate = $program->affiriate;
                    @endphp
                    <li>
                        <a href="{{ route('programs.show', ['program' => $program]) }}">
                            <div class="top__list__pick_up__thumb">{!! Tag::image($affiriate->img_url, $program->title, ['loading' => 'lazy']) !!}</div>
                            <p class="pick_up_pick_up_ttl">{{ $program->title }}</p>
                            <p class="top__list__pick_up__point">
                                {{ $point->fee_label }}P
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div><!-- /.top__pickup -->
@endif

<!-- おすすめショッピング -->
@php
$standard_program_list = \App\Program::ofList()
            ->ofLabel([82])
            ->ofSort(4)
            ->take(6)
            ->get();
@endphp
@if (!empty($standard_program_list))
<div class="top__shopping">
    <div class="inner">
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en"><span class="top__ttl__jp">おすすめショッピング</span>SHOPPING</h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [82]]), 'もっと見る', ['class' => 'top__ttl__link']) }}
            </div>
        </div>
        <ul class="top__shopping__list">
            @foreach ($standard_program_list as $program)
            @php
            // ポイント
            $point = $program->point;
            // アフィリエイト
            $affiriate = $program->affiriate;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    <div class="top__shopping__list__thumb">{!! Tag::image($affiriate->img_url, $program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__shopping__list__ttl">{{ $program->title }}</p>
                    <p class="top__shopping__list__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div><!-- /.top__shopping -->
@endif

@if (session('show_popup') && !empty($program_details) && Auth::check()) 
<div class="js-modal-open" data-auto-open="true" data-is-time-sale="{{ $program_details->point->time_sale ?? 0 }}"></div>
            <!-- modal include -->
@include('inc.modal-ad' , ['ads' => $program_details, 'title' =>$popup_ads->title])

@endif

<!-- ポイ活お得情報 -->
@php
    $new_recipe_data = \App\External\Recipe::getNewRecipeList();
@endphp
@if (isset($new_recipe_data) && $new_recipe_data->result->status && !empty($new_recipe_data->items))
    @php
        $recipe_list = collect($new_recipe_data->items)->sortByDesc('update')->take(5)->values()->all();
    @endphp

    <div class="top__article">
        <div class="inner">
            <div class="top__ttl" id="top__article">
                <div class="top__ttl__l">
                    <h2 class="top__ttl__en"><span class="top__ttl__jp"></span>ポイ活お得情報</h2>
                </div>
                <div class="top__ttl__r">
                    {{ Tag::link('/article/', 'もっと見る', ['class' => 'top__ttl__link']) }}
                </div>
            </div>
            <ul class="top__article__list">
                @foreach ($recipe_list as $i => $recipe)
                    <li>
                        <a href="{{ $recipe->guid }}">
                            <div
                                class="top__article__list__thumb">{!! Tag::image($recipe->img, $recipe->title, ['loading' => 'lazy']) !!}</div>
                            <div class="top__shopping__list__content">
                                <p class="top__shopping__list__ttl">{{ $recipe->title }}</p>
                                <p class="top__shopping__list__data">{{ $recipe->update }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="article__btn">
                <a href="/article/poikatsulist/">ポイ活お得情報一覧を見る</a>
            </div>
        </div>
    </div><!-- /.top__article -->
@endif

<script type="text/javascript"><!--
var diffTimestamp = 0;

$(function() {
    var serverTimestamp = "{{ $now->timestamp }}";

    var clientDate = new Date();
    var clientTimestamp = Math.floor(clientDate.getTime() / 1000);

    // サーバーとクライアントの時間差
    diffTimestamp = serverTimestamp - clientTimestamp;

    setInterval(function() {
        $('.counter').each(function() {
            var $ele = $(this);

            // セール終了時間
            var stopAtTimestamp = $ele.attr('timestamp');

            // サーバーとクライアントの時間差を加味した現在の時間
            var nowDate = new Date();
            var nowTimestamp = Math.floor(nowDate.getTime() / 1000);
            nowTimestamp += diffTimestamp;

            // セール終了までの残り時間
            var countDownTimestamp = Math.max(stopAtTimestamp - nowTimestamp, 0);

            var second = 60 * 60 * 24;
            var dd = Math.floor(countDownTimestamp / second);
            var hh = Math.floor((countDownTimestamp % second) / (60 * 60));
            var mm = Math.floor((countDownTimestamp % second) / 60) % 60;
            var ss = Math.floor(countDownTimestamp % second) % 60 % 60;

            var h0 = ('00' + hh).slice(-2);
            var m0 = ('00' + mm).slice(-2);
            var s0 = ('00' + ss).slice(-2);

            // htmlに残り時間を反映
            $ele.find('.countDownDay').text(dd);
            $ele.find('.countDownTime').text(h0 + ':' + m0 + ':' + s0);
        });
    }, 1000);
});
//-->
</script>

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
    $program_list = \App\Program::ofList()
        ->whereIn('id', $program_id_list)
        ->get();
}
@endphp
@if (!$program_list->isEmpty())
@php
// ランダムで表示を5件に制限
$program_list = $program_list->shuffle()->splice(0, 5);
@endphp

@endif

@php
$feature_content_list = \App\Content::ofSpot(\App\Content::SPOT_FEATURE)
    ->orderBy('start_at', 'asc')
    ->limit(2)
    ->get();
@endphp
@if (!$feature_content_list->isEmpty())

@endif

@php
// 口コミ
$review_list = App\Review::ofEnableLabel()
    ->ofSort(0)
    ->take(3)
    ->get();
@endphp
@if (!$review_list->isEmpty())

@endif

@if (Auth::check())
<div class="top__opinion__box">
    <div class="inner">
        <h2 class="contents__ttl">ご意見箱</h2>
        <div class="opinion__box">
            @if (Session::has('opinionSended'))
            <!-- 送信成功開始 -->
            <p><b>ご意見ありがとうございました。</b><br />
            頂いたご意見は、運営スタッフが必ず目を通させて頂きますが、個々のご意見に返信できないことを予めご了承ください。<br />
            返信が必要な場合、大変お手数ですが、下記のお問い合わせフォームよりお問い合わせください。</p>
            <p class="inquiries__btn">{!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせ') !!}</p>
            <!-- 送信成功終了 -->
            @else
            <!-- 送信入力開始 -->
            <p>GMOポイ活へのご意見・ご要望をお聞かせください！<br />
            頂いた意見は今後のサイト運営、改善に役立てていけるよう、参考にさせていただきます。</p>
            <?php
            $body_attr = ['cols' => '', 'rows' => '3', 'placeholder' => 'ご意見を入力ください'];
            if (WrapPhp::count($errors) > 0) {
                $body_attr['class'] = 'error';
            }
            ?>
            {!! Tag::formOpen(['url' => route('users.opinion'), 'class' => 'opinion__box__form']) !!}
            @csrf    
            {!! Tag::formHidden('scroll', '') !!}
                {!! Tag::formTextarea('body', '', $body_attr) !!}
                @if ($errors->has('body'))
                <p class="error_message"><span class="icon-attention"></span>&nbsp;{{ $errors->first('body') }}</p>
                @endif
                {!! Tag::formButton('意見を送る', ['type' => 'submit']) !!}
            {!! Tag::formClose() !!}
            <!-- 送信入力終了 -->
            @endif
        </div>
    </div>
</div><!--/opinionbox-->

<div class="top__stop">
    <div class="inner u-mt-20">
        {{ Tag::link(route('stops'), Tag::image("/images/common/bnr_stop.png", '', ['loading' => 'lazy']), null, null, false) }}
    </div>
</div>
@endif

<script type="text/javascript">
let introExSwiper1 = new Swiper('.js-intro-ex-swiper1', {
		loop: true,
		speed: 4000,
		slidesPerView: 4,
		spaceBetween: 8,
		allowTouchMove: false,
		autoplay: {
			delay: 0,
		},
	});
	let introExSwiper2 = new Swiper('.js-intro-ex-swiper2', {
		loop: true,
		speed: 4000,
		slidesPerView: 4,
		spaceBetween: 8,
		centeredSlides : true,
		allowTouchMove: false,
		autoplay: {
			delay: 0,
		},
	});
	let swiper1 = new Swiper('.swiper1', {
		loop: true,
		speed: 500,
		slidesPerView: 'auto',
		spaceBetween: 10,
		centeredSlides : true,
		initialSlide: 0,
		effect:'slide',
		autoplay: {
			delay: 4000,
			disableOnInteraction: false,
		},
		pagination: {
			el: '.swiper-pagination1',
			type: 'bullets',
			clickable: true,
		}
	});

    let swiper3 = new Swiper('.swiper3', {
        loop: true,
        speed: 500,
        slidesPerView: 'auto',
        spaceBetween: 10,
        centeredSlides : false,
        initialSlide: 0,
        effect:'slide',
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });
    // tab サービスから検索
    $(function(){
        $(".top__search__service__tab a").click(function(){
            $(this).parent().addClass("active").siblings(".active").removeClass("active");
            var top__search__service__inner = $(this).attr("href");
            $(top__search__service__inner).addClass("active").siblings(".active").removeClass("active");
            return false;
        });
    });

    // tab ランキング
    $(function(){
        $(".top__ranking__tab a").click(function(){
            $(this).parent().addClass("active").siblings(".active").removeClass("active");
            var top__ranking = $(this).attr("href");
            $(top__ranking).addClass("active").siblings(".active").removeClass("active");
            return false;
        });
    });

</script>
@if (Auth::check())
<script src="/js/ad-modal.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const consentCheckbox = document.getElementById('consent');
        const userId = '{{ Auth::user()->id }}';
        const dontShowTodayKey = `dont_show_today_${userId}`;
        function setCookie(name, value) {
            const now = new Date();
            const midnight = new Date(now);
            midnight.setHours(23, 59, 59, 999); 
            const expires = "expires=" + midnight.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i].trim();
                if (c.indexOf(nameEQ) == 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }
        function eraseCookie(name) {
            document.cookie = name + '=; Max-Age=-99999999;';
        }
        function setDontShowToday() {
            fetch('{{ route("set_dont_show_today") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                // console.log('Dont show today set:', data);
            })
            .catch(error => {
                // console.error('Error setting dont_show_today:', error);
            });
        }
        function clearDontShowToday() {
            fetch('{{ route("clear_dont_show_today") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                // console.log('Dont show today cleared:', data);
            })
            .catch(error => {
                // console.error('Error clearing dont_show_today:', error);
            });
        }
        const dontShowToday = getCookie(dontShowTodayKey);
        if (consentCheckbox) {
            consentCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    setCookie(dontShowTodayKey, true); // Create cookie 
                    setDontShowToday();
                } else {
                    eraseCookie(dontShowTodayKey); // Clear cookie
                    clearDontShowToday();
                }
            });
        }
    });
</script>
@endif
@endsection
@section('layout.footer_notes')
@php
    $footNotes = 'seo';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection