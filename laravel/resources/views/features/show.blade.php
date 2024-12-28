@php
$base_css_type = 'feature';
@endphp
@extends('layouts.default')


@section('layout.title', $feature_category_data->meta_title . '｜ポイントサイトならGMOポイ活')
@section('layout.keywords', $feature_category_data->meta_keywords)
@section('layout.description', 'GMOポイ活がオススメする' . $feature_category_data->meta_title . '！' . $feature_category_data->detail)
@section('url', route('features.show', ['feature_id' => $feature_category->id]) )
@section('og_type', 'website')

@if (isset($feature_category_data->header_img_ogp))
    @section('og_image', $feature_category_data->header_img_ogp)
    @section('twitter_image', $feature_category_data->header_img_ogp)
@endif

@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
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
            {{ Tag::link(route('features.index'), '特集一覧') }}
        </li>
        <li>
            {{ $feature_category->title }}
        </li>
    </ol>
</section>
@endsection

@section('layout.content')

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

<div class="contents">
    <h2>
        {{ Tag::image($feature_category_data->header_img_url, $feature_category_data->header_img_alt) }}
    </h2>

    @if (empty($feature_program_list))
    <div class="end"><p>この特集は終了しました</p></div>
    @else
    @if (!$feature_sub_category_list->isEmpty())
        <p class="feature__intro">{{ $feature_category_data->detail }}</p>
        @if (!$feature_sub_category_list->isEmpty())
        <div class="feature__index">
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
        </div>
        @endif
    @endif

    @if (isset($feature_program_list[0]))
    <section class="feature__sec pickup js-feature-sec" id="pickup">
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
                            <p class="fee_condition">
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
                            <p class="fee_condition">
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
            <li class="feature__item margin">
            </li>
            <li class="feature__item margin">
            </li>
        </ul>
    </section>
    @endif

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
                            <p class="fee_condition">
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
                            <p class="fee_condition">
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
            <li class="feature__item margin">
            </li>
            <li class="feature__item margin">
            </li>
        </ul>
        </section>

        @endif
        @endforeach
    @endif
    @endif
</div>
@endsection

@section('layout.footer.sale')

@php
$today_only_time_sale_program = \App\Program::ofTimeSale(true)
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->first();

$time_sale_query = \App\Program::ofTimeSale()
    ->ofSort(\App\Program::DEFAULT_SORT)
    ->take(8);
if (isset($today_only_time_sale_program->id)) {
    $time_sale_query = $time_sale_query
        ->where('id', '<>', $today_only_time_sale_program->id);
}
$time_sale_program_list = $time_sale_query->get();
$tmp = WrapPhp::count($time_sale_program_list);
@endphp

@if (!$time_sale_program_list->isEmpty())
<section class="footer-sale" data-tmp="{{ $tmp }}">
    <div class="footer-sale__inner">
        <h3 class="feature__sec__ttl js-feature-sec-ttl">高還元セール<span class="strong">期間限定UP中!</span></h3>
        <div class="footer-sale__swiper__wrap">
            @php
            $clsSwiper = 'footer-sale__swiper';
            $clsNoSwiper = '';
            $clsItem = '';
            if (WrapPhp::count($time_sale_program_list) < 5) {
                $clsSwiper = '';
                $clsItem = 'item';
                $clsNoSwiper = 'footer-sale__no_swiper';
            }
            @endphp
            <div class="swiper-container {{ $clsSwiper }}">
                <ul class="swiper-wrapper {{ $clsNoSwiper }}">
                    @foreach($time_sale_program_list as $program)
                        @php
                        // アフィリエイト情報
                        $affiriate = $program->affiriate;
                        // ポイント
                        $point = $program->point;
                        @endphp
                        <li class="swiper-slide {{ $clsItem }}">
                            <div class="feature__item js-feature-item js-sale">
                                <a href="{{ route('programs.show', ['program'=> $program]) }}">
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
                                            <p class="fee_condition">
                                                {{ $program->fee_condition }}
                                            </p>
                                        </div>
                                        <div class="primary">
                                            <div class="point">
                                                <p class="original js-sale-tag
                                                    {{ ($point->previous_point->fee_label_s == null || $point->previous_point->fee_label_s == 0) ? 'visibility-none' : '' }}">{!! $point->previous_point->fee_label_s_feature !!}P</p>
                                                <p class="special">{!! $point->fee_label_s_feature !!}<span class="feature__show_P">P</span></p>
                                            </div>
                                            <div class="btn">詳細</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if(WrapPhp::count($time_sale_program_list) > 4)
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            @endif
        </div>

        @if(WrapPhp::count($time_sale_program_list) > 4)
            <a class="feature__list__btn" href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['タイムセール']]) }}">高還元セールをすべて見る</a>
        @endif
    </div>
</section>
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
		spaceBetween: 15,
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
{!! Tag::script('/js/feature.js', ['type' => 'text/javascript']) !!}
{!! Tag::script('/js/feature-sale.js', ['type' => 'text/javascript']) !!}
@endsection

