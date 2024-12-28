@extends('layouts.base')

@section('layout.plane.head')
    @php
        $categoryColletsions = [
            [
                'ttl' => '新着',
                'image' => 'ico_hd_cat_new.svg',
                'link' => \App\Search\ProgramCondition::getStaticListUrl((object) ['sort' => 3]),
            ],
            [
                'ttl' => '高還元',
                'image' => 'ico_hd_cat_sale.svg',
                'link' => \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['高還元']]),
            ],
            [
                'ttl' => '無料',
                'image' => 'ico_hd_cat_foc.svg',
                'link' => \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [80]]),
            ],
            [
                'ttl' => '即日還元',
                'image' => 'ico_hd_cat_soku.svg',
                'link' => \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [78]]),
            ],
            [
                'ttl' => 'アンケート',
                'image' => 'ico_hd_cat_pen.svg',
                'link' => route('questions.index'),
            ],
            [
                'ttl' => 'レシ活',
                'image' => 'ico_hd_receipt_color.svg',
                'link' => route('receipt.list'),
            ],
            [
                'ttl' => 'ポイ活<br>お得情報',
                'image' => 'ico_hd_cat_book.svg',
                'link' => '/article',
            ],
            [
                'ttl' => 'ポイント<br><span>（貯まる・使えるお店）</span>',
                'image' => 'ico_hd_cat_point.svg',
                'link' => '/article/category/pointlist/',
            ],
            [
                'ttl' => 'キャンペーン<br>情報',
                'image' => 'ico_hd_cat_camp.svg',
                'link' => '/article/category/campaignlist/',
            ],
        ];
    @endphp

    <script type="text/javascript">
        <!--
        // header hamburger inner
        $(function() {
            var menu_btn = $('.header__menu__btn');
            var menu_contents = $('.header__menu__contents');
            var hum_btn_inr = $('.header__menu__btn__hamburger');

            // 3本線メニューをクリックしたらメニューを開閉し、3本線が×になる
            menu_btn.on('click', function() {
                menu_contents.toggleClass('header__active');
                hum_btn_inr.toggleClass('hum__active');
                return false;
            });

            // メニューの外側をクリックしたらメニューを閉じる
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.header__menu__contents').length) {
                    menu_contents.removeClass('header__active');
                    hum_btn_inr.removeClass('hum__active');
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // header search inner
            $(function() {
                var search_btn = $('.header__search__btn');
                var search_contents = $('.header__search__contents');
                function getBodyTop() {
                    return parseInt($('body').css('top'), 10) || 0;
                }
                function setSearchContentPosition() {
                    var bodyTop = getBodyTop();
                    var btnPos = search_btn.offset();
                    search_contents.css({
                        top: btnPos.top + search_btn.outerHeight() - bodyTop,
                    });
                }
                // サブメニューの検索をクリックしたら検索エリアを開閉する
                search_btn.on('click', function() {
                    setSearchContentPosition();
                    search_contents.toggleClass('search__active');
                    search_btn.parent().toggleClass('current');
                    return false;
                });
                $(window).on('resize scroll', setSearchContentPosition);
                setSearchContentPosition();
                // 検索エリアの外側をクリックしたら検索エリアを閉じる
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.header__search__contents__inner').length) {
                        search_contents.removeClass('search__active');
                        search_btn.parent().removeClass('current');
                    }
                });
            });
        });
        // header search サービスから検索タブ
        $(function() {
            $(".header__search__contents__service__tab a").click(function() {
                $(this).parent().addClass("active").siblings(".active").removeClass("active");
                var header__search__contents__service__inner = $(this).attr("href");
                $(header__search__contents__service__inner).addClass("active").siblings(".active")
                    .removeClass("active");
                return false;
            });
        });

        // gnav 同一のURLであれば色をつける
        document.addEventListener('DOMContentLoaded', function() {

            if (document.getElementsByClassName('gnav') != null) {
                const currentUrl = location.href;
                const gnavItem = Array.from(document.getElementsByClassName('gnav__item'));
                gnavItem.forEach(function(target) {
                    target.classList.remove('current');
                    const gnavUrl = target.querySelector('a').href;
                    if (currentUrl === gnavUrl) {
                        target.classList.add('current');
                    }
                });
            }
        });

        $(function() {
            var scroll = purl(location.href).param('scroll');
            if (scroll === "" || scroll === null || scroll === undefined) {
                return;
            }

            var data = scroll.split(',');
            $(window).scrollLeft(data[0]);
            $(window).scrollTop(data[1]);
        });
        $(function() {
            $('a[class=save_scroll]').on('click', function(event) {
                // スクロール位置を持たせる
                $(this).attr('href', getScrollUrl($(this).attr('href'), event));
            });
            $('form[class=save_scroll]').on('submit', function(event) {
                // スクロール位置を持たせる
                $(this).children('input[name=scroll]').val(getScrollPos(event));
            });
        });
        $(function() {
            $(document).on('click', '.for_link', function(event) {
                if ($(this).attr('forUrl') != '') {
                    window.location.href = getScrollUrl($(this).attr('forUrl'), event);
                }
            });
        });
        $(function() {
            $('.u_text').collapser({
                mode: 'chars',
                truncate: 70,
                showText: '続きを読む',
                hideText: '閉じる'
            });
        });
        $(function() {
            $('.r_list:not(.r_list:first-of-type)').css('display', 'none');
            $('.more').nextAll('.more').css('display', 'none');
            $('.more').on('click', function() {
                $(this).css('display', 'none');
                $(this).next('.r_list').slideDown('fast');
                $(this).nextAll('.more:first').css('display', 'block');
            });
        });
        $(function() {
            $('.txt_blog').each(function() {
                var $target = $(this);

                // オリジナルの文章を取得する
                var html = $target.html();

                // 対象の要素を、高さにautoを指定し非表示で複製する
                var $clone = $target.clone();
                $clone.css({
                    display: 'none',
                    position: 'absolute',
                    overflow: 'visible'
                }).width($target.width()).height('auto');

                // DOMを一旦追加
                $target.after($clone);

                // 指定した高さになるまで、1文字ずつ消去していく
                while ((html.length > 0) && ($clone.height() > $target.height())) {
                    html = html.substr(0, html.length - 1);
                    $clone.html(html + '...');
                }

                // 文章を入れ替えて、複製した要素を削除する
                $target.html($clone.html());
                $clone.remove();
            });
        });

        $(document).on('click', ".AjaxContent", function(event) {
            if ($(this).is('select')) return;
            executeAjax($(this).attr('forUrl'), $('#' + $(this).attr('forRender')));
        });

        $(document).on('change', "select.AjaxContent", function(event) {
            executeAjax($(this).first().find('option:selected').attr('forUrl'), $('#' + $(this).first().find(
                'option:selected').attr('forRender')));
        });

        $(function() {
            $(".AjaxRender").each(function() {
                executeAjax($(this).attr('forUrl'), $(this));
            });
        });
        //
        -->
    </script>

    @if (($use_recaptcha ?? false) && \App\External\Google::getRecaptchaUse())
        <style type="text/css">
            .grecaptcha-badge {
                margin-bottom: 100px;
            }
        </style>
        {{ Tag::script(\App\External\Google::getRecaptchaJsUrl(), ['type' => 'text/javascript']) }}
        <script type="text/javascript">
            <!--
            $(function() {
                $('.{{ \App\External\Google::getRecaptchaClass() }}').submit(function() {
                    var form = $(this);
                    var input = form.find('input[name="{{ \App\External\Google::getRecaptchaParamKey() }}"]');
                    if (input.val() != '') {
                        return true;
                    }
                    var action = form.attr('forGoogleRecaptchaAction') || 'homepage';
                    grecaptcha.execute('{{ \App\External\Google::getRecaptchaSiteKey() }}', {
                            action: action
                        })
                        .then(function(token) {
                            // Verify the token on the server.
                            input.val(token);
                            form.submit();
                        });
                    return false;
                });
            });
            //
            -->
        </script>
    @endif
    @yield('layout.head')
@endsection
@php
$path = Request::path();
$pattern = '/^programs\/\d+/';
$arrRoute = ['entries.confirm', 'entries.post_confirm', 'entries.send', 'entries.create', 'entries.confirm_tel' , 'entries.question', 'entries.index', 'login.google.callback', 'login.line.callback', 'login'];
@endphp
@section('layout.plane.body')
    @if(in_array(Route::current()->getName(), $arrRoute) )
    <header class="header simple">
        <div class="header__logo">
            <h1><img src="/images/common/logo.png" alt="はじめてのポイ活はGMOポイ活"></h1>
        </div>
    </header>
    @else
    <header>
        <!-- top header wrapper -->
        @php
            //get top banner above header
            $top_banner_above_header = \App\Content::ofSpot(\App\Content::SPOT_BANNER_ABOVE_HEADER_PC)
                ->orderBy('priority', 'asc')
                ->orderBy('start_at', 'desc')
                ->limit(1)
                ->get();
        @endphp

        @if (!$top_banner_above_header->isEmpty())
            @php
                $content_data = json_decode($top_banner_above_header[0]['data']);
            @endphp
            @if (isset($base_css_type) && $base_css_type == 'top')
                @push('custom-css')
                <style>
                .header__search__contents {
                    top: 265px;
                }
                .header__menu__contents {
                    top: 200px;
                }
                </style>
                @endpush
                <div class="top-header-wrapper">
                    <a href="{{ $content_data->url }}">
                        {!! Tag::image($content_data->img_url, $top_banner_above_header[0]['title'], ['class' => 'top-header-content']) !!}
                    </a>
                </div>
            @endif
        @endif

        <!-- header main -->
        <section class="header__main__wrap">
            <div class="inner">
                <div class="header__main">
                    <div class="header__main__l">
                        <div class="header__logo">
                            {{ Tag::link(
                                route('website.index'),
                                Tag::image('/images/common/logo.png', 'はじめてのポイ活はGMOポイ活'),
                                [],
                                null,
                                false,
                            ) }}
                        </div>
                    </div>

                    <div class="header__main__r">
                        @include('elements.searchbox')

                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                            @endphp

                            <a href="{{ route('users.show') }}" class="header__mypage">
                                <div class="header__user">
                                    @if (isset($user->nickname))
                                        <div class="header__user__name">{{ $user->nickname }}さん</div>
                                    @else
                                        <div class="header__user__name">{{ $user->name }}さん</div>
                                    @endif
                                    <div class="header__user__point">
                                        <span>{{ number_format($user->point) }}</span>P
                                    </div>
                                </div>

                                <div class="header__exchange">
                                    <a href="{{ route('exchanges.index') }}" class="header__exchange__btn">
                                        <div class="header__exchange__btn__ico">
                                            {{ Tag::image('/images/common/ico_hd_exchange.svg') }}</div>
                                        <div class="header__exchange__btn__txt">ポイント交換</div>
                                    </a>
                                </div>
                            </a>
                        @else
                            <div class="header__exchange">
                                <a href="{{ route('entries.index') }}" class="header__exchange__btn">
                                    <div class="header__exchange__btn__ico">
                                        {{ Tag::image('/images/common/ico_hd_entry.svg') }}</div>
                                    <div class="header__exchange__btn__txt">新規登録</div>
                                </a>
                            </div>

                            <li class="header__exchange">
                                <a href="{{ route('login', ['back' => 0]) }}" class="header__exchange__btn">
                                    <div class="header__exchange__btn__ico">
                                        {{ Tag::image('/images/common/ico_hd_login.svg') }}</div>
                                    <div class="header__exchange__btn__txt">ログイン</div>
                                </a>
                            </li>
                        @endif

                        <div class="header__menu">
                            <button class="header__menu__btn" id="">
                                <div class="header__menu__btn__hamburger">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <div class="header__menu__btn__txt">MENU</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- hamburger inner -->
        <div class="header__menu__contents">
            <div class="inner">
                <div class="header__menu__contents__inner">
                    <ul class="header__menu__contents__list">
                        <li>{{ Tag::link('/support/?cat=8', 'お知らせ') }}</li>
                        <li>{{ Tag::link(route('programs.list', ['sort' => 3]), '新着') }}</li>
                        <li>{{ Tag::link(
                            \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['高還元']]),
                            '高還元',
                        ) }}
                        </li>
                        <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [80]]), '無料') }}
                        </li>
                    </ul>
                    <ul class="header__menu__contents__list">
                        <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [78]]), '即日還元') }}
                        </li>
                        <li>{{ Tag::link(route('questions.index'), 'アンケート') }}</li>
                        <li>{{ Tag::link(route('sp_programs.index') . '#game', 'ゲーム') }}</li>
                        <li>{{ Tag::link(route('shops.index'), '買い物') }}</li>
                    </ul>
                    <ul class="header__menu__contents__list">

                        <li>{{ Tag::link(route('fancrew.pages'), 'モニター') }}</li>
                        <li>{{ Tag::link(route('friends.index'), 'お友達紹介') }}</li>
                        <li>{{ Tag::link('/article', 'ポイ活お得情報') }}</li>
                        <li>{{ Tag::link(route('beginners'), 'GMOポイ活の使い方') }}</li>
                    </ul>
                    <ul class="header__menu__contents__list">
                        <li>{{ Tag::link(route('credit_cards.list'), 'カード比較') }}</li>
                        <li>{{ Tag::link(route('sp_programs.index'), '毎日ゲット') }}</li>
                        <li>{{ Tag::link(route('features.index'), '特集一覧') }}</li>
                        <li>{{ Tag::link('/help', 'ヘルプセンター') }}</li>

                    </ul>
                </div>
            </div>
        </div>
    </header>
    @endif
    @if (preg_match($pattern, $path))
    <main class="program">

    @elseif (in_array(Route::current()->getName(), $arrRoute) )
    <main class="entries">
    @else
    <main>
    @endif
    @if (in_array(Route::current()->getName(), $arrRoute) )
    @else
        <header>
            @if (!Auth::check())
                @yield('layout.content.header.top_banner')
            @endif

            <!-- gnav -->
            <section class="gnav">
                <ul class="gnav__list">
                    <li class="gnav__item"><a href="javascript:void(0)" class="header__search__btn"><i><img
                                    src="/images/common/ico_gnav_search.svg"></i>
                            <p><span>カテゴリー</span><br>から探す</p>
                        </a></li>
                    <li class="gnav__item"><a href="{{ route('questions.index') }}"><i><img
                                    src="/images/common/ico_gnav_question.svg"></i>
                            <p><span>アンケート</span><br>で貯める</p>
                        </a></li>
                    <li class="gnav__item"><a href="{{ route('receipt.list') }}"><i><img
                                    src="/images/common/ico_gnav_receipt.svg"></i>
                            <p><span>レシート</span><br>で貯める</p>
                        </a></li>
                    <li class="gnav__item"><a href="{{ route('fancrew.pages') }}"><i><img
                                    src="/images/common/ico_gnav_survey.svg"></i>
                            <p><span>モニター</span><br>で貯める</p>
                        </a></li>
                    <li class="gnav__item"><a href="{{ route('sp_programs.index') }}"><i><img
                                    src="/images/common/ico_gnav_calendar.svg"></i>
                            <p><span>毎日無料</span><br>で貯める</p>
                        </a></li>
                    <li class="gnav__item"><a href="{{ route('friends.index') }}"><i><img
                                    src="/images/common/ico_gnav_friends.svg"></i>
                            <p><span>お友達紹介</span><br>で貯める</p>
                        </a></li>
                </ul>
            </section>

            @yield('layout.breadcrumbs')
        </header>
        <header>
            @yield('layout.content.header')
            <!-- search inner -->
            @php
                $rakuten_program_list = \App\Program::ofEnable()
                    ->ofKeyword(['楽天サービス'])
                    ->take(8)
                    ->get();

                $yahoo_program_list = \App\Program::ofEnable()
                    ->ofKeyword(['Yahoo!サービス'])
                    ->take(8)
                    ->get();

                $docomo_program_list = \App\Program::ofEnable()
                    ->ofKeyword(['docomoサービス'])
                    ->take(8)
                    ->get();

                $au_program_list = \App\Program::ofEnable()
                    ->ofKeyword(['auサービス'])
                    ->take(8)
                    ->get();
            @endphp

            <div class="header__search__contents">
                <div class="inner">
                    <div class="header__search__contents__inner">
                        <div class="header__search__contents__keyword">
                            <div class="header__search__contents__keyword__inner">
                                <div class="header__search__contents__keyword__txt">キーワードで検索</div>
                                {!! Tag::formOpen([
                                    'url' => route('programs.list'),
                                    'method' => 'get',
                                    'id' => 'form1',
                                    'name' => 'form1',
                                    'class' => 'header__search__contents__keyword__form',
                                ]) !!}
                                @csrf
                                {!! Tag::formButton('', ['type' => 'submit', 'class' => 'header__search__contents__keyword__submit']) !!}
                                {!! Tag::formText('keywords', '', [
                                    'class' => 'header__search__contents__keyword__box',
                                    'placeholder' => 'キーワードで探す',
                                ]) !!}
                                {!! Tag::formClose() !!}
                            </div>
                        </div>

                        <!-- サービスから検索・カテゴリーから検索 -->
                        <div class="header__search__contents__2col">
                            <!-- サービスから検索 -->
                            <div class="header__search__contents__service">
                                <div class="header__search__contents__2col__ttl">サービスから検索</div>

                                <!-- 検索タブ -->
                                <ul class="header__search__contents__service__tab">
                                    @if (!$rakuten_program_list->isEmpty())
                                        <li class="active"><a
                                                href="#search_tab_rakuten">{{ Tag::image('/images/common/ico_hd_serv_rakuten.png', '楽天') }}</a>
                                        </li>
                                    @endif
                                    @if (!$yahoo_program_list->isEmpty())
                                        <li><a
                                                href="#search_tab_yahoo">{{ Tag::image('/images/common/ico_hd_serv_yahoo.png', 'Yahoo!') }}</a>
                                        </li>
                                    @endif
                                    @if (!$docomo_program_list->isEmpty())
                                        <li><a
                                                href="#search_tab_docomo">{{ Tag::image('/images/common/ico_hd_serv_docomo.png', 'docomo') }}</a>
                                        </li>
                                    @endif
                                    @if (!$au_program_list->isEmpty())
                                        <li><a
                                                href="#search_tab_au">{{ Tag::image('/images/common/ico_hd_serv_au.png', 'au') }}</a>
                                        </li>
                                    @endif
                                </ul>

                                <!-- 楽天サービス一覧 -->
                                @if (!$rakuten_program_list->isEmpty())
                                    <div class="header__search__contents__service__inner active" id="search_tab_rakuten">
                                        <div class="header__search__contents__service__inner__ttl">
                                            <a
                                                href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['楽天サービス']]) }}">
                                                <i>{{ Tag::image('/images/common/logo_small_rakuten.png') }}</i>楽天サービス一覧
                                            </a>
                                        </div>
                                        <ul class="header__search__contents__service__list">
                                            @foreach ($rakuten_program_list as $rakuten_program)
                                                @php
                                                    // アフィリエイト情報
                                                    $affiriate = $rakuten_program->affiriate;
                                                @endphp
                                                <li>
                                                    <a
                                                        href="{{ route('programs.show', ['program' => $rakuten_program]) }}">
                                                        <p class="header__search__contents__service__list__ttl">
                                                            {{ $rakuten_program->title }}</p>
                                                        <div class="header__search__contents__service__list__thumb">
                                                            {{ Tag::image($affiriate->img_url, $rakuten_program->title) }}
                                                        </div>
                                                        @php
                                                            $point = $rakuten_program->point;
                                                            $point_class = 'header__search__contents__service__list__point';
                                                            if ($point->fee_type == 2) {
                                                                $point_class = $point_class . ' percent_point';
                                                            }
                                                        @endphp
                                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="header__search__contents__service__btn">
                                            {{ Tag::link(
                                                \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['楽天サービス']]),
                                                'その他の楽天サービス',
                                            ) }}
                                        </div>
                                    </div><!-- /id=search_tab_rakuten -->
                                @endif

                                <!-- Yahoo!サービス一覧 -->
                                @if (!$yahoo_program_list->isEmpty())
                                    <div class="header__search__contents__service__inner" id="search_tab_yahoo">
                                        <div class="header__search__contents__service__inner__ttl">
                                            <a
                                                href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['Yahoo!サービス']]) }}">
                                                <i>{{ Tag::image('/images/common/logo_small_yahoo.png') }}</i>Yahoo!サービス一覧</a>
                                        </div>
                                        <ul class="header__search__contents__service__list">
                                            @foreach ($yahoo_program_list as $yahoo_program)
                                                @php
                                                    // アフィリエイト情報
                                                    $affiriate = $yahoo_program->affiriate;
                                                @endphp
                                                <li>
                                                    <a href="{{ route('programs.show', ['program' => $yahoo_program]) }}">
                                                        <p class="header__search__contents__service__list__ttl">
                                                            {{ $yahoo_program->title }}</p>
                                                        <div class="header__search__contents__service__list__thumb">
                                                            {{ Tag::image($affiriate->img_url, $yahoo_program->title) }}
                                                        </div>
                                                        @php
                                                            $point = $yahoo_program->point;
                                                            $point_class = 'header__search__contents__service__list__point';
                                                            if ($point->fee_type == 2) {
                                                                $point_class = $point_class . ' percent_point';
                                                            }
                                                        @endphp
                                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="header__search__contents__service__btn">
                                            {{ Tag::link(
                                                \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['Yahoo!サービス']]),
                                                'その他のYahoo!サービス',
                                            ) }}
                                        </div>
                                    </div><!-- /id=search_tab_yahoo -->
                                @endif

                                <!-- docomoサービス一覧 -->
                                @if (!$docomo_program_list->isEmpty())
                                    <div class="header__search__contents__service__inner" id="search_tab_docomo">
                                        <div class="header__search__contents__service__inner__ttl">
                                            <a
                                                href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['docomoサービス']]) }}">
                                                <i>{{ Tag::image('/images/common/logo_small_docomo.png') }}</i>docomoサービス一覧</a>
                                        </div>
                                        <ul class="header__search__contents__service__list">
                                            @foreach ($docomo_program_list as $docomo_program)
                                                @php
                                                    // アフィリエイト情報
                                                    $affiriate = $docomo_program->affiriate;
                                                @endphp
                                                <li>
                                                    <a
                                                        href="{{ route('programs.show', ['program' => $docomo_program]) }}">
                                                        <p class="header__search__contents__service__list__ttl">
                                                            {{ $docomo_program->title }}</p>
                                                        <div class="header__search__contents__service__list__thumb">
                                                            {{ Tag::image($affiriate->img_url, $docomo_program->title) }}
                                                        </div>
                                                        @php
                                                            $point = $docomo_program->point;
                                                            $point_class = 'header__search__contents__service__list__point';
                                                            if ($point->fee_type == 2) {
                                                                $point_class = $point_class . ' percent_point';
                                                            }
                                                        @endphp
                                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="header__search__contents__service__btn">
                                            {{ Tag::link(
                                                \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['docomoサービス']]),
                                                'その他のdocomoサービス',
                                            ) }}
                                        </div>
                                    </div><!-- /id=search_tab_docomo -->
                                @endif

                                <!-- auサービス一覧 -->
                                @if (!$au_program_list->isEmpty())
                                    <div class="header__search__contents__service__inner" id="search_tab_au">
                                        <div class="header__search__contents__service__inner__ttl">
                                            <a
                                                href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['auサービス']]) }}">
                                                <i>{{ Tag::image('/images/common/logo_small_au.png') }}</i>auサービス一覧
                                            </a>
                                        </div>
                                        <ul class="header__search__contents__service__list">
                                            @foreach ($au_program_list as $au_program)
                                                @php
                                                    // アフィリエイト情報
                                                    $affiriate = $au_program->affiriate;
                                                @endphp
                                                <li>
                                                    <a href="{{ route('programs.show', ['program' => $au_program]) }}">
                                                        <p class="header__search__contents__service__list__ttl">
                                                            {{ $au_program->title }}
                                                        </p>
                                                        <div class="header__search__contents__service__list__thumb">
                                                            {{ Tag::image($affiriate->img_url, $au_program->title) }}
                                                        </div>
                                                        @php
                                                            $point = $au_program->point;
                                                            $point_class = 'header__search__contents__service__list__point';
                                                            if ($point->fee_type == 2) {
                                                                $point_class = $point_class . ' percent_point';
                                                            }
                                                        @endphp
                                                        <p class="{{ $point_class }}">{{ $point->fee_label }}P</p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="header__search__contents__service__btn">
                                            {{ Tag::link(
                                                \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['auサービス']]),
                                                'その他のauサービス',
                                            ) }}
                                        </div>
                                    </div><!-- /id=search_tab_au -->
                                @endif
                            </div><!-- End サービスから検索 -->

                            <!-- カテゴリーから検索 -->
                            <div class="header__search__contents__category">
                                <div class="header__search__contents__2col__ttl">カテゴリーから検索</div>
                                <ul class="header__search__contents__category__list">
                                    @foreach ($categoryColletsions as $categoryItem)
                                        <li>
                                            <a href={{ $categoryItem['link'] }}>
                                                <div class="header__search__contents__category__list__ico">
                                                    {{ Tag::image('/images/common/' . $categoryItem['image']) }}</div>
                                                <p class="header__search__contents__category__list__ttl">
                                                    {!! $categoryItem['ttl'] !!}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div><!-- End カテゴリーから検索 -->

                        </div><!-- End サービスから検索・カテゴリーから検索 -->
                    </div>
                </div>
            </div><!-- End search inner -->
        </header>
        @endif
        <section class="contents__wrap">
            @yield('layout.content')

            @hasSection ('layout.left_sidebar')
                <section class="left_column">
                    @yield('layout.left_sidebar')
                </section><!--/left_colum-->
            @endif

            @hasSection ('layout.sidebar')
                <div class="sidebar">
                    @yield('layout.sidebar')
                </div><!--/right_colum-->
            @endif
        </section>
        @yield('layout.recommend')
        @yield('layout.footer.sale')
        @yield('layout.footer_notes')
        @if(!in_array(Route::current()->getName(), $arrRoute))
        <div class="adS">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5058246241263959"
                crossorigin="anonymous"></script>
            <!-- colleee2021pc_fotter728 -->
            <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
                data-ad-client="ca-pub-5058246241263959" data-ad-slot="8484441825"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div><!--/adS-->
        @endif
        
    </main>

    @yield('layout.fancrew')


    <footer>
        @if(!in_array(Route::current()->getName(), $arrRoute))
        <p class="footer__top__link">
            <a href="#">▲ページ上へ戻る</a>
        </p>
        @endif
        <div class="footer__nav__wrap">
            <div class="inner footer__nav">
                <nav class="footer__nav__l">
                    <ul class="footer__nav__list">
                        @if (Auth::check())
                            <li>{{ Tag::link(route('users.show'), 'マイページ') }}</li>
                        @endif

                        <li>{{ Tag::link(route('friends.index'), 'お友達紹介') }}</li>
                        <li>{{ Tag::link('/help/', 'ヘルプセンター') }}</li>
                        <li>{{ Tag::link(route('sitemaps.index'), 'サイトマップ') }}</li>

                    </ul>
                    <ul class="footer__nav__list">
                        <li>{{ Tag::link(route('abouts.membership_contract'), '会員利用規約') }}</li>
                        <li>{{ Tag::link(config('url.gmo_nikko'), '運営会社', ['target' => '_blank', 'class' => 'lnk_external']) }}
                        </li>
                        <li>{{ Tag::link(config('url.privacy_policy'), '個人情報保護方針', [
                            'target' => '_blank',
                            'class' => 'lnk_external',
                        ]) }}
                        </li>
                        @if (Auth::check())
                            <li>{{ Tag::link(route('logout'), 'ログアウト') }}</li>
                        @endif
                    </ul>
                </nav>

                <div class="footer__nav__r">
                    <div class="footer__nav__sns__ttl">GMOポイ活<br>公式アカウント</div>
                    <ul class="footer__nav__sns">
                        <li>{!! Tag::link(
                            'https://twitter.com/gmo_poikatsu',
                            Tag::image('/images/common/ico_ft_tw.png', 'X'),
                            ['target' => '_blank'],
                            null,
                            false,
                        ) !!}</li>
                        <li>{!! Tag::link(
                            'https://www.tiktok.com/@gmopoikatsu_official',
                            Tag::image('/images/common/ico_ft_tiktok.png', 'TikTok'),
                            ['target' => '_blank'],
                            null,
                            false,
                        ) !!}</li>
                        <li>{!! Tag::link(
                            'https://www.facebook.com/colleee.info/',
                            Tag::image('/images/common/ico_ft_fb.png', 'Facebook'),
                            ['target' => '_blank'],
                            null,
                            false,
                        ) !!}</li>
                    </ul>
                </div>
            </div>

            <div class="inner footer__nav">
                <div style="text-align:left;">
                    <div style="display:inline-block;">
                        @if (config('app.env') == 'production')
                        <div id="ss_gmo_globalsign_secured_site_seal" oncontextmenu="return false;" style="width:130px; height:66px">
                            <img id="ss_gmo_globalsign_img" src="data:image/gif;base64,R0lGODlhAQABAGAAACH5BAEKAP8ALAAAAAABAAEAAAgEAP8FBAA7" alt="" onclick="ss_open_profile()" style="cursor:pointer; border:0; width:100%" >
                        </div>
                        <script>
                            window.addEventListener('load', () => {
                            let s = document.createElement("script");
                            s.src = "https://seal.atlas.globalsign.com/gss/one/seal?image=seal_130-66_ja_w.png";
                            document.body.appendChild(s);
                            });
                        </script>
                        @endif
                    </div>
                    <div style="display:inline-block;margin-left:20px;">
                        <span id="csi_siteseal_tag" oncontextmenu="return false;">
                            <a id="csi_siteseal_profile_link">
                                <img decoding="async" loading="lazy" alt="dark_typeB_130x66.png" id="csi_siteseal_image" width="130" height="66" src="#" style="display: none" />
                            </a>
                        </span>
                        <script type="text/javascript" src="https://gmo-cybersecurity.com/siteseal/siteseal.js" defer="defer"></script>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer__info__wrap">
            <div class="inner footer__info">
                <div class="footer__info__l">
                    <div class="footer__logo">
                        {{ Tag::link(
                            route('website.index'),
                            Tag::image('/images/common/logo.png', 'はじめてのポイ活はGMOポイ活'),
                            [],
                            null,
                            false,
                        ) }}
                    </div>
                </div>
                <div class="footer__info__r">
                    <div class="footer__copyright">&#0169; GMO NIKKO, Inc. All Rights Reserved.</div>
                </div>
            </div>
        </div>
        {{ Tag::script('//seal.globalsign.com/SiteSeal/2021gmogs_100-50_ja.js', ['defer' => 'defer']) }}
        {{ Tag::script('https://siteseal.gmo-cybersecurity.com/static/scripts/siteseal.js', ['defer' => 'defer']) }}
        {{ Tag::script('https://cache.img.gmo.jp/gmo/footer/script.min.js', [
            'charset' => 'UTF-8',
            'type' => 'text/javascript',
            'id' => 'gmofootertag',
            'data-gmofooter-type' => 'F',
            'async' => 'async',
        ]) }}

    </footer>
@endsection
