@php
$base_css_type = 'programs_list';
@endphp
@extends('layouts.default')

@section('layout.head')
@if ($paginator->currentPage() > 1)
<link rel="prev" href="{{ $condition->getListUrl((object) ['page' => $paginator->currentPage() + 1]) }}" />
@endif
@if (($paginator->currentPage() < $paginator->lastPage()))
<link rel="next" href="{{ $condition->getListUrl((object) ['page' => $paginator->currentPage() + 1]) }}" />
@endif
<script type="text/javascript"><!--
    $(function(){
        $('#sortBox').on('change', function(event) {
            if ($(this).val() != '') {
                window.location.href = getScrollUrl($(this).val(), event);
            }
        });
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
//-->
</script>
@endsection

@php
$accept_days_map = config('map.accept_days');

$keyword_list = $condition->getParam('keyword_list');
$shop_category_id = $condition->getParam('shop_category_id');
$all_back = $condition->getParam('all_back');
$label_id_list = $condition->getParam('ll');
if (!empty($label_id_list)) {
    $label_name_list = \App\Label::whereIn('id', $label_id_list)->pluck('name')->all();
}
@endphp

@if (isset($keyword_list))
@php
$keywordtxt =  implode(' ', $condition->getParam('keyword_list'));
@endphp
@section('layout.title')
{{ $keywordtxt }}の検索結果 @if ($paginator->total() > 0)（{{ $paginator->currentPage() }}ページ目）@endif | ポイントサイトならGMOポイ活 @endsection

@section('layout.description', $keywordtxt.'の検索結果 | ' . $keywordtxt. 'ならGMOポイ活がお得 | 貯めたポイントは現金やギフト券に交換することができます。')
@else
@php
$label_name = !empty($label_name) ? $label_name : '広告検索結果';
@endphp
@section('layout.title')
{{ $label_name }}の広告一覧 @if ($paginator->total() > 0)（{{ $paginator->currentPage() }}ページ目）@endif | ポイントサイトならGMOポイ活 @endsection
{{-- @section('layout.description', 'GMOポイ活から参加できる広告が一覧で表示されます。 | 貯めたポイントは現金やギフト券に交換することができます。') --}}
@section('layout.description', $meta_description)
@endif

@section('layout.keywords', 'GMOポイ活,広告')
@section('layout.url', url($condition->getListUrl()))

@section('layout.breadcrumbs')
    @if(WrapPhp::count($arr_breadcrumbs) > 0)
        <section class="header__breadcrumb">
            <ol>
                @foreach($arr_breadcrumbs as $item)
                    <li>
                        <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                    </li>
                @endforeach
                <li>
                    @if($sort != 0)
                    広告検索結果の広告一覧{{ ( $paginator->total() > 0 ? '（' . $paginator->currentPage() . 'ページ目）' : '') }}
                    @else
                    {{ empty($label_name) ? $keywordtxt.'に関連する広告一覧'. ( $paginator->total() > 0 ? '（' . $paginator->currentPage() . 'ページ目）' : '')
                    : ($label_name . ( $paginator->total() > 0 ? '（' . $paginator->currentPage() . 'ページ目）' : '')) }}
                    @endif
                </li>
            </ol>
        </section>
    @endif
@endsection

@section('layout.content')

<div class="contents">
    @if ($paginator->total() > 0)
    {{-- 検索結果あり--start --}}
        <div class="programs_list__ttl">
            <h1 class="contents__ttl">@if(!empty($label_name)) {{$label_name}}の広告一覧（{{ $paginator->currentPage() }}ページ目）
                  @else {{$keywordtxt}}に関連する広告一覧（{{ $paginator->currentPage() }}ページ目） @endif</h1>
            <div class="programs_list__select">
                @php
                $sort_value = $condition->getListUrl((object) ['sort' => $condition->getParam('sort') ?? 0, 'page' => 1]);
                $sort_map = [$condition->getListUrl((object) ['sort' => 0, 'page' => 1]) => '&nbsp;おすすめ順',
                $condition->getListUrl((object) ['sort' => 1, 'page' => 1]) => '&nbsp;ポイント数順',
                $condition->getListUrl((object) ['sort' => 2, 'page' => 1]) => '&nbsp;ポイント率順',
                $condition->getListUrl((object) ['sort' => 3, 'page' => 1]) => '&nbsp;新着順'];
                @endphp
                {{ Tag::formSelect('', $sort_map, $sort_value, ['id' => 'sortBox']) }}
            </div> <!-- programs_list__select -->
        </div> <!-- programs_list__ttl -->

        <div class="programs_list__list">
            <ul class="programs_list__list__wrap">

            @foreach ($paginator as $program)
            @php
            // アフィリエイト
            $affiriate = $program->affiriate;
            // ポイント
            $point = $program->point;
            // タイムセール TODO
            $is_time_sale = $point->time_sale;
            $show_url = route('programs.show', ['program'=> $program]);
            @endphp
                <li>
                    <a class="programs_list__detail" href="{{ $show_url }}">
                        <div class="programs_list__detail__l">
                            <div class="programs_list__detail__thumb">
                                {{ Tag::image($affiriate->img_url, $program->title) }}
                            </div><!-- programs_list__detail__thumb" -->
                        </div><!-- programs_list__detail__l" -->
                        <div class="programs_list__detail__r">
                            <p class="programs_list__detail__txt">{{ $program->title }}</p>
                            <ul class="programs_list__detail__tag">
                                @foreach ($program->tag_list as $tag)
                                    <li><span>{{ $tag }}</span></li>
                                @endforeach
                            </ul>
                            <dl class="programs_list__detail__chart">
                                <dt>獲得条件</dt>
                                <dd>{{ $program->fee_condition }}</dd>
                                <dt>獲得までの期間</dt>
                                <dd>{{ $accept_days_map[$affiriate->accept_days] }}</dd>
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
                                    {{ number_format($program->review_total) }}
                                    @else
                                    0件
                                    @endif
                                </li>
                            </ul>
                            @if($is_time_sale)<!--タイムセール処理の分岐 -->
                                <p class="programs_list__detail__point__countdown counter" timestamp="{{ $point->stop_at->timestamp }}">
                                    残り
                                    <span class="countDownDay"></span>
                                    日
                                    <span class="countDownTime"></span>
                                </p>
                                <p class="programs_list__detail__point__linethrough">{{ $point->previous_point->fee_label }}P</p>
                            @endif
                            <p class="programs_list__detail__point">
                                @if ($is_time_sale)<!--タイムセール処理の分岐 -->
                                    <img src="{{ asset('/images/common/ico_arrow.svg')}}">
                                @endif
                                @if ($point->fee_type == 2)
                                    購入額の
                                @endif
                                <span class="large">{{ $point->fee_label_s }}</span>
                                <span>P</span>
                            </p>
                        </div><!--programs_list__detail__r -->
                    </a><!-- programs_list__detail -->
                </li>
            @endforeach
            </ul><!-- programs_list__list__wrap -->
        </div><!-- programs_list__list -->
    @else
    {{-- 検索結果なし--start --}}
        <div class="programs_list__ttl">
            <h2 class="contents__ttl">関連する広告一覧&nbsp;</h2>
        </div><!-- programs_list__ttl -->
        <div class="programs_list__list">
            <div class="programs_list__detail">
                <p class="programs_list__notfound">関連する広告が<br />見つかりませんでした…</p>
            </div><!-- programs_list__detail -->
        </div><!-- programs_list__list -->
    @endif
    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) use($condition) { return $condition->getListUrl((object) ['page' => $page]); }]) !!}
        @if ($paginator->total() > 0)
            @php
                $links_for_category = [
                              125 => ['link_1' => '/article/category/creditcard/', 'text_link_1' => 'クレジットカードの新着お得情報を見る'],
                              131 => ['link_1' => '/article/category/shopping/', 'text_link_1' => 'ショッピングの新着お得情報を見る'],
                              135 => ['link_1' => '/article/category/furusato/', 'text_link_1' => 'ふるさと納税の新着お得情報を見る'],
                              136 => ['link_1' => '/article/category/application/', 'text_link_1' => 'アプリの新着お得情報を見る']
                            ];

                $start = 109;
                $end = 121;
                $default = ['link_1' => '/article/category/shopping/', 'link_2' => '/article/category/campaignlist/',
                'text_link_1' => 'ショッピングの新着お得情報を見る', 'text_link_2' => 'キャンペーンの新着お得情報を見る'];

                foreach (range($start, $end) as $i) {
                    $links_for_category[$i] = $default;
                }
                ksort($links_for_category);
            @endphp
            @if (!empty($label_id_list[0]) && in_array($label_id_list[0],  array_keys($links_for_category)))
                @php
                    $link_1 = $links_for_category[$label_id_list[0]]['link_1'] ?? '';
                    $link_2 = $links_for_category[$label_id_list[0]]['link_2'] ?? '';
                    $text_1 = $links_for_category[$label_id_list[0]]['text_link_1'] ?? '';
                    $text_2 = $links_for_category[$label_id_list[0]]['text_link_2'] ?? '';
                @endphp
                <div class="programs_list__list">
                    <h2 class="contents__ttl u-mt-40">関連コンテンツ</h2>
                    <div class="article__cat" id="article__cat">
                        <ul>
                            <li><a href="{{ $link_1 }}">{{$text_1}}</a></li>
                            @if ($link_2)
                                <li><a href="{{ $link_2 }}">{{$text_2}}</a></li>
                            @endif
                            @if($ll[0] == 125)
                            <li>
                                <a href="{{ route('credit_cards.list') }}">クレジットカード徹底比較を見る</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div><!-- inner -->
            @endif
        @endif
</div> <!-- contents -->

@endsection
