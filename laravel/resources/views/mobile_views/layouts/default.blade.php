@extends('layouts.base')

@section('layout.plane.head')

<script type="text/javascript"><!--
$(function(){
    var menu_btn = $('.header__menu__btn');
    var menu_contents = $('.header__menu__contents');
    var hum_btn_inr = $('.header__menu__btn__hamburger');

    // 3本線メニューをクリックしたらメニューを開閉し、3本線が×になる
    menu_btn.on('click',function(){
        menu_contents.toggleClass('header__active');
        hum_btn_inr.toggleClass('hum__active');
        return false;
    });

    // メニューの外側をクリックしたらメニューを閉じる
    $(document).on('click',function(e){
        if(!$(e.target).closest('.header__menu__contents').length){
            menu_contents.removeClass('header__active');
            hum_btn_inr.removeClass('hum__active');
        }
    });
});

// header search inner
$(function(){
	var search_btn = $('.header__search__btn');
	var search_contents = $('.header__search__contents');
	var search_contents_close = $('.header__search__contents__close__btn');
	var body_fixed = $('.js_body');
	var state = false;
	var scrollPos; //topからのスクロール位置

	// 画面下固定メニューの「検索」をクリックしたら検索エリアを開閉する
	search_btn.on('click',function(){
		search_contents.toggleClass('search__active');
		search_btn.toggleClass('search__btn__active');
		if(state == false) {
			scrollPos = $(window).scrollTop(); //topからのスクロール位置を格納
			body_fixed.toggleClass('js_body_fixed').css({ top: -scrollPos }); //背景固定
			state = true;
		} else {
			body_fixed.removeClass('js_body_fixed').css({ top: 0 }); //背景固定を解除
			$(window).scrollTop(scrollPos); //元の位置までスクロール
			state = false;
			return false;
		}
		return false;
	});

	// 検索エリア内の閉じるボタンをクリック
	search_contents_close.on('click',function(){
		search_contents.removeClass('search__active');
		search_btn.removeClass('search__btn__active');
		body_fixed.removeClass('js_body_fixed').css({ top: 0 }); //背景固定を解除
		$(window).scrollTop(scrollPos); //元の位置までスクロール
		return false;
	});
});

// header search サービスで探す・ショッピングで探す 検索タブ
$(function(){
	$(".header__search__contents__service__tab a").click(function(){
		$(this).parent().addClass("active").siblings(".active").removeClass("active");
		var header__search__contents__service__inner = $(this).attr("href");
		$(header__search__contents__service__inner).addClass("active").siblings(".active").removeClass("active");
		return false;
	});
});

// tabbar 同一のURLであれば色をつける
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementsByClassName('tabbar') != null) {
        const currentUrl = location.href;
        const tabbarItem = Array.from(document.getElementsByClassName('tabbar__item'));
        function currentItem() {
            tabbarItem.forEach(function(target) {
                target.classList.remove('current');
                const tabbarUrl = target.querySelector('a').href;
                if(currentUrl === tabbarUrl) {
                    target.classList.add('current');
                }
                currentIcon();

                target.querySelector('a').addEventListener('click', function() {
                    new Promise((resolve) => {
                        tabbarItem.forEach(function(e) {
                            const eUrl = e.querySelector('a').href;
                            if(currentUrl === eUrl) {
                                e.classList.toggle('current');
                            }
                        });
                        if(target.querySelector('a.header__search__btn') !== null) {
                            target.classList.toggle('current');
                        }
                        resolve();
                    }).then(() => {
                        currentIcon();
                    });
                });
                document.querySelector('.header__search__contents__close__btn').addEventListener('click', function() {
                    new Promise((resolve) => {
                        if(target.querySelector('a.header__search__btn') !== null) {
                            target.classList.toggle('current');
                        }
                        if(currentUrl === tabbarUrl) {
                            target.classList.add('current');
                        }
                        resolve();
                    }).then(() => {
                        currentIcon();
                    });
                });
            });
            function currentIcon() {
                tabbarItem.forEach(function(target) {
                    const tabbarIco = target.querySelector('img');
                    if(target.classList.contains('current')) {
                        tabbarIco.src = tabbarIco.src.replace('_gr', '');
                    } else if (tabbarIco.src.indexOf('_gr') == -1 && target.classList.contains('current') == false) {

                        tabbarIco.src = tabbarIco.src.replace('.svg', '_gr.svg');
                    }
                });
            }
        }
        currentItem();
    }
});

$(function() {
    var scroll = purl(location.href).param('scroll');
    if(scroll === "" || scroll === null || scroll === undefined){
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

$(function(){
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
    //20220801 programs画面の調整で下記削除したが影響がある場合他画面に影響がある場合調整が必要
    //$('.r_list:not(.r_list:first-of-type)').css('display','none');
    $('.more').nextAll('.more').css('display','none');
    $('.more').on('click', function() {
        $(this).css('display','none');
        $(this).next('.r_list').slideDown('fast');
        $(this).nextAll('.more:first').css('display','block');
    });
});
//スムーズスクロール部分の記述
// #で始まるアンカーをクリックした場合に処理
$('a[href^="#"]').on('click', function(event) {
    // スクロールの速度
    var speed = 400; // ミリ秒
    // アンカーの値取得
    var href= $(this).attr("href");
    // 移動先を取得
    var target = $(href == "#" || href == "" ? 'html' : href);
    // 移動先を数値で取得
    var position = target.offset().top;
    // スムーススクロール
    $('body,html').animate({scrollTop:position}, speed, 'swing');
    return false;
});
$(function() {
    $('.eins .heading').each(function() {
        var $target = $(this);

        // オリジナルの文章を取得する
        var html = $target.html();

        // 対象の要素を、高さにautoを指定し非表示で複製する
        var $clone = $target.clone();
        $clone.css({
            display: 'none',
            position : 'absolute',
            overflow : 'visible'
        }).width($target.width()).height('auto');

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
$(function() {
    $("#go").leanModal({ top : 10, overlay : 0.4, closeButton: ".modal_close" });
});

$(document).on('click', ".AjaxContent", function(event) {
    if ($(this).is('select')) return;
    executeAjax($(this).attr('forUrl'), $('#' + $(this).attr('forRender')));
});
$(document).on('change', "select.AjaxContent", function(event) {
    executeAjax($(this).first().find('option:selected').attr('forUrl'), $('#' + $(this).first().find('option:selected').attr('forRender')));
});
$(function() {
    $(".AjaxRender").each(function() {
        executeAjax($(this).attr('forUrl'), $(this));
    });
});
//-->
</script>

@if (($use_recaptcha ?? false) && \App\External\Google::getRecaptchaUse())
<style type="text/css">
<!--
.grecaptcha-badge {
    margin-bottom: 100px;
}
-->
</style>
{{ Tag::script(\App\External\Google::getRecaptchaJsUrl(), ['type' => 'text/javascript']) }}
<script type="text/javascript"><!--
$(function(){
  $('.{{ \App\External\Google::getRecaptchaClass() }}').submit(function() {
    var form = $(this);
    var input = form.find('input[name="{{ \App\External\Google::getRecaptchaParamKey() }}"]');
    if (input.val() != '') {
        return true;
    }
    var action = form.attr('forGoogleRecaptchaAction') || 'homepage';
    grecaptcha.execute('{{ \App\External\Google::getRecaptchaSiteKey() }}', {action: action})
      .then(function(token) {
        // Verify the token on the server.
        input.val(token);
        form.submit();
    });
    return false;
  });
});
//-->
</script>
@endif
@yield('layout.head')

@endsection
@php
  $path = Request::path();
  $pattern = '/^programs\/\d+/';
  $arrRoute = ['entries.confirm', 'entries.post_confirm', 'entries.send', 'entries.create', 'entries.confirm_tel' , 'entries.question', 'entries.index', 'login.google.callback', 'login.line.callback' , 'login'];

@endphp
@section('layout.plane.body')
@if (in_array(Route::current()->getName(), $arrRoute) )

<header class="header simple">
    <div class="header__logo">
        <h1><img src="/images/common/logo.png" alt="はじめてのポイ活はGMOポイ活"></h1>
    </div>
</header>
@else
<header>
    @php
        //get top banner above header
        $top_banner_above_header = \App\Content::ofSpot(\App\Content::SPOT_BANNER_ABOVE_HEADER_SP)
            ->orderBy('priority', 'asc')
            ->orderBy('start_at', 'desc')
            ->limit(1)
            ->get();
    @endphp

    <!-- top header wrapper -->
    @if(!$top_banner_above_header->isEmpty())
        @php
            $content_data = json_decode($top_banner_above_header[0]['data']);
        @endphp
        @if (isset($base_css_type) && $base_css_type == 'top')
            @push('custom_css')
            <style>
            .header__menu__contents {
                top: 126px;
            }
            </style>
            @endpush
            <div class="top-header-wrapper">
                <a href="{{$content_data->url}}">
                    {!! Tag::image($content_data->img_url, $top_banner_above_header[0]['title'], ['class' => 'top-header-content']) !!}
                </a>
            </div>
        @endif
    @endif

    <div class="header__main__wrap">
        <div class="inner">
            <div class="header__main">
                <div class="header__main__l">
                    @if (strpos(Route::currentRouteName(), 'api')  !== false)
                        <div class="header__logo">
                            {{ Tag::link(url()->full(), Tag::image('/images/common/logo.png', 'はじめてのポイ活はGMOポイ活'), [], null, false) }}
                        </div>
                    @else
                        <div class="header__logo">
                            {{ Tag::link(route('website.index'), Tag::image('/images/common/logo.png', 'はじめてのポイ活はGMOポイ活'), [], null, false) }}
                        </div>
                    @endif
                </div>

                <nav class="header__main__r">
                    <ul>
                        @if (Auth::check())
                            <a href="{{ route('users.show') }}" class="header__mypage">
                                <li>
                                    <div class="header__user">
                                        @php
                                            $user = Auth::user();
                                        @endphp

                                        @if (isset($user->nickname))
                                            <div class="header__user__name">{{ $user->nickname }}<br>さん</div>
                                        @else
                                            <div class="header__user__name">{{ $user->name }}<br>さん</div>
                                        @endif
                                        <div class="header__user__point"><span>{{ number_format($user->point) }}</span>P</div>
                                    </div>
                                </li>

                                <li>
                                    <div class="header__favorite">
                                        <button class="header__favorite__btn">
                                            <div class="header__favorite__btn__ico">{{ Tag::link(route('exchanges.index'), Tag::image('/images/common/ico_hd_exchange.svg'),  [], null, false) }}</div>
                                            <div class="header__favorite__btn__txt">ポイント交換</div>
                                        </button>
                                    </div>
                                </li>
                            </a>
                        @else
                            <li>
                                <div class="header__favorite">
                                    <a href="{{ route('entries.index') }}" class="header__favorite__btn">
                                        <div class="header__favorite__btn__ico">
                                            {{ Tag::image('/images/common/ico_hd_entry.svg') }}
                                        </div>
                                        <div class="header__favorite__btn__txt">新規登録</div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="header__favorite">
                                    <a href="{{ route('login', ['back' => 0]) }}" class="header__favorite__btn">
                                        <div class="header__favorite__btn__ico">
                                            {{ Tag::image('/images/common/ico_hd_login.svg') }}
                                        </div>
                                        <div class="header__favorite__btn__txt">ログイン</div>
                                    </a>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="header__menu">
                                <button class="header__menu__btn">
                                    <div class="header__menu__btn__hamburger">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                    <div class="header__menu__btn__txt">MENU</div>
                                </button>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    @yield('layout.breadcrumbs')

    <!-- hamburger inner -->
    <div class="header__menu__contents">
        <div class="">
            <div class="header__menu__contents__inner">
                <ul class="header__menu__contents__list">

                    <li>{{ Tag::link('/support/?cat=8', 'お知らせ') }}</li>
                    <li>{{ Tag::link(route('programs.list', ['sort' => 3]), '新着') }}</li>
                    <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['高還元']]), '高還元') }}</li>
                    <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [80]]), '無料') }}</li>
                    <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [78]]), '即日還元') }}</li>
                    <li>{{ Tag::link(route('questions.index'), 'アンケート') }}</li>
                    <li>{{ Tag::link(route('sp_programs.index').'#game', 'ゲーム') }}</li>
                    <li>{{ Tag::link(route('shops.index'), '買い物') }}</li>
                    <li>{{ Tag::link(route('fancrew.pages'), 'モニター') }}</li>
                    <li>{{ Tag::link('/article', 'ポイ活お得情報') }}</li>
                    <li>{{ Tag::link(route('friends.index'), 'お友達紹介') }}</li>
                    <li>{{ Tag::link(route('credit_cards.index'), 'カード比較') }}</li>
                    <li>{{ Tag::link(route('sp_programs.index'), '毎日ゲット') }}</li>
                    <li>{{ Tag::link(route('features.index'), '特集一覧') }}</li>
                    <li>{{ Tag::link(route('beginners'), 'GMOポイ活の使い方') }}</li>
                    <li>{{ Tag::link('/help', 'ヘルプセンター') }}</li>

                </ul>
            </div>
        </div>
    </div><!-- menu -->

    <!-- header sub -->
	<section class="tabbar">
        <ul class="tabbar__list">
            <li class="tabbar__item"><a href="{{ route('website.index') }}"><i><img src="/images/common/ico_gnav_home_gr.svg"></i><p>ホーム</p></a></li>
			<li class="tabbar__item"><a href="javascript:void(0)" class="header__search__btn"><i><img src="/images/common/ico_gnav_search_gr.svg"></i><p>検索</p></a></li>
			<li class="tabbar__item"><a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => ['アプリ']]) }}"><i><img src="/images/common/ico_gnav_app_gr.svg"></i><p>アプリ</p></a></li>
			<li class="tabbar__item"><a href="{{ route('receipt.list') }}"><i><img src="/images/common/ico_gnav_receipt_gr.svg"></i><p>レシ活</p></a></li>
			<li class="tabbar__item"><a href="{{ route('questions.index') }}"><i><img src="/images/common/ico_gnav_question_gr.svg"></i><p>アンケート</p></a></li>
			<li class="tabbar__item"><a href="{{ route('sp_programs.index') }}"><i><img src="/images/common/ico_gnav_calendar_gr.svg"></i><p>毎日GET</p></a></li>
        </ul>
	</section>

    <!-- search inner -->
    <div class="header__search__contents">
        <div class="header__search__contents__inner__wrap">
            <div class="header__search__contents__inner">
                <div class="header__search__contents__close">
                    <button class="header__search__contents__close__btn">
                        <span></span>
                        <span></span>
                    </button>
                </div>

                <div class="header__search__contents__keyword">
                    <div class="header__search__contents__keyword__inner">
                        {!! Tag::formOpen(['url' => route('programs.list'), 'method' => 'get', 'id' => 'form1', 'name' => 'form1', 'class' => 'header__search__contents__keyword__form']) !!}
                        @csrf    
                        {!! Tag::formButton('', ['type' => 'submit', 'class' => 'header__search__contents__keyword__submit']) !!}
                            {!! Tag::formText('keywords', '', ['class' => 'header__search__contents__keyword__box', 'placeholder' => 'キーワードで探す']) !!}
                        {!! Tag::formClose() !!}
                    </div>
                </div>

                <!-- サービスで探す・ショッピングで探す -->
                @php
                    $label_type_map = [
                        'サービスで探す' => [
                            122 => ['icon' => 'ico_serv_car.svg', 'class' => 'large'],
                            123 => ['icon' => 'ico_serv_foc.svg'],
                            124 => ['icon' => 'ico_serv_entmt.svg'],
                            125 => ['icon' => 'ico_serv_credit.svg'],
                            126 => ['icon' => 'ico_serv_home.svg'],
                            127 => ['icon' => 'ico_serv_hikkoshi.svg'],
                            128 => ['icon' => 'ico_serv_kaitori.svg'],
                            129 => ['icon' => 'ico_serv_pen.svg'],
                            130 => ['icon' => 'ico_serv_school.svg'],
                            131 => ['icon' => 'ico_serv_beauty.svg'],
                            132 => ['icon' => 'ico_serv_pay.svg'],
                            133 => ['icon' => 'ico_serv_bank.svg', 'class' => 'small'],
                            134 => ['icon' => 'ico_serv_money.svg'],
                            135 => ['icon' => 'ico_serv_furusato.svg']],
                        'ショッピングで探す' => [
                            109 => ['icon' => 'ico_shop_cart.svg'],
                            110 => ['icon' => 'ico_shop_diet.svg'],
                            111 => ['icon' => 'ico_shop_beauty.svg', 'class' => 'xsmall'],
                            112 => ['icon' => 'ico_shop_fashion.svg'],
                            113 => ['icon' => 'ico_shop_gourmet.svg'],
                            114 => ['icon' => 'ico_shop_gift.svg'],
                            115 => ['icon' => 'ico_shop_kaden.svg'],
                            116 => ['icon' => 'ico_shop_life.svg'],
                            117 => ['icon' => 'ico_shop_sports.svg'],
                            118 => ['icon' => 'ico_shop_kids.svg'],
                            119 => ['icon' => 'ico_shop_pet.svg', 'class' => 'large'],
                            120 => ['icon' => 'ico_shop_book.svg'],
                            121 => ['icon' => 'ico_shop_game.svg']],
                    ];

                    $label_tab_map = ['サービスで探す' => 'search_tab_service', 'ショッピングで探す' => 'search_tab_shop'];
                @endphp

                <div class="header__search__contents__box">
                    <!-- 検索タブ -->
                    <ul class="header__search__contents__service__tab">
                        @foreach ($label_tab_map as $label_tab => $label_tab_id)
                            @php
                                $class = '';
                                if ($label_tab === array_key_first($label_tab_map)) {
                                    $class = " active";
                                }
                            @endphp
                            <li {{ $class ? "class=$class" : "" }}><a href={{ '#'. $label_tab_id }}>{{ $label_tab }}</a></li>
                        @endforeach
                    </ul>

                    <!-- カテゴリ一覧 -->
                    @foreach ($label_tab_map as $label_tab => $label_tab_id)
                        @php
                            $class = '';
                            if ($label_tab === array_key_first($label_tab_map)) {
                                $class = " active";
                            }
                        @endphp

                        <div class="header__search__contents__service__inner{{ $class }}" id="{{ $label_tab_id }}">
                            <ul class="header__search__contents__service__inner__list">
                                @php
                                $label_ids = $label_type_map[$label_tab];
                                $label_list = \App\Label::whereIn('id', array_keys($label_ids))->pluck('name', 'id')->all();
                                @endphp

                                @foreach($label_list as $label_id => $label)
                                <?php
                                    $icon = $label_ids[$label_id]['icon'] ?? null;
                                    $class = $label_ids[$label_id]['class'] ?? null;
                                ?>
                                <li>
                                    <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$label_id]]) }}">
                                        <i>{{ Tag::image("/images/common/$icon", null, isset($class) ? ['class' => $class] : null) }}</i>{{ $label }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div><!-- /.header__search__contents__box -->
            </div>
        </div>
    </div><!-- End search inner -->
</header><!-- header -->
@endif

@if (preg_match($pattern, $path))
<main class="program">
    <section class="contents__wrap">
    @hasSection ('layout.content')
        @yield('layout.content')
    @else
        @hasSection ('layout.outer_content')
            @yield('layout.outer_content')
        @endif
    @endif
    </section>
    @yield('layout.recommend')
    @yield('layout.footer_notes')
</main>

<div class="modal member-rank" data-modal="modal-member-rank">
    <div class="js-modal-overlay modal__overlay"></div>
    <div class="modal__window">
        <div class="modal__contents">
            <div class="modal__contents__body member-rank__inner">
				<p class="member-rank__ttl">ランク特典とは？</p>
				<p>サービス利用で獲得したポイントに、さらに会員ランクに応じた特典をプレゼントいたします。タグがついた広告がボーナス対象です。</p>
				<dl>
					<dt>1,000P獲得、ランク特典10.0%の場合</dt>
					<dd>
						獲得ポイント + 獲得ポイント × ランク特典なので<br>
						<span>1,000P + 1,000P × 10.0% = 1,100P獲得</span><br>
						となり、合計1,100ポイント獲得となります。<br>
					</dd>
				</dl>
				{{ Tag::link(route('abouts.member_rank'), '会員ランクについて') }}
				<a class="js-modal-close modal__close"></a>
            </div>
        </div>
    </div>
</div>

@else
    @if (in_array(Route::current()->getName(), $arrRoute) )
        <main class="entries">
            @yield('layout.content.header')
                <section class="contents__wrap">
                    @hasSection ('layout.content')
                        @yield('layout.content')
                    @else
                        @hasSection ('layout.outer_content')
                            @yield('layout.outer_content')
                        @endif
                    @endif
                </section>
            @yield('layout.recommend')
            @yield('layout.footer_notes')
        </main>
    @else
    <main>
        @yield('layout.content.header')
        <div>
            <section class="contents__wrap">
                @hasSection ('layout.content')
                    @yield('layout.content')
                @else
                    @hasSection ('layout.outer_content')
                        @yield('layout.outer_content')
                    @endif
                @endif
            </section>
        </div>
        @yield('layout.recommend')
        @yield('layout.footer_notes')
    </main>
    @endif
@endif

<footer class="footer">
<section class="footer__category">
        <div class="footer__category__tabs">
            @php
                // PC版で表示するラベルマップ
                $label_type_map = [
                    'service_content' => [
                        122 => ['icon' => 'ico_serv_car.svg', 'class' => 'large'],
                        123 => ['icon' => 'ico_serv_foc.svg'],
                        124 => ['icon' => 'ico_serv_entmt.svg'],
                        125 => ['icon' => 'ico_serv_credit.svg'],
                        126 => ['icon' => 'ico_serv_home.svg'],
                        127 => ['icon' => 'ico_serv_hikkoshi.svg'],
                        128 => ['icon' => 'ico_serv_kaitori.svg'],
                        129 => ['icon' => 'ico_serv_pen.svg'],
                        130 => ['icon' => 'ico_serv_school.svg'],
                        131 => ['icon' => 'ico_serv_beauty.svg'],
                        132 => ['icon' => 'ico_serv_pay.svg'],
                        133 => ['icon' => 'ico_serv_bank.svg', 'class' => 'small'],
                        134 => ['icon' => 'ico_serv_money.svg'],
                        135 => ['icon' => 'ico_serv_furusato.svg'],
                    ],
                    'shopping_content' => [
                        109 => ['icon' => 'ico_shop_cart.svg'],
                        110 => ['icon' => 'ico_shop_diet.svg'],
                        111 => ['icon' => 'ico_shop_beauty.svg', 'class' => 'xsmall'],
                        112 => ['icon' => 'ico_shop_fashion.svg'],
                        113 => ['icon' => 'ico_shop_gourmet.svg', 'class' => 'small'],
                        114 => ['icon' => 'ico_shop_gift.svg'],
                        115 => ['icon' => 'ico_shop_kaden.svg'],
                        116 => ['icon' => 'ico_shop_life.svg'],
                        117 => ['icon' => 'ico_shop_sports.svg'],
                        118 => ['icon' => 'ico_shop_kids.svg'],
                        119 => ['icon' => 'ico_shop_pet.svg', 'class' => 'large'],
                        120 => ['icon' => 'ico_shop_book.svg'],
                        121 => ['icon' => 'ico_shop_game.svg'],
                    ],
                ];
            @endphp

            <input id="service" type="radio" name="tab_item" checked>
            <label class="tab__item" for="service">サービスでさがす</label>
            <input id="shopping" type="radio" name="tab_item">
            <label class="tab__item" for="shopping">ショッピングでさがす</label>

            @foreach ($label_type_map as $key_label_map => $label_ids)
                <div class="tab__contents" id="{{ $key_label_map }}">
                    <ul class="tab__contents__list">
                        @php
                            $label_list = \App\Label::whereIn('id', array_keys($label_ids))
                                ->pluck('name', 'id')
                                ->all();
                        @endphp

                        @foreach ($label_list as $label_id => $label)
                            @php
                                $icon = $label_ids[$label_id]['icon'] ?? null;
                                $class = $label_ids[$label_id]['class'] ?? null;
                            @endphp

                            <li>
                                <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$label_id]]) }}">
                                    <i>{{ Tag::image("/images/common/$icon", null, isset($class) ? ['class' => $class] : null) }}</i>{{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>
    <div class="footer__nav__wrap">
        <nav class="inner footer__nav">
            <ul class="footer__nav__list">
                @if(Auth::check())
                    <li>{{ Tag::link(route('users.show'), 'マイページ', ['target' => '_blank']) }}</li>
                @endif
                @if (!(strpos(Route::currentRouteName(), 'api')  !== false))

                    <li>{{ Tag::link(route('friends.index'), 'お友達紹介') }}</li>
                    <li>{{ Tag::link('/help/', 'ヘルプセンター') }}</li>
                    <li>{{ Tag::link(route('sitemaps.index'), 'サイトマップ') }}</li>
                    <li>{{ Tag::link(route('abouts.membership_contract'), 'GMOポイ活会員利用規約') }}</li>

                @endif
                <li>{{ Tag::link(config('url.gmo_nikko'), '運営会社', ['target' => '_blank', 'class' => 'lnk_external']) }}</li>
                <li>{{ Tag::link(config('url.privacy_policy'), '個人情報保護方針', ['target' => '_blank', 'class' => 'lnk_external']) }}</li>
                @if (Cookie::has(config('cookie.name')))
                    <li>{{ Tag::link(route('api.login.logout'), 'ログアウト', ['class' => 'heading']) }}</li>
                @else
                    @if (Auth::check())
                    <li>{{ Tag::link(route('logout'), 'ログアウト', ['class' => 'heading']) }}</li>
                    @endif
                @endif
            </ul>

            <ul class="footer__nav__sns">
                <li>{{ Tag::link('https://twitter.com/gmo_poikatsu', Tag::image('/images/common/ico_ft_tw.png', 'X'), ['target' => '_blank'], null, false) }}</li>
                <li>{{ Tag::link('https://www.tiktok.com/@gmopoikatsu_official', Tag::image('/images/common/ico_ft_tiktok.png', 'TikTok'), ['target' => '_blank'], null, false) }}</li>
                <li>{{ Tag::link('https://www.facebook.com/colleee.info/', Tag::image('/images/common/ico_ft_fb.png', 'Facebook'), ['target' => '_blank'], null, false) }}</li>
            </ul>

            <div class="footer__nav__site_seal">
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
            <!--
            <ul class="footer__nav__site_shindan">
                <a href="https://shindan-lp.gmo-cybersecurity.com/?utm_source=gmo_nikko&utm_medium=01&utm_campaign=gmo_nikko_01_nds2023" target="_blank"><img decoding="async" loading="lazy" class="nds_banner" src="https://gmo-cybersecurity.com/nds/banner/rectangle2.jpg" style="width: 234px; height: 64px;" alt="ネットde診断バナー" ></a>
            </ul>
            -->

        </nav>
    </div><!-- footer__nav__wrap -->
    <div class="footer__info__wrap">
        <div class="inner">
            <div class="footer__logo">
                {{ Tag::link(route('website.index'), Tag::image('/images/common/logo.png', 'はじめてのポイ活はGMOポイ活'), ['target' => '_blank'], null, false) }}
            </div>
            <div class="footer__copyright">&#0169; GMO NIKKO, Inc. All Rights Reserved.</div>
        </div>
    </div><!-- footer__info__wrap -->
    {{ Tag::script('//seal.globalsign.com/SiteSeal/2021gmogs_100-50_ja.js', ['defer' => 'defer']) }}
    {{ Tag::script('https://siteseal.gmo-cybersecurity.com/static/scripts/siteseal.js', ['defer' => 'defer']) }}
</footer>
@endsection
