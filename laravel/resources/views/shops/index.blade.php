@extends('layouts.shop')

@section('layout.title', 'ショッピング | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,買い物,ショッピング,お得,ポイント')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')
@section('url', route('shops.index'))
@section('og_type', 'website')

@section('layout.head')
{{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css') }}
{{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js', ['type' => 'text/javascript']) }}
<script type="text/javascript">
    $(function(){
        $('#itslide').fadeIn(2000); //fade処理追加2秒？
        $( '#itslide' ).sliderPro({
            width: 640,
            height: 400,
            visibleSize: 640,
            forceSize: 'fullWidth',
            autoSlideSize: true,
            autoplayOnHover:'pause',
            keyboard: true
        });
    });
</script>
<script type="text/javascript">
jQuery(function($) {
  $('.txt_blog, .relativerecipe .zwei .heading, .relativerecipe .drei .heading, .relativerecipe .vier .heading, .classicstores .storesbox .eachstore li a dt').each(function() {
    var $target = $(this);

    // オリジナルの文章を取得する
    var html = $target.html();

    // 対象の要素を、高さにautoを指定し非表示で複製する
    var $clone = $target.clone();
    $clone
      .css({
        display: 'none',
        position : 'absolute',
        overflow : 'visible'
      })
      .width($target.width())
      .height('auto');

    // DOMを一旦追加
    $target.after($clone);

    // 指定した高さになるまで、1文字ずつ消去していく
    while((html.length > 0) && ($clone.height() > $target.height())) {
      html = html.substr(0, html.length - 1);
      $clone.html(html + '...');
    }

    // 文章を入れ替えて、複製した要素を削除する
    $target.html($clone.html());
    $clone.remove();
  });
});
</script>
<script>
$(document).ready(function(){
  $('.txt_ureview').collapser({
    mode: 'chars',
    truncate: 60,
    showText: '続きを読む',
    hideText: '閉じる'
  });
});
</script>
@endsection

@section('layout.content.header')
@php
$top_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_PC_TOP)
    ->limit(8)
    ->get();
@endphp
@if (!$top_content_list->isEmpty())
<section class="top__fv">
    <div class="swiper-container swiper1">
        <ul class="swiper-wrapper">
            @foreach ($top_content_list as $content)
            @php
            $content_data = $content->json_data;
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

<section class="contents">
    <h2 class="contents__ttl">日々のお買い物をお得に！</h2>
    <!--ショッピングに関するポイ活お得情報-->
    @include('elements.shopping_recipe_list')

    <!--定番ショップ-->
    @include('elements.shop_standard', ['limit' => 8])

    @php
        $popular_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_POPULAR)
            ->limit(9)
            ->get();
    @endphp

    <!--話題の商品-->
    @if (!$popular_content_list->isEmpty())
    <div class="top__ttl">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">話題の商品</p>
            <h2 class="top__ttl__en">HOT ITEMS</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['content_spot_id' => \App\Content::SPOT_SHOP_POPULAR]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <ul class="hot_items_box">
        @foreach ($popular_content_list as $content)
        @php
        $content_data = $content->json_data;
        $program = $content->program;
        @endphp
        @continue (!isset($program->id))
        @php
        $point = $program->point;
        // アフィリエイト
        $affiriate = $program->affiriate;
        @endphp
        <li>
            <a href="{{ route('programs.show', ['program' => $program]) }}">
                <div class="top__list__shopping__thumb">{{ Tag::image($content_data->img_url, $content->title) }}</div>
                <p class="top__list__shopping__ttl">{{ $content->title }}</p>
                <p class="top__list__shopping__price">商品価格：{{ number_format($content_data->price) }}円</p>
                <p class="top__list__shopping__point">
                    @if ($point->fee_type == 2)
                    <span>{{ strval($point->rate_percent) }}</span>%P
                    @else
                    <span>{{ $point->fee_label_s }}</span>P
                    @endif
                </p>
            </a>
        </li>
        @endforeach
    </ul>
    <hr class="bd_orange u-mt-20">
    @endif

    <!--いま注目のショップ-->
    @php
        $publicity_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_PUBLICITY)
            ->limit(4)
            ->get();
    @endphp
    @if (!$publicity_content_list->isEmpty())
    <div class="top__ttl">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">いま注目のショップ</p>
            <h2 class="top__ttl__en">POPULAR SHOPS</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['content_spot_id' => \App\Content::SPOT_SHOP_PUBLICITY]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <ul class="popular_shops_box">
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
        $url = route('programs.show', ['program' => $program]);
        $point = $program->point;
        // アフィリエイト
        $affiriate = $program->affiriate;
        @endphp
        <li>
            <dl>
                <dt>
                    <div class="img_recipe">{{ Tag::link($url, Tag::image($affiriate->img_url, $program->title), null, null, false) }}</div><!--120*120-->
                    @if (in_array($program->id, $user_program_id_list))
                    {{ Tag::link(route('users.remove_program', ['program' => $program]), '<p class="added_fav">お気に入り削除</p>', ['class' => 'save_scroll'], null, false) }}
                    @else
                    {{ Tag::link(route('users.add_program', ['program' => $program]), '<p class="add_fav">お気に入り追加</p>', ['class' => 'save_scroll'], null, false) }}
                    @endif
                </dt>
                <dd class="sname">{{ Tag::link($url, $program->title) }}</dd>
                <dd class="shopping__point"><p>
                    @if ($point->fee_type == 2)
                    <span>{{ strval($point->rate_percent) }}</span>%P
                    @else
                    <span>{{ $point->fee_label_s }}</span>P
                    @endif
                </p></dd>
                <dd class="product">
                    <div class="rating">総合評価：
                        <ul class="stars"><!--
                            @for ($i = 1; $i <= 5; $i++)
                            --><li>{{ Tag::image(($i <= $program->review_avg) ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}</li><!--
                            @endfor
                        --></ul><!--★-->
                        ({{ $program->review_avg }}/5)
                    </div>
                    <dl class="numberofreviews">
                        <dt>口コミ:</dt>
                        <dd>
                            @if ($program->review_total > 0)
                            {{ Tag::link($url, number_format($program->review_total).'件') }}
                            @else
                            0件
                            @endif
                        </dd>
                    </dl>
                </dd>
                <dd class="txt_note">{!! $program->description !!}</dd>
            </dl>
        </li>
        @endforeach
    </ul>
    <hr class="bd_orange u-mt-20">
    @endif

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
    <div class="top__ttl">
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
        @endphp
        <li>
            <a href="{{ route('programs.show', ['program' => $program]) }}">
            <div class="imgbox">{{ Tag::image($content_data->img_url, $content->title) }}</div>
            <dl>
                <dt class="heading">{{ $content->title }}</dt>
                <dd class="schedule" style=" width:100%; text-align:center;">期間：{{ $content_data->term }}</dd>
                <dd class="txt_campaign">{{ $content_data->detail }}</dd>
                <dd class="cp">
                    @if ($program->point->fee_type == 2)
                        購入額の
                        <span class="large">{{ strval($point->rate_percent) }}</span><span>%P</span>
                    @else
                        <span class="large">{{ $point->fee_label_s }}</span><span>P</span>
                    @endif
                </dd>
            </dl>
            </a>
        </li><!--/contentsbox-->
        @endforeach
    </ul>
    <hr class="bd_orange u-mt-20">
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
    <div class="top__ttl">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">実質無料でお試し</p>
            <h2 class="top__ttl__en">PRACTICALLY FREE</h2>
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
                <div class="programs_list__detail">
                    <div class="programs_list__detail__l">
                        <div class="programs_list__detail__thumb">
                            {{ Tag::link($show_url, Tag::image($affiriate->img_url, $program->title), null, null, false) }}
                        </div>
                        @if (in_array($program->id, $user_program_id_list))
                        <div class="programs_list__detail__btn__gray">
                            {{ Tag::link(route('users.remove_program', ['program' => $program]), 'お気に入り削除', ['class' => 'save_scroll'], null, false) }}
                        </div>
                        @else
                        <div class="programs_list__detail__btn">
                            {{ Tag::link(route('users.add_program', ['program' => $program]), 'お気に入り追加', ['class' => 'save_scroll'], null, false) }}
                        </div>
                        @endif
                    </div><!-- programs_list__detail__l" -->
                    <div class="programs_list__detail__r">
                        <p class="programs_list__detail__txt">{{ Tag::link($show_url, $program->title) }}</p>
                        <ul class="programs_list__detail__tag">
                            @foreach ($program->tag_list as $tag)
                            <li>{{ $tag }}</li>
                            @endforeach
                        </ul>
                        <dl class="programs_list__detail__chart">
                            <dt>獲得条件</dt>
                            <dd>{{ $program->fee_condition }}</dd>
                            <dt>獲得までの期間</dt>
                            <dd>{{ config('map.accept_days')[$affiriate->accept_days] }}</dd>
                        </dl>
                        <ul class="programs_list__detail__kuchikomi">
                            <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg')}}" alt=""></i>総合評価：
                                @if ($program->review_avg > 0)
                                {{ Tag::link($show_url.'#reviews', $program->review_avg) }}
                                @else
                                -
                                @endif
                            </li>
                            <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg')}}" alt=""></i>口コミ：
                                @if ($program->review_total > 0)
                                {{ Tag::link($show_url.'#reviews', number_format($program->review_total).'件') }}
                                @else
                                0件
                                @endif
                            </li>
                        </ul>
                        <!--ここはタイムセール処理の分岐無し -->
                        <p class="programs_list__detail__point">
                            @if ($point->fee_type == 2)
                                購入額の
                                <span class="large">{{ strval($point->rate_percent) }}</span><span>%P</span>
                            @else
                                <span class="large">{{ $point->fee_label_s }}</span><span>P</span>
                            @endif
                        </p>
                    </div>
                </div>
            </li>
            @endforeach
        </ul><!-- programs_list__list__wrap -->
    </div><!-- programs_list__list -->
    <hr class="bd_orange u-mt-20">
    @endif

    <!--新着情報-->
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
    <div class="top__ttl">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">新着情報</p>
            <h2 class="top__ttl__en">NEW</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link( \App\Search\ProgramCondition::getStaticListUrl((object)['ll' => [77],'sort' => 3]), 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
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
                <div class="programs_list__detail">
                    <div class="programs_list__detail__l">
                        <div class="programs_list__detail__thumb">
                            {{ Tag::link($show_url, Tag::image($affiriate->img_url, $program->title), null, null, false) }}
                        </div>
                        @if (in_array($program->id, $user_program_id_list))
                        <div class="programs_list__detail__btn__gray">
                            {{ Tag::link(route('users.remove_program', ['program' => $program]), 'お気に入り削除', ['class' => 'save_scroll'], null, false) }}
                        </div>
                        @else
                        <div class="programs_list__detail__btn">
                            {{ Tag::link(route('users.add_program', ['program' => $program]), 'お気に入り追加', ['class' => 'save_scroll'], null, false) }}
                        </div>
                        @endif
                    </div><!-- programs_list__detail__l" -->
                    <div class="programs_list__detail__r">
                        <p class="programs_list__detail__txt">{{ Tag::link($show_url, $program->title) }}</p>
                        <ul class="programs_list__detail__tag">
                            @foreach ($program->tag_list as $tag)
                            <li>{{ $tag }}</li>
                            @endforeach
                        </ul>
                        <dl class="programs_list__detail__chart">
                            <dt>獲得条件</dt>
                            <dd>{{ $program->fee_condition }}</dd>
                            <dt>獲得までの期間</dt>
                            <dd>{{ config('map.accept_days')[$affiriate->accept_days] }}</dd>
                        </dl>
                        <ul class="programs_list__detail__kuchikomi">
                            <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg')}}" alt=""></i>総合評価：
                                @if ($program->review_avg > 0)
                                {{ Tag::link($show_url.'#reviews', $program->review_avg) }}
                                @else
                                -
                                @endif
                            </li>
                            <li><i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg')}}" alt=""></i>口コミ：
                                @if ($program->review_total > 0)
                                {{ Tag::link($show_url.'#reviews', number_format($program->review_total).'件') }}
                                @else
                                0件
                                @endif
                            </li>
                        </ul>
                        <!--ここはタイムセール処理の分岐無し -->
                        <p class="programs_list__detail__point">
                            @if ($point->fee_type == 2)
                                購入額の
                                <span class="large">{{ strval($point->rate_percent) }}</span><span>%P</span>
                            @else
                                <span class="large">{{ $point->fee_label_s }}</span><span>P</span>
                            @endif
                        </p>
                    </div>
                </div>
            </li>
            @endforeach
        </ul><!-- programs_list__list__wrap -->
    </div><!-- programs_list__list -->
    @endif
</section><!--/shopping_contents-->
<script type="text/javascript">
    let swiper1 = new Swiper('.swiper1', {
        loop: true,
        speed: 500,
        slidesPerView: 'auto',
        spaceBetween: 10,
        centeredSlides : true,
        initialSlide: 1,
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
</script>
@endsection
