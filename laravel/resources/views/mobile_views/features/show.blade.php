@php
$base_css_type = 'feature';
@endphp
@extends('layouts.default')

@section('layout.title', $feature_category_data->meta_title.' | ポイントサイトならGMOポイ活')
@section('layout.keywords', $feature_category_data->meta_keywords)
{{-- @section('layout.description', 'GMOポイ活がオススメする' . $feature_category_data->meta_title . '！GMOポイ活経由で利用するだけでポイントを貯めることができます。貯めたポイントは、現金やギフト券に交換することができます。') --}}
@section('layout.description', 'GMOポイ活がオススメする' . $feature_category_data->meta_title . '！' . $feature_category_data->detail)
@section('url', route('features.show', ['feature_id' => $feature_category->id]) )
@section('og_type', 'website')

@if (isset($feature_category_data->header_img_ogp))
    @section('og_image', $feature_category_data->header_img_ogp)
    @section('twitter_image', $feature_category_data->header_img_ogp)
@endif

@section('layout.breadcrumbs')
    <section class="header__breadcrumb">
        <ol>
            <li>
                <a href="{{route('website.index')}}">ホーム </a>
            </li>
            <li>
                <a href="{{route('features.index')}}">特集一覧</a>
            </li>
            <li>{{ $feature_category->title }}</li>
        </ol>
    </section>
@endsection

@section('layout.content')
<div class="contents">

    <h2 class="feature__thumb">{{ Tag::image($feature_category_data->header_img_url, $feature_category_data->header_img_alt) }}</h2>
    <p class="feature__intro">{{ $feature_category_data->detail }}</p>

    @if (empty($feature_program_list))
        <div class="end"><p>この特集は終了しました</p></div>
    @else
        <div class="feature__index js-index">

            @if (!$feature_sub_category_list->isEmpty())
            <ul class="feature__index__list">
                @if (isset($feature_program_list[0]))
                <li>{{ Tag::link($sub_category->url ?? '#pickup', 'イチオシ') }}</li>
                @endif

                @foreach ($feature_sub_category_list as $sub_category)
                @if (!empty($feature_program_list[$sub_category->id]) || isset($sub_category->url))
                <li>{{ Tag::link($sub_category->url ?? '#category'.$sub_category->id, $sub_category->title) }}</li>
                @endif
                @endforeach
            </ul>
            @endif
        </div>

        @php
        $dNone = '';
        if (!isset($feature_program_list[0])) {
            $dNone = 'd-none';
        }
        @endphp
        <section class="feature__sec pickup js-feature-sec {{ $dNone }}" id="pickup">
        @if (isset($feature_program_list[0]))
            <h3 class="feature__sec__ttl js-feature-sec-ttl">ポノスケのイチオシ広告</h3>
            <ul class="feature__list">
                @foreach ($feature_program_list[0] as $pickup_program)
                    @php
                    $program = $pickup_program->program;
                    $affiriate = $program->affiriate;
                    $point = $program->point;
                    @endphp
                <li class="feature__item js-feature-item {{ ($point->stop_at && $point->time_sale == 1) ? 'js-sale' : '' }}">
                    <a href="{{ route('programs.show', ['program' => $program, 'rid' => $feature_category_data->rid]) }}">
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
        @endif
        </section>

        <script>
            window.addEventListener('load', (event) => {
                const boxes = document.querySelectorAll(".feature__list li");
                boxes.forEach(box => {
                    box.addEventListener('click', function() {
                        location.href =  box.firstChild.nextSibling.href;
                    });
                });
            });
        </script>

        @if (!$feature_sub_category_list->isEmpty())
                @foreach ($feature_sub_category_list as $sub_category)
                    @if (!empty($feature_program_list[$sub_category->id]))
                    <section class="feature__sec js-feature-sec" id="category{{ $sub_category->id }}">
                        <h3 class="feature__sec__ttl js-feature-sec-ttl">{{ $sub_category->title }}</h3>
                        <ul class="feature__list">
                            @foreach ($feature_program_list[$sub_category->id] as $sub_category_program)
                            @php
                            $program = $sub_category_program->program;
                            $affiriate = $program->affiriate;
                            $point = $program->point;
                            @endphp
                            <li class="feature__item js-feature-item {{ ($point->stop_at && $point->time_sale == 1) ? 'js-sale' : '' }}">
                                <a href="{{ route('programs.show', ['program' => $program, 'rid' => $feature_category_data->rid]) }}">
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
                @endforeach
        @endif
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
<script>
	let saleSwiper = new Swiper('.footer-sale__swiper', {
		loop: true,
		speed: 500,
		slidesPerView: 'auto',
		initialSlide: 1,
		effect:'slide',
		slidesPerView: 4,
		spaceBetween: 20,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		autoplay: {
			delay: 4000,
			disableOnInteraction: false,
		},
	});
</script>

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

{!! Tag::script('/js/sp_feature.js', ['type' => 'text/javascript']) !!}
{!! Tag::script('/js/sp_feature-sale.js', ['type' => 'text/javascript']) !!}
@endsection
