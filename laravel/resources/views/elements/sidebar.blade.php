@php
$today_only_time_sale_program = \App\Program::ofTimeSale(true)
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->first();

$time_sale_query = \App\Program::ofTimeSale()
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->take(2);
if (isset($today_only_time_sale_program->id)) {
    $time_sale_query = $time_sale_query
        ->where('id', '<>', $today_only_time_sale_program->id);
}
$time_sale_program_list = $time_sale_query->get();
@endphp

<script type="text/javascript"><!--
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

@if (Route::currentRouteName() === 'website.index' || Route::currentRouteName() === 'features.show')

@else
@if (isset($today_only_time_sale_program->id))
@if($_SERVER['REQUEST_URI'] == '/questions')
    <h2 class="contents__ttl">毎日1ポイント貯まる！</h2>
    <ul class="sidebar__bnr">
        <li><a href="/questions/list"><img src="{{ asset('/images/questions/GMOquestions_pc.png')}}" alt="毎日更新!GMOポイ活アンケート かんたんアンケートで1日1ポイント 今日の分をチェック"></a></li>
    </ul>
@endif
<h2 class="contents__ttl">高還元セール<span class="sidebar__sale__tag">期間限定UP中！</span></h2>
<ul class="sidebar__sale__list">
    @php
    // アフィリエイト情報
    $affiriate = $today_only_time_sale_program->affiriate;
    // ポイント
    $point = $today_only_time_sale_program->point;
    @endphp
    <li>
        <a href="{{ route('programs.show', ['program'=> $today_only_time_sale_program]) }}">
            <div class="sidebar__sale__list__thumb">{{ Tag::image($affiriate->img_url, $today_only_time_sale_program->title) }}</div>
            <p class="sidebar__sale__list__countdown counter" timestamp="{{ $point->stop_at->timestamp }}">
                残り
                <span class="countDownDay"></span>日
                <span class="countDownTime"></span>
            </p>
            <p class="sidebar__sale__list__ttl">{{ $today_only_time_sale_program->title }}</p>
            <p class="sidebar__sale__list__txt">{{ $today_only_time_sale_program->fee_condition }}</p>
            <p class="sidebar__sale__list__linethrough">{{ $point->previous_point->fee_label }}p</p>
            <p class="sidebar__sale__list__point arrow">{{ $point->fee_label }}P</p>
        </a>
    </li>
</ul>
@endif

@if (!$time_sale_program_list->isEmpty())
@if($_SERVER['REQUEST_URI'] == '/questions')
    <h2 class="contents__ttl">毎日1ポイント貯まる！</h2>
    <ul class="sidebar__bnr">
        <li><a href="/questions/list"><img src="{{ asset('/images/questions/GMOquestions_pc.png')}}" alt="毎日更新!GMOポイ活アンケート かんたんアンケートで1日1ポイント 今日の分をチェック"></a></li>
    </ul>
@endif
<h2 class="contents__ttl">高還元セール<span class="sidebar__sale__tag">期間限定UP中！</span></h2>
<ul class="sidebar__sale__list">
    @foreach($time_sale_program_list as $program)
    @php
    // アフィリエイト情報
    $affiriate = $program->affiriate;
    // ポイント
    $point = $program->point;
    @endphp
    <li>
        <a href="{{ route('programs.show', ['program'=> $program]) }}">
            <dl class="clearfix">
                <div class="sidebar__sale__list__thumb">{{ Tag::image($affiriate->img_url, $program->title) }}</div>
                <dd class="sidebar__sale__list__countdown counter" timestamp="{{ $point->stop_at->timestamp }}">
                    残り
                    <span class="countDownDay"></span>日
                    <span class="countDownTime"></span>
                </dd>
                </dt>
                <p class="sidebar__sale__list__ttl">{{ $program->title }}</p>
                <p class="sidebar__sale__list__txt">{!! $program->description !!}</p>
                <p class="sidebar__sale__list__linethrough">{{ $point->previous_point->fee_label }}p</p>
                <p class="sidebar__sale__list__point arrow">{{ $point->fee_label }}P</p>
            </dl>
        </a>
    </li>
    @endforeach
</ul>
<div class="sidebar__sale__btn">
    {{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['タイムセール']]), 'もっと見る') }}
</div>
@endif
@endif

@include('elements.category')

@php
$feature_content_list = \App\Content::ofSpot(\App\Content::SPOT_FEATURE)
    ->orderBy('start_at', 'asc')
    ->limit(3)
    ->get();
@endphp
@if (!$feature_content_list->isEmpty())
    <div class="contents__ttl">特集</div>
    <ul class="sidebar__bnr">
        @foreach ($feature_content_list as $content)
        @php
        $content_data = $content->json_data;
        @endphp
        <li>{{ Tag::link($content_data->url, Tag::image($content_data->img_url, $content->title), null, null, false) }}</li>
        @endforeach
    </ul>
@endif

<div class="contents__ttl">サポートメニュー</div>
<ul class="sidebar__list">

    <li>{{ Tag::link('/support/?cat=8', "<i>".Tag::image("/images/common/ico_sprt_support.svg")."</i>お知らせ", [], null, false) }}</li>
    <li>{{ Tag::link('/help', "<i>".Tag::image("/images/common/ico_sprt_contact.svg")."</i>ヘルプセンター", [], null, false) }}</li>

</ul>
<ul class="sidebar__bnr">
    <li>{{ Tag::link(route('beginners'), Tag::image("/images/common/bnr_howto.png", 'GMOポイ活の使い方'), null, null, false) }}</li>
    <li>{{ Tag::link(route('stops'), Tag::image("/images/common/bnr_stop.png", 'STOP!不正行為'), null, null, false) }}</li>
</ul>

@include('elements.opinionbox')
