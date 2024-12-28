<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article#">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, minimum-scale=1.0, maximum-scale=1" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/favicon-v2.ico" type="image/x-icon" rel="icon" />
        <link href="/favicon-v2.ico" type="image/x-icon" rel="shortcut icon" />
        <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
        @hasSection ('layout.title')
        <title>@yield('layout.title')</title>
        <meta name="twitter:title" content="@yield('layout.title')" />
        <meta property='og:title' content="@yield('layout.title')" />
        @else
        <title>ポイントサイトのGMOポイ活｜初心者でも安全にポイ活でお小遣い稼ぎ</title>
        <meta property='og:title' content="はじめてのポイ活はGMOポイ活｜初心者でも安心安全のGMO運営" />
        <meta name="twitter:title" content="はじめてのポイ活はGMOポイ活｜初心者でも安心安全のGMO運営" />
        @endif
        @hasSection ('layout.keywords')
        <meta name="keywords" content="@yield('layout.keywords')" />
        @else
        <meta name="keywords" content="GMOポイ活,買い物,ショッピング,お得,ポイント,ポイントサイト,ポイ活" />
        @endif
        @hasSection ('layout.description')
        <meta name="description" content="@yield('layout.description')" />
        <meta property="og:description" content="@yield('layout.description')" />
        <meta name="twitter:description" content="@yield('layout.description')" />
        @else
        <meta name="description" content="GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪" />
        <meta property="og:description" content="GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪" />
        <meta name="twitter:description" content="GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪" />
        @endif
        @hasSection ('og_type')
        <meta property='og:type' content="@yield('og_type')" />
        @else
        <meta property='og:type' content="article" />
        @endif
        @hasSection ('url')
        <meta property='og:url' content="@yield('url')" />
        @else
        <meta property='og:url' content="{{ route('website.index') }}" />
        @endif

        @hasSection('og_image')
            <meta property='og:image' content="@yield('og_image')" />
        @else
            <meta property='og:image' content="{{ url('/images/common/ogp.jpg') }}" />  
        @endif

        <meta property="og:locale" content="ja_JP" />
        <meta property="fb:app_id" content="150247875730932" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="@gmo_poikatsu" />
        
        @hasSection('twitter_image')
            <meta name="twitter:image" content="@yield('twitter_image')" />
        @else
            <meta name="twitter:image" content="{{ url('/images/common/ogp.jpg') }}" />
        @endif

        @if (Request::path() == 'entries')
        <link rel="canonical" href="{{ url()->current() }}">
        @else
        <link rel="canonical" href="{{ url()->full() }}">
        @endif

        {!! Tag::style('/css/reset_20230425.css') !!}
        {!! Tag::style('/css/sp_common_20230330.css') !!}
        {!! Tag::style('/css/sp_common.css?20240827') !!}
        {!! Tag::style('/css/sp_modal.css?20230718') !!}

        @if (isset($base_css_type))
        @if ($base_css_type == 'top')
        {!! Tag::style('/css/sp_top.css?20240130') !!}
        @endif
        @if ($base_css_type == 'search')
        {!! Tag::style('/css/sp_search_20200623.css') !!}
        @endif
        @if ($base_css_type == 'programs_list')
        {!! Tag::style('/css/sp_programs_list_20230517.css') !!}
        @endif
        @if ($base_css_type == 'detail')
        {!! Tag::style('/css/sp_programs_detail.css?20241023') !!}
        @endif
        @if ($base_css_type == 'review')
        {!! Tag::style('/css/sp_review_20220804.css') !!}
        @endif
        @if ($base_css_type == 'signup')
        {!! Tag::style('/css/sp_signup.css?20230825') !!}
        {!! Tag::style('/css/sp_feature.css?20240313') !!}
        @endif
        @if ($base_css_type == 'entries')
        {!! Tag::style('/css/sp_entries.css?20240422') !!}
        {!! Tag::style('/css/feature.css?20240313') !!}
        @endif
        @if ($base_css_type == 'login')
        {!! Tag::style('/css/sp_login_20230323.css') !!}
        {!! Tag::style('/css/sp_entries.css?20240422') !!}
        @endif
        @if ($base_css_type == 'mypage')
        {!! Tag::style('/css/sp_mypage.css?20232510') !!}
        @endif
        @if ($base_css_type == 'exchange')
        {!! Tag::style('/css/sp_exchange.css?20231005') !!}
        @endif
        @if ($base_css_type == 'question')
        {!! Tag::style('/css/sp_questions_top.css?20230901') !!}
        @endif
        @if ($base_css_type == 'question_detail')
        {!! Tag::style('/css/sp_questions_20230315.css') !!}
        @endif
        @if ($base_css_type == 'shopping')
        {!! Tag::style('/css/sp_shopping_20220829.css') !!}
        @endif
        @if ($base_css_type == 'friends')
        {!! Tag::style('/css/sp_friends_20220826.css') !!}
        @endif
        @if ($base_css_type == 'about')
        {!! Tag::style('/css/sp_about_20220819.css') !!}
        @endif
        @if ($base_css_type == 'fancrew')
        {!! Tag::style('/css/sp_monitor_20220831.css') !!}
        @endif
        @if ($base_css_type == 'withdrawal')
        {!! Tag::style('/css/sp_withdrawal_20220829.css') !!}
        @endif
        @if ($base_css_type == 'recipe')
        {!! Tag::style('/css/sp_recipe_20220809.css') !!}
        @endif
        @if ($base_css_type == 'remind')
        {!! Tag::style('/css/sp_remind_20220829.css') !!}
        @endif
        @if ($base_css_type == 'status')
        {!! Tag::style('/css/sp_status_20220823.css') !!}
        @endif
        @if ($base_css_type == 'incentive')
        {!! Tag::style('/css/sp_incentive_20230427.css') !!}
        @endif
        @if ($base_css_type == 'support')
        {!! Tag::style('/css/sp_support_20220809.css') !!}
        @endif
        @if ($base_css_type == 'sitemap')
        {!! Tag::style('/css/sp_sitemap_20220824.css') !!}
        @endif
        @if ($base_css_type == 'feature')
        {!! Tag::style('/css/sp_feature.css?20240313') !!}
        @endif
        @if ($base_css_type == 'credit_card')
        {!! Tag::style('/css/sp_credit_card_20220721.css') !!}
        @endif
        @if ($base_css_type == 'guide')
        {!! Tag::style('/css/sp_guide.css?20230823') !!}
        @endif
        @if ($base_css_type == 'sky_flag')
        {!! Tag::style('/css/sp_skyflag_20230309.css') !!}
        @endif
        @if ($base_css_type == 'greeadsreward')
        {!! Tag::style('/css/sp_greeadsreward.css?20230905') !!}
        @endif
        @if ($base_css_type == 'api_login')
        {!! Tag::style('/css/sp_api_login_20221019.css') !!}
        @endif
        @if ($base_css_type == 'api_confirm')
        {!! Tag::style('/css/sp_api_confirm_20221019.css') !!}
        @endif
        @if ($base_css_type == 'gmo_tech')
           {!! Tag::style('/css/sp_gmotech.css?20230608') !!}
        @endif
        @if ($base_css_type == 'welkatsu')
           {!! Tag::style('/css/welkatsu.css?20230627') !!}
        @endif
        @if ($base_css_type == 'receipt')
            {!! Tag::style('/css/sp_receipt.css?20230713') !!}
        @endif
        @endif

        @if (isset($hidden_header) && $hidden_header == true)
            {!! Tag::style('/css/hidden_header_sp.css?20231010') !!}
        @endif
        {!! Tag::style('/css/ico_20170621.css') !!}
        {!! Tag::style('/css/sp_breadcrumb.css?20230707') !!}

        @stack('custom_css')

        {!! Tag::script('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', ['type' => 'text/javascript']) !!}

        {!! Tag::style('/css/slick.css') !!}
        {!! Tag::style('/slick-1.6.0/slick-theme_20180312.css') !!}
        {!! Tag::script('/slick-1.6.0/slick.min.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/jquery.collapser.min.js', ['type' => 'text/javascript']) !!}
        {!! Tag::script('/js/purl.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/jquery.leanModal.min.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/base_20180925.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/fixed_btn_sp.js', ['type' => 'text/javascript']) !!}

        @if (config('app.env') == 'production' || config('app.env') == 'development')
        @include('common.verify_for_google')
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TP78Z7F');</script>
        <!-- End Google Tag Manager -->
        @endif

        @yield('layout.plane.head')

        <!-- Hotjar Tracking Code for https://colleee.net/ -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:3774944,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4382171250087855"
        crossorigin="anonymous"></script>

    </head>

    <body>
        @if(request()->is('questions') || request()->is('questions/*') || request()->is('sp_programs'))
            <!-- 広告調整 -->
            <style>
                div.gmossp_core_g942017 iframe { margin-bottom: 62.2px; }
            </style>
            <!-- SPオーバーレイ -->
            <script async src="https://cdn.gmossp-sp.jp/js/async/g942017/gc.js"></script>
            <div class="gmossp_core_g942017">
            <script>
            window.Gmossp=window.Gmossp||{};window.Gmossp.events=window.Gmossp.events||[];
            window.Gmossp.events.push({
            sid: "g942017",
            });
            </script>
            </div>
            <!-- SPインタースティシャル -->
            <script async src="https://cdn.gmossp-sp.jp/js/async/g942015/gc.js"></script>
            <div class="gmossp_core_g942015">
            <script>
            window.Gmossp=window.Gmossp||{};window.Gmossp.events=window.Gmossp.events||[];
            window.Gmossp.events.push({
            sid: "g942015",
            });
            </script>
            </div>
        @endif

        @if (config('app.env') == 'production' || config('app.env') == 'development')
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TP78Z7F"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        @endif

        @yield('layout.plane.body')
    </body>
</html>
