@php
$base_css_type = 'shopping';
@endphp
@extends('layouts.default')

@section('layout.title', 'ショッピング | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,買い物,ショッピング,お得,ポイント')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')
@section('og_type', 'website')

@section('layout.head')
<script>
$(document).ready(function(){
  $('.txt_ureview').collapser({
    mode: 'chars',
    truncate: 70,
    showText: '続きを読む',
    hideText: '閉じる'
  });
});

$(function(){
$('.shopping').slick({
autoplay:true,
autoplaySpeed:2000,
dots:true,
pauseOnHover:true,
arrows: false,
});
});
</script>
@endsection

@section('layout.content.header')
<h1 style="color:#89909f; font-size:14px; padding:5px; text-align:center;">日々のお買い物をお得に！</h1>
@php
$top_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_SP_TOP)
    ->limit(8)
    ->get();
@endphp
@if (!$top_content_list->isEmpty())
<!-- ▼carousel panel -->
<section id="feature" class="" style=""><div class="carousel"><ul class="shopping">
    @foreach ($top_content_list as $content)
    @php
    $content_data = $content->json_data;
    @endphp
    <li>{{ Tag::link($content_data->url, Tag::image($content_data->img_url, $content->title), null, null, false) }}</li>
    @endforeach
</ul></div></section>
<!-- △carousel panel -->
@endif
@endsection

@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$position = 1;
$application_json = '';
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem", "position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('shops.index');
$application_json .= '{ "@type": "ListItem","position": ' . $position . ', "name": "ショッピング", "item": "' . $link . '"}';

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
            ショッピング
        </li>
    </ol>
</section>
@endsection

@section('layout.content')
@php
// 公開・非公開確認
$target_banner_list = [1034 => ['link' => '/images/bnr_rakuten2.png', 'class' => 'fl'], 1022 => ['link' => '/images/bnr_yahoo.png', 'class' => 'fr']];
$target_banner = App\Program::whereIn('id', array_keys($target_banner_list))->ofEnable()->get()->keyBy('id');
@endphp
<div class="inner">
<section class="bnrs_shopping">
    {{ Tag::link('/support/?p=35', Tag::image('/images/bnr_aboutshopping_sp.png', 'GMOポイ活 ショッピングについてよくある質問'), null, null, false) }}
    <div class="clearfix bnr_fixed">
        @foreach($target_banner_list as $program_id => $value)
        @if(isset($target_banner[$program_id]))
        {{ Tag::link(route('programs.show', ['program' => $program_id]), Tag::image($value['link'], '', ['class' => $value['class']]), null, null, false) }}
        @endif
        @endforeach
    </div>
</section>
</div>
<div class="inner">
@include('elements.shop_searchbox')
</div>
@include('elements.shop_category')

<!--ショッピングに関するポイ活お得情報-->
@include('elements.shopping_recipe_list')

<!--お得なキャンペーン-->
@php
$campaign_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_CAMPAIGN)
    ->get()
    ->shuffle();
@endphp
@if (!$campaign_content_list->isEmpty())
@php
$show_content_total = 0;
@endphp
<div class="inner">
    <div class="top__ttl u-mt-remove">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">お得なキャンペーン</p>
            <h2 class="top__ttl__en">HOT CAMPAIGN</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['content_spot_id' => \App\Content::SPOT_SHOP_CAMPAIGN]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <ul class="hot_campaign_box">
        @foreach ($campaign_content_list as $content)
        @break ($show_content_total >= 3)
        @php
        $content_data = $content->json_data;
        $program = $content->program;
        @endphp
        @continue (!isset($program->id))
        @php
        $show_content_total = $show_content_total + 1;
        $point = $program->point;
        @endphp
        <li>
            <a href="{{ route('programs.show', ['program' => $program]) }}">
                <div class="imgbox">{{ Tag::image($content_data->img_url, $content->title) }}</div>
                <dl>
                    <dt class="heading">{{ $content->title }}</dt><!--案件名-->
                    <dd class="schedule">{{ $content_data->term }}</dd>
                    <dd class="txt_campaign">{{ $content_data->detail }}</dd>
                    <dd class="cp">
                        @if ($point->fee_type == 2)
                            購入額の
                        @endif
                        <span class="large">{{ $program->point->fee_label_s }}P</span></dd>
                </dl>
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif

<!--定番ショップ-->
@include('elements.shop_standard')

<!--話題の商品-->
@php
$popular_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_POPULAR)
    ->limit(4)
    ->get();
@endphp
@if (!$popular_content_list->isEmpty())
<div class="inner">
    <div class="top__ttl u-mt-remove">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">話題の商品</p>
            <h2 class="top__ttl__en">HOT ITEMS</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['content_spot_id' => \App\Content::SPOT_SHOP_POPULAR]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <ul class="hot_campaign_box">
        @foreach ($popular_content_list as $content)
        @php
        $content_data = $content->json_data;
        $program = $content->program;
        @endphp
        @continue (!isset($program->id))
        @php
        $point = $program->point;
        @endphp
        <li>
            <a href="{{ route('programs.show', ['program' => $program]) }}">
                <div class="imgbox">{{ Tag::image($content_data->img_url, $content->title) }}</div>
                <dl>
                    <dd class="heading">{{ $content->title }}</dd>
                    <dd class="txt_campaign">商品価格：{{ number_format($content_data->price) }}円</dd>
                    <dd class="cp">
                        @if ($point->fee_type == 2)
                            購入額の
                        @endif
                        <span class="large">{{ $program->point->fee_label_s }}P</span>
                    </dd>
                </dl>
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif

<!--いま注目のショップ-->
@php
$publicity_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_PUBLICITY)
    ->limit(4)
    ->get();
@endphp
@if (!$publicity_content_list->isEmpty())
<div class="inner">
    <div class="top__ttl u-mt-remove">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">いま注目のショップ</p>
            <h2 class="top__ttl__en">POPULAR SHOPS</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['content_spot_id' => \App\Content::SPOT_SHOP_PUBLICITY]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <div class="programs_list__list">
        <ul class="programs_list__list__wrap">
        @php
        $user_program_id_list = [];
        // ログインしている場合、お気に入り登録プログラムID一覧を取得
        if (Auth::check()) {
            $program_id_list = [];
            foreach ($publicity_content_list as $content) {
                $content_data = json_decode($content->data);
                $program_id_list[] = $content_data->program_id;
            }
            $user_program_id_list = Auth::user()->fav_programs()
                ->wherePivotIn('program_id', $program_id_list)
                ->pluck('programs.id')
                ->all();
        }
        @endphp
        @foreach ($publicity_content_list as $content)
        @php
        $content_data = $content->json_data;
        $program = $content->program;
        @endphp
        @continue (!isset($program->id))

        @php
        $show_url = route('programs.show', ['program' => $program]);
        $point = $program->point;
        // アフィリエイト
        $affiriate = $program->affiriate;
        @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    <div class="programs_list__detail">
                        <div class="programs_list__detail__l">
                            <div class="programs_list__detail__thumb">
                                {{ Tag::image($affiriate->img_url, $program->title) }}
                            </div>
                        </div> <!-- programs_list__detail__l -->
                        <div class="programs_list__detail__r">
                            <p class="programs_list__detail__txt">{{ $program->title }}</p>
                            <dl class="programs_list__detail__chart">
                                <dd>{{ $program->fee_condition }}</dd>
                            </dl>
                            <ul class="programs_list__detail__kuchikomi">
                                <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg')}}" alt=""></i>総合評価：
                                    @if ($program->review_avg > 0)
                                        {{ $program->review_avg }}
                                    @else
                                        -
                                    @endif
                                </li>
                                <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg')}}" alt=""></i>口コミ：
                                    @if ($program->review_total > 0)
                                        {{ number_format($program->review_total).'件' }}
                                    @else
                                        0件
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div class="programs_list__detail__description">{!! $program->description !!}</div>
                        <!--ここはタイムセール処理の分岐無し -->
                        <p class="programs_list__detail__point2">
                            @if ($point->fee_type == 2)
                                購入額の
                            @endif
                            <span class="large">{{ $point->fee_label_s }}</span>
                            <span>P</span>
                        </p> <!-- programs_list__detail__point -->
                    </div>
                </a>
            </li>
        @endforeach
        </ul>
    </div><!--/contentsbox_h-->
</div>
@endif

<!--実質無料でお試し-->
@php
$now = Carbon\Carbon::now();
$program_list = App\Program::ofList()
    ->join('points', function ($join) use ($now) {
        $join->on('programs.id', '=', 'points.program_id')
            ->where('points.status', '=', 0)
            ->where('points.stop_at', '>=', $now)
            ->where('points.start_at', '<=', $now);
    })
    ->select('programs.*')
    ->where('points.all_back', '=', 1)
    ->take(5)
    ->get();
@endphp
@if (!$program_list->isEmpty())
@php
$user_program_id_list = [];
// ログインしている場合、お気に入り登録プログラムID一覧を取得
if (Auth::check()) {
    $program_id_list = [];
    foreach ($program_list as $program) {
        $program_id_list[] = $program->id;
    }
    $user_program_id_list = Auth::user()->fav_programs()
        ->wherePivotIn('program_id', $program_id_list)
        ->pluck('programs.id')
        ->all();
}
@endphp
<div class="inner">
    <div class="top__ttl u-mt-remove">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">実質無料でお試し</p>
            <h2 class="top__ttl__en">ESSENTIALLY FREE</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['all_back' => 1]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <div class="programs_list__list">
        <ul class="programs_list__list__wrap">
        @foreach ($program_list as $program)
        @php
        // アフィリエイト
        $affiriate = $program->affiriate;
        // ポイント
        $point = $program->point;
        $show_url = route('programs.show', ['program'=> $program]);
        @endphp
            <li>
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    <div class="programs_list__detail">
                        <div class="programs_list__detail__l">
                            <div class="programs_list__detail__thumb">
                                {{ Tag::image($affiriate->img_url, $program->title) }}
                            </div>
                        </div> <!-- programs_list__detail__l -->
                        <div class="programs_list__detail__r">
                            <p class="programs_list__detail__txt">{{ $program->title }}</p>
                            <dl class="programs_list__detail__chart">
                                <dd>{{ $program->fee_condition }}</dd>
                            </dl>
                            <ul class="programs_list__detail__kuchikomi">
                                <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg')}}" alt=""></i>総合評価：
                                    @if ($program->review_avg > 0)
                                        {{ $program->review_avg }}
                                    @else
                                        -
                                    @endif
                                </li>
                                <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg')}}" alt=""></i>口コミ：
                                    @if ($program->review_total > 0)
                                        {{ number_format($program->review_total).'件' }}
                                    @else
                                        0件
                                    @endif
                                </li>
                            </ul>
                            <!--ここはタイムセール処理の分岐無し -->
                            <p class="programs_list__detail__point">
                                @if ($point->fee_type == 2)
                                    購入額の
                                @endif
                                <span class="large">{{ $point->fee_label_s }}</span>
                                <span>P</span>
                            </p> <!-- programs_list__detail__point -->
                        </div> <!-- programs_list__detail__r -->
                    </div>
                </a>
            </li>
        @endforeach
        </ul> <!-- programs_list__list__wrap -->
    </div> <!-- programs_list__list -->
</div>
@endif

<!-- 新着情報 -->
<div class="inner">
    <div class="top__ttl u-mt-remove">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">新着情報</p>
            <h2 class="top__ttl__en">NEW</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['ll' => [77],'sort' => 3]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    @php
    // 新着情報取得
    $new_program_list = \App\Program::ofEnableDevice()
        ->ofLabel([77])
        ->ofSort(\App\Program::NEW_SORT)
        ->take(3)
        ->get();
    @endphp
    @if (!$new_program_list->isEmpty())
    @php
    $user_program_id_list = [];
    // ログインしている場合、お気に入り登録プログラムID一覧を取得
    if (Auth::check()) {
        $program_id_list = [];
        foreach ($new_program_list as $program) {
            $program_id_list[] = $program->id;
        }
        $user_program_id_list = Auth::user()->fav_programs()
            ->wherePivotIn('program_id', $program_id_list)
            ->pluck('programs.id')
            ->all();
    }
    @endphp
    <div class="programs_list__list">
        <ul class="programs_list__list__wrap">
            @foreach ($new_program_list as $program)
                @php
                    // アフィリエイト
                    $affiriate = $program->affiriate;
                    // ポイント
                    $point = $program->point;
                    $show_url = route('programs.show', ['program'=> $program]);
                @endphp
                <li>
                    <a href="{{ route('programs.show', ['program' => $program]) }}">
                        <div class="programs_list__detail">
                            <div class="programs_list__detail__l">
                                <div class="programs_list__detail__thumb">
                                    {{ Tag::image($affiriate->img_url, $program->title) }}
                                </div>
                            </div> <!-- programs_list__detail__l -->
                            <div class="programs_list__detail__r">
                                <p class="programs_list__detail__txt">{{ $program->title }}</p>
                                <dl class="programs_list__detail__chart">
                                    <dd>{{ $program->fee_condition }}</dd>
                                </dl>
                                <ul class="programs_list__detail__kuchikomi">
                                    <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg')}}" alt=""></i>総合評価：
                                        @if ($program->review_avg > 0)
                                            {{ $program->review_avg }}
                                        @else
                                            -
                                        @endif
                                    </li>
                                    <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg')}}" alt=""></i>口コミ：
                                        @if ($program->review_total > 0)
                                            {{ number_format($program->review_total).'件' }}
                                        @else
                                            0件
                                        @endif
                                    </li>
                                </ul>
                                <!--ここはタイムセール処理の分岐無し -->
                                <p class="programs_list__detail__point">
                                    @if ($point->fee_type == 2)
                                        購入額の
                                    @endif
                                    <span class="large">{{ $point->fee_label_s }}</span>
                                    <span>P</span>
                                </p> <!-- programs_list__detail__point -->
                            </div> <!-- programs_list__detail__r -->
                        </div>
                    </a>
                </li>
            @endforeach
        </ul> <!-- programs_list__list__wrap -->
    </div> <!-- programs_list__list -->
    @endif
</div>

<!-- みんなの新着口コミ -->
@php
// 口コミ
$review_list = \App\Review::ofEnableLabel([77])
    ->ofSort(0)
    ->take(3)
    ->get();
@endphp
@if (!$review_list->isEmpty())
<div class="inner">
    @include('elements.review_list', ['review_list' => $review_list])
</div>
@endif

@endsection
