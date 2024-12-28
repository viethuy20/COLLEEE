@php
$base_css_type = 'top';
@endphp
@extends('layouts.default')

@section('og_type', 'website')

@section('layout.head')

{{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css') }}
{!! Tag::style('/css/common_20240613.css') !!}
{!! Tag::style('/css/modal.css') !!}
{!! Tag::style('/css/popup_ad_modal.css') !!}

{{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js', ['type' => 'text/javascript']) }}

@endsection

@section('layout.content.header.top_banner')
    <section class="top__intro">
        <div class="top__intro__inner">
            <div class="top__intro__ttl">
                <p>学生・会社員・主婦・シニアまで</p>
                <h1><img src="/images/top/intro/intro_heading_pc.svg" width="700" height="58"
                        alt="はじめてのポイ活はGMOポイ活"></h1>
            </div>
            <ul class="top__intro__emblems">
                <li><img src="/images/top/intro/intro__emblem_1.svg" width="134" height="108" alt="利用・登録料 ずーっと0円">
                </li>
                <li><img src="/images/top/intro/intro__emblem_2.svg" width="134" height="108"
                        alt="運営実績 20年超えの老舗サイト"></li>
                <li><img src="/images/top/intro/intro__emblem_3.svg" width="134" height="108"
                        alt="運営会社 GMOインターネットグループ会社"></li>
            </ul>
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
                <p class="top__intro__feature__txt">貯めたポイントは<strong>1ポイント1円分</strong>で<strong>現金</strong>・<strong>電子マネー</strong>などに交換！</p>
                <div class="top__intro__feature__exchanges swiper-container js-intro-ex-swiper">
                    <ul class="swiper-wrapper">
                        <li class="swiper-slide"><img src="/images/exchanges/img_bank.png" alt="現金"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_jp-bank.png" alt="ゆうちょ銀行"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_smbc.png" alt="三井住友銀行"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_mizuho.png" alt="みずほ銀行"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_mufg.png" alt="三菱UFJ銀行"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_gmo-aozora.png" alt="GMOあおぞらネット銀行"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_rakuten-bank.png" alt="楽天銀行"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_amazon.png" alt="Amazonギフトカード"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_apple.png" alt="Apple Gift Card"></li>
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
                        <li class="swiper-slide"><img src="/images/exchanges/img_ponta.png" alt="Pontaポイント"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_pssticket.png" alt="プレイステーション ストアチケット"></li>
                        <li class="swiper-slide"><img src="/images/exchanges/img_kdol.png" alt="KDOL" /></li>
                    </ul>
                </div>
                <div class="top__intro__deco">
                    <div class="charas">
                        <figure class="chara-l"><img src="/images/top/intro/deco_chara_2.png" width="150"
                                height="190" alt=""></figure>
                        <figure class="chara-r"><img src="/images/top/intro/deco_chara_1.png" width="150"
                                height="190" alt=""></figure>
                    </div>
                    <div class="coins">
                        <figure class="coin-l"><img src="/images/top/intro/deco_coin_2_pc.png" width="215"
                                height="175" alt=""></figure>
                        <figure class="coin-r"><img src="/images/top/intro/deco_coin_1_pc.png" width="215"
                                height="175" alt=""></figure>
                    </div>
                </div>
            </div>
            <figure class="top__intro__cloud"></figure>
            <div class="top__intro__cv__wrap">
                @if (Auth::check())
                <div class="top__intro__cv">
                    <a href="{{ route('entries.index') }}">
                        <p class="box"><i><img src="/images/top/intro/ico_alarm-clock.svg"
                                    alt=""></i>最短1分！</p>
                        <p>無料会員登録はこちら</p>
                    </a>
                </div>
                @else
                @php
                $cv = '';
                @endphp
                @include('inc.cv-btn',['cv' => $cv])
                @endif
            </div>
        </div>
    </section>
@endsection
@section('layout.footer_notes')
@php
    $footNotes = 'seo';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection
@section('layout.content.header')

@if (!Auth::check())

@include('fixed_btn')

@endif

@php
$top_content_list = \App\Content::ofSpot(\App\Content::SPOT_PC_TOP)
    ->limit(8)
    ->get();

$time_sale_program_list = \App\Program::ofTimeSale()
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->take(3)
    ->get();

$recommend_program_list = \App\Program::ofEnable()
    ->ofSort(4)
    ->take(3)
    ->get();

$feature_category_list = \App\Content::ofSpot(\App\Content::SPOT_FEATURE_CATEGORY)
    ->orderBy('id', 'asc')
    ->take(4)
    ->get();

$ranking_banner_list = \App\Content::ofSpot(\App\Content::SPOT_LOWER_RANKING_BANNER)
    ->orderBy('priority', 'asc')
    ->orderBy('start_at', 'desc')
    ->take(3)
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
@endif

<!-- start mini banner  -->
@php
    $top_mini_banner_list = \App\Content::ofSpot(\App\Content::SPOT_MINI_BANNER_PC)
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
@endsection

@section('layout.content')
    <!-- main contents -->
    <div class="contents">

        <!-- 高還元セール -->
        @if (!$time_sale_program_list->isEmpty())
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">高還元セール</span>
                    SALE<span class="top__ttl__tag">期間限定UP中！</span></h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['タイムセール']]), 'もっと見る', ['class' => 'top__ttl__link']) }}
            </div>
        </div>
        <ul class="top__list__3col">
        @foreach ($time_sale_program_list as $time_sale_program)
        @php
        // アフィリエイト情報
        $affiriate = $time_sale_program->affiriate;
        // ポイント
        $point = $time_sale_program->point;
        @endphp
            <li>
                <a href="{{ route('programs.show', ['program'=> $time_sale_program]) }}">
                    <div class="top__list__3col__thumb">{{ Tag::image($affiriate->img_url, $time_sale_program->title, ['loading' => 'lazy']) }}</div>
                    <p class="top__list__3col__countdown counter" timestamp="{{ $point->stop_at->timestamp }}">
                        残り
                        <span class="countDownDay"></span>日
                        <span class="countDownTime"></span>
                    </p>
                    <p class="top__list__3col__ttl">{{ $time_sale_program->title }}</p>
                    <p class="top__list__3col__txt2">{{ $time_sale_program->fee_condition }}</p>
                    <p class="top__list__3col__linethrough">{{ $point->previous_point->fee_label }}p</p>
                    <p class="top__list__3col__point arrow">{{ $point->fee_label }}P</p>
                </a>
            </li>
        @endforeach
        </ul>
        <hr class="bd_orange u-mt-20">
        @endif

        @php
        use App\External\Logrecoai;
        use App\External\History;
        use App\External\RecommendProgram;
        @endphp

        
        <!-- スタッフおすすめ -->
        @php
        $recomennd_program = new RecommendProgram();
        $num = 10;
        $login_recommend_program_list = $recomennd_program->getRecommendPrograms($num,'pc');
        if(is_array($login_recommend_program_list) && count($login_recommend_program_list)>=4){
            $recommend_program_list_top = array_slice($login_recommend_program_list,0,4);
        }else{
            $recommend_program_list_top = $login_recommend_program_list;
        }
        if(is_array($login_recommend_program_list) && count($login_recommend_program_list)>4){
            if(is_array($login_recommend_program_list) && count($login_recommend_program_list)>=8){
                $recommend_program_list_bottom = array_slice($login_recommend_program_list,4,4);
            }else{
                $recommend_program_list_bottom = array_slice($login_recommend_program_list,4);
            }
        }else{
            $recommend_program_list_bottom = [];
        }
        
        
        @endphp
        @if ($recommend_program_list_top)
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">スタッフおすすめ</span>RECOMMENDED
                </h2>
            </div>
        </div>
        <ul class="top__list__shopping2">
            @foreach ($recommend_program_list_top as $recommend_program)
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
                    <div class="top__list__shopping__thumb">{!! Tag::image($affiriate->img_url, $recommend_program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__list__shopping__ttl">{{ $recommend_program->title }}</p>
                    <p class="top__list__shopping__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
        @if ($recommend_program_list_bottom)
        <ul class="top__list__shopping2">
            @foreach ($recommend_program_list_bottom as $recommend_program)
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
                    <div class="top__list__shopping__thumb">{!! Tag::image($affiriate->img_url, $recommend_program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__list__shopping__ttl">{{ $recommend_program->title }}</p>
                    <p class="top__list__shopping__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
        <hr class="bd_orange u-mt-20">
        @endif
        

        @if (Auth::check())
        <!-- 最近チェックした広告 -->
        @php
        $history_program_list_top = [];
        $history_program_list_bottom = [];
        $history_data = History::getProgramHistoriesData(8);
        if ($history_data) {
            $history_data_chunked = array_chunk($history_data, 4);
            $history_program_list_top = isset($history_data_chunked[0]) ? $history_data_chunked[0] : [];
            $history_program_list_bottom = isset($history_data_chunked[1]) ? $history_data_chunked[1] : [];
        }
        @endphp
        @if (!empty($history_program_list_top))
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">最近チェックした広告</span>HISTORY
                </h2>
            </div>
        </div>
        <ul class="top__list__shopping2">
            @foreach ($history_program_list_top as $history_program)
            @php
            // ポイント
            $point = $history_program->point;
            // アフィリエイト
            $affiriate = $history_program->affiriate;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $history_program]) }}">
                    <div class="top__list__shopping__thumb">{!! Tag::image($affiriate->img_url, $history_program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__list__shopping__ttl">{{ $history_program->title }}</p>
                    <p class="top__list__shopping__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
        @if (!empty($history_program_list_bottom))
        <ul class="top__list__shopping2">
            @foreach ($history_program_list_bottom as $history_program)
            @php
            // ポイント
            $point = $history_program->point;
            // アフィリエイト
            $affiriate = $history_program->affiriate;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $history_program]) }}">
                    <div class="top__list__shopping__thumb">{!! Tag::image($affiriate->img_url, $history_program->title,  ['loading' => 'lazy']) !!}</div>
                    <p class="top__list__shopping__ttl">{{ $history_program->title }}</p>
                    <p class="top__list__shopping__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
        <hr class="bd_orange u-mt-20">
        @endif
        @endif

        <!-- ランキング -->
        @php
        $credit_labels = [125];
        $app_labels = [136];
        $service_labels = [122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136];
        $travel_labels = [122];

        $credit_rank_programs = \App\Program::ofEnable()
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

        $all_rank_programs = \App\Program::ofEnable()
            ->ofRank()
            ->take(5)
            ->get();

        $app_rank_programs = \App\Program::ofEnable()
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

        $service_rank_programs = \App\Program::ofEnable()
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

        $travel_rank_programs = \App\Program::ofEnable()
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
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">ランキング</span>RANKING
                </h2>
            </div>
        </div>
        <!-- カードのランキングが存在しない場合は総合を先頭表示 -->
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
        <hr class="bd_orange u-mt-20">
        @endif

        <!-- start lower ranking banner  -->
        @if (!$ranking_banner_list->isEmpty())
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
        <!-- end lower ranking banner  -->

        <!-- 特集・キャンペーン -->
        @if (!$feature_category_list->isEmpty())
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">特集・キャンペーン</span>CAMPAIGN
                </h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(route('features.index'), 'もっと見る', ['class' => 'top__ttl__link']) }}
            </div>
        </div>
        <ul class="top__list__campaign">
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
        <hr class="bd_orange u-mt-20">
        @endif

        <!-- 新着広告 -->
        @php
        $new_program_list = \App\Program::ofList()
            ->ofSort(\App\Program::NEW_SORT)
            ->take(3)
            ->get();
        @endphp
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">新着広告</span>NEW
                </h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(route('programs.list', ['sort' => 3]), 'もっと見る', ['class' => 'top__ttl__link']) }}
            </div>
        </div>
        <ul class="top__list__3col">
            @foreach($new_program_list as $program)
            @php
            $affiriate = $program->affiriate;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    <div class="top__list__3col__thumb">{{ Tag::image($affiriate->img_url, $program->title, ['loading' => 'lazy']) }}</div>
                    <p class="top__list__3col__ttl">{{ $program->title }}</p>
                    <p class="top__list__3col__txt1">{{ $program->fee_condition }}</p>
                    <p class="top__list__3col__point">{{ $program->point->fee_label }}P</p>
                </a>
            </li>
            @endforeach
        </ul>

        <hr class="bd_orange u-mt-20">

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
        if (!empty($program_id_list)) {
            $program_lists = \App\Program::ofEnable()
                ->whereIn('id', $program_id_list)
                ->orderBy('id', 'desc')->limit(12)
                ->get();
        }
        @endphp

        @if (!empty($program_lists))
            <div class="top__pickup__ttl">
                <h2 class="top__pickup__ttl__en">
                    <span class="top__ttl__jp">お買い物ピックアップ</span>PICK UP
                </h2>
            </div>
            <ul class="top__list__pick_up">
                @foreach ($program_lists as $program)
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

            <hr class="bd_orange u-mt-20">

        @endif

        <!-- おすすめショッピング -->
        @php
        $standard_program_list = \App\Program::ofEnable()
            ->ofLabel([82])
            ->ofSort(4)
            ->take(4)
            ->get();
        @endphp
        @if (!empty($standard_program_list))
        <div class="top__ttl">
            <div class="top__ttl__l">
                <h2 class="top__ttl__en">
                    <span class="top__ttl__jp">おすすめショッピング</span>SHOPPING
                </h2>
            </div>
            <div class="top__ttl__r">
                {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [82]]), 'もっと見る', ['class' => 'top__ttl__link', 'loading' => 'lazy']) }}
            </div>
        </div>
        <ul class="top__list__shopping">
            @foreach ($standard_program_list as $program)
            @php
            // ポイント
            $point = $program->point;
            // アフィリエイト
            $affiriate = $program->affiriate;
            @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    <div class="top__list__shopping__thumb">{!! Tag::image($affiriate->img_url, $program->title, ['loading' => 'lazy']) !!}</div>
                    <p class="top__list__shopping__ttl">{{ $program->title }}</p>
                    <p class="top__list__shopping__point">
                        {{ $point->fee_label }}P
                    </p>
                </a>
            </li>
            @endforeach
        </ul>
        @endif

        <!-- ポイ活お得情報 -->
        @php
            $new_recipe_data = \App\External\Recipe::getNewRecipeList();
        @endphp
        @if (isset($new_recipe_data) && $new_recipe_data->result->status && !empty($new_recipe_data->items))
            @php
                $recipe_list = collect($new_recipe_data->items)->sortByDesc('update')->take(4)->values()->all();
            @endphp
            <hr class="bd_orange u-mt-20">
            <div class="top__ttl" id="top__article">
                <div class="top__ttl__l">
                    <h2 class="top__ttl__en">ポイ活お得情報</h2>
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
            <div class="top_article__btn">
                <a href="/article/poikatsulist/">ポイ活お得情報一覧を見る</a>
            </div>
        @endif
    </div><!-- /.contents -->

    @if (session('show_popup') && !empty($program_details) && Auth::check()) 
        <div class="js-modal-open" data-auto-open="true" data-is-time-sale="{{ $program_details->point->time_sale ?? 0 }}"></div>
                    <!-- modal include -->
        @include('inc.modal-ad' , ['ads' => $program_details , 'title' =>$popup_ads->title])

    @endif
    <script>
        let introExSwiper = new Swiper('.js-intro-ex-swiper', {
            loop: true,
            speed: 4000,
            slidesPerView: 6,
            spaceBetween: 16,
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
            centeredSlides : true,
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

        // ランキング
        $(function(){
            $(".top__ranking__tab a").click(function(){
                $(this).parent().addClass("active").siblings(".active").removeClass("active");
                var top__ranking = $(this).attr("href");
                $(top__ranking).addClass("active").siblings(".active").removeClass("active");
                return false;
            });

        // タイムセール
        var diffTimestamp = 0;

        $(function() {
            var serverTimestamp = "{{ \Carbon\Carbon::now()->timestamp }}";

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
