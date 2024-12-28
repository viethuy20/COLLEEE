<?php $base_css_type = 'signup'; ?>
@extends('layouts.default')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活会員登録,無料')
@section('layout.description', 'GMOポイ活への会員登録ができます。登録はもちろん無料！会員になることで、ポイントを貯めたり、貯まったポイントを現金やギフトコードに交換できたりと、サイトをよりお得に利用できます。')
@section('url', route('entries.index'))
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('website.index');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '/entries/debut"},';

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
@section('layout.content')
<!-- page title -->
<div class="inner u-mt-20">
    <h2>{{ Tag::image('/images/debut/debut_ttl_sp.png', 'ご入会ありがとうございます！GMOポイ活へようこそ♪') }}</h2>
</div>

<!-- ポイントをゲットするには？ -->
<div class="inner">
    <div class="u-mt-small">
    <h2>{{ Tag::image('/images/debut/debut_recommend.jpg', 'GMOポイ活ビギナー向けプログラム特集', ['style' => 'border-radius: 6px; margin-top: 10px;']) }}</h2>

    @php
    $feature_id = 3958;
    $feature_program_list = App\FeatureProgram::ofCategory($feature_id)
        ->where('status', '=', 0)
        ->orderBy('priority', 'asc')
        ->get()
        ->mapToGroups(function ($item) {
            $now = Carbon\Carbon::now();
            if (!$item['program'] || !$item['program']->is_enable) {
                return [];
            }
            return [($item['sub_category_id'] ?? 0) => $item];
        });
    @endphp
    @if (isset($feature_program_list[0]))
    <section class="feature__sec feature__sec__debut">
        <ul class="feature__list">
        @foreach ($feature_program_list[0] as $feature_program)
            @php
            $program = $feature_program->program;
            $affiriate = $program->affiriate;
            $point = $program->point;
            @endphp
            <li class="feature__item js-feature-item {{ ($point->stop_at && $point->time_sale == 1) ? 'js-sale' : '' }}">
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    @if($point->stop_at && $point->time_sale == 1)
                    <div class="feature__item__counter counter" timestamp="{{ $point->stop_at->timestamp }}">
                        <p class="date">あと<span class="countDownDay">00</span>日</p>
                        <p><span class="countDownTime">00:00:00</span></p>
                    </div>
                    <div class="feature__item__img">
                        {{ Tag::image($affiriate->img_url, $program->title) }}
                    </div>
                    <div class="feature__item__detail">
                        <div class="txt">
                            <p class="headline">{{ $program->title }}</p>
                            <p>
                                {{ $program->fee_condition }}
                            </p>
                        </div>
                        <div class="primary">
                            <div class="point">
                                <p class="original js-sale-tag
                                {{ ($point->previous_point->fee_label_s == null || $point->previous_point->fee_label_s == 0) ? 'visibility-none' : '' }}">{!! $point->previous_point->fee_label_s !!}P</p>
                                <p class="special">{!! $point->fee_label_s_feature !!}<span class="feature__show_P">P</span></p>
                            </div>
                            <div class="btn">詳細</div>
                        </div>
                    </div>
                    @else
                    <div class="feature__item__img">
                        {{ Tag::image($affiriate->img_url, $program->title) }}
                    </div>
                    <div class="feature__item__detail">
                        <div class="txt">
                            <p class="headline">{{ $program->title }}</p>
                            <p>
                                {{ $program->fee_condition }}
                            </p>
                        </div>
                        <div class="primary">
                            <div class="point">
                                <p><span class="feature__show__item__point">{!! $point->fee_label_s_feature !!}</span><span class="feature__show_P">P</span></p>
                            </div>
                            <div class="btn">詳細</div>
                        </div>
                    </div>
                    @endif
                </a>
            </li>
        @endforeach
        </ul>
    </section>
    @endif
    <div class="debut__type__btn" style="margin-top: 20px;">
        <a href="{{ route('features.show', ['feature_id' => $feature_id]) }}">おすすめプログラム特集へ</a>
    </div>
    </div>
    <div class="contents__box u-mt-small">
        <h2 class="debut__get__ttl">ポイントを<br><span>ゲット</span>するには？</h2>
        <div class="debut__get__list">
            <ul>
                <li>
                    <a href="{{ route('programs.list') }}">
                        <div class="txt">広告でゲット！</div>
                        <div class="img">{{ Tag::image('/images/debut/get_1_sp.png', '広告でゲット！') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sp_programs.index').'#game' }}">
                        <div class="txt">ゲームでゲット！</div>
                        <div class="img">{{ Tag::image('/images/debut/get_2_sp.png', 'ゲームでゲット！') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('questions.index') }}">
                        <div class="txt">アンケートでゲット！</div>
                        <div class="img">{{ Tag::image('/images/debut/get_3_sp.png', 'アンケートでゲット！') }}</div>
                    </a>
                </li>
                <li>
                    <a href="/article">
                        <div class="txt">記事に口コミしてゲット！</div>
                        <div class="img">{{ Tag::image('/images/debut/get_4_sp.png', '記事に口コミしてゲット！') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('friends.index') }}">
                        <div class="txt">お友達紹介でゲット！</div>
                        <div class="img">{{ Tag::image('/images/debut/get_5_sp.png', 'お友達紹介でゲット！') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('fancrew.pages') }}">
                        <div class="txt">お店でゲット！</div>
                        <div class="img">{{ Tag::image('/images/debut/get_6_sp.png', 'お店でゲット！') }}</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- おすすめ広告はコチラ！ -->
@php
$time_sale_program_list = \App\Program::ofTimeSale()
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->take(4)
    ->get();
@endphp
@if (!$time_sale_program_list->isEmpty())
<div class="inner">
    <div class="u-mt-small">
        <h2 class="debut__recommend__ttl">GMOポイ活おすすめ広告はコチラ！<br><span>ポイントアップ中</span></h2>
        <section class="feature__sec feature__sec__debut">
        <ul class="feature__list">
        @foreach ($time_sale_program_list as $program)
            @php
            $affiriate = $program->affiriate;
            $point = $program->point;
            @endphp
            <li class="feature__item js-feature-item {{ ($point->stop_at && $point->time_sale == 1) ? 'js-sale' : '' }}">
                <a href="{{ route('programs.show', ['program' => $program]) }}">
                    @if($point->stop_at && $point->time_sale == 1)
                    <div class="feature__item__counter counter" timestamp="{{ $point->stop_at->timestamp }}">
                        <p class="date">あと<span class="countDownDay">00</span>日</p>
                        <p><span class="countDownTime">00:00:00</span></p>
                    </div>
                    <div class="feature__item__img">
                        {{ Tag::image($affiriate->img_url, $program->title) }}
                    </div>
                    <div class="feature__item__detail">
                        <div class="txt">
                            <p class="headline">{{ $program->title }}</p>
                            <p>
                                {{ $program->fee_condition }}
                            </p>
                        </div>
                        <div class="primary">
                            <div class="point">
                                <p class="original js-sale-tag
                                {{ ($point->previous_point->fee_label_s == null || $point->previous_point->fee_label_s == 0) ? 'visibility-none' : '' }}">{!! $point->previous_point->fee_label_s !!}P</p>
                                <p class="special">{!! $point->fee_label_s_feature !!}<span class="feature__show_P">P</span></p>
                            </div>
                            <div class="btn">詳細</div>
                        </div>
                    </div>
                    @else
                    <div class="feature__item__img">
                        {{ Tag::image($affiriate->img_url, $program->title) }}
                    </div>
                    <div class="feature__item__detail">
                        <div class="txt">
                            <p class="headline">{{ $program->title }}</p>
                            <p>
                                {{ $program->fee_condition }}
                            </p>
                        </div>
                        <div class="primary">
                            <div class="point">
                                <p><span class="feature__show__item__point">{!! $point->fee_label_s_feature !!}</span><span class="feature__show_P">P</span></p>
                            </div>
                            <div class="btn">詳細</div>
                        </div>
                    </div>
                    @endif
                </a>
            </li>
        @endforeach
        </ul>
    </section>
    <div class="debut__type__btn" style="margin-top: 20px;">
        <a href="/programs/list?keywords=%E3%82%BF%E3%82%A4%E3%83%A0%E3%82%BB%E3%83%BC%E3%83%AB">タイムセールをもっと見る</a>
    </div>
    </div>
</div>
@endif

<!-- タイプ別広告をご紹介 -->
<div class="inner">
    <div class="contents__box u-mt-small">
        <h2 class="debut__type__ttl">{{ Tag::image('/images/debut/type_ttl_img_3.png', 'ガッツリ派？コツコツ派？') }}<br>タイプ別広告を<br>ご紹介</h2>
        <div class="debut__type__list">
            <ul>
                <li>
                <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['高還元']]) }}">
                    <div class="img">{{ Tag::image('/images/debut/type_1.png', '高還元') }}</div>
                    <div class="txt">高還元</div>
                </a>
                <li>
                    <a href="{{ route('credit_cards.list') }}">
                        <div class="img">{{ Tag::image('/images/debut/type_2.png', 'クレカ') }}</div>
                        <div class="txt">クレカ</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('shops.index') }}">
                        <div class="img">{{ Tag::image('/images/debut/type_3.png', 'ショッピング') }}</div>
                        <div class="txt">ショッピング</div>
                    </a>
                </li>
                <li>
                    <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [80]]) }} ">
                        <div class="img">{{ Tag::image('/images/debut/type_4.png', '無料案件') }}</div>
                        <div class="txt">無料案件</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sp_programs.index').'#game' }}">
                        <div class="img">{{ Tag::image('/images/debut/type_5.png', 'ゲーム') }}</div>
                        <div class="txt">ゲーム</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="debut__type__btn"><a href="{{ route('website.index') }}">トップページから探す</a></div>
    </div>
</div>

<script type="text/javascript">
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
</script>
{!! Tag::script('/js/sp_feature-sale.js', ['type' => 'text/javascript']) !!}
@endsection
