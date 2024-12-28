<!DOCTYPE html>
<html>
    <head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article#">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta charset="utf-8" />
        <link href="/favicon-v2.ico" type="image/x-icon" rel="icon" />
        <link href="/favicon-v2.ico" type="image/x-icon" rel="shortcut icon" />
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

        <!--ここからSNSまわり-->
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
        <!--/snsまわりここまで-->

        @if (Request::path() == 'entries')
        <link rel="canonical" href="{{ url()->current() }}">
        @else
        <link rel="canonical" href="{{ url()->full() }}">
        @endif

        {!! Tag::style('/css/reset_20230425.css') !!}
        {!! Tag::style('/css/common.css?20241204') !!}
        {!! Tag::style('/css/ico_20170621.css') !!}
        {!! Tag::style('/css/common_20230424.css') !!}
        {!! Tag::style('/css/modal.css?20240326') !!}
        {!! Tag::style('/css/breadcrumb.css?20230707') !!}
        {!! Tag::style('/css/sidebar.css?20240710') !!}

        @if (isset($base_css_type))
        @if ($base_css_type == 'top')
        {!! Tag::style('/css/top.css?20240130') !!}
        @endif
        @if ($base_css_type == 'search')
        {!! Tag::style('/css/search_20200623.css') !!}
        @endif
        @if ($base_css_type == 'programs_list')
        {!! Tag::style('/css/programs_list_20230517.css') !!}
        @endif
        @if ($base_css_type == 'detail')
        {!! Tag::style('/css/programs_detail.css?20241204') !!}
        @endif
        @if ($base_css_type == 'shopping')
        {!! Tag::style('/css/shopping_20220829.css') !!}
        @endif
        @if ($base_css_type == 'login')
        {!! Tag::style('/css/login_20230323.css') !!}
        {!! Tag::style('/css/entries.css?20240612') !!}
        @endif
        @if ($base_css_type == 'signup')
        {!! Tag::style('/css/signup_20230323.css') !!}
        {!! Tag::style('/css/feature.css?20240313') !!}
        @endif
        @if ($base_css_type == 'entries')
        {!! Tag::style('/css/entries.css?20240514') !!}
        @endif
        @if ($base_css_type == 'review')
        {!! Tag::style('/css/review_20220804.css') !!}
        @endif
        @if ($base_css_type == 'mypage')
        {!! Tag::style('/css/mypage.css?20231025') !!}
        @endif
        @if ($base_css_type == 'exchange')
        {!! Tag::style('/css/exchange.css?20231005') !!}
        @endif
        @if ($base_css_type == 'fancrew')
        {!! Tag::style('/css/monitor_20230221.css') !!}
        @endif
        @if ($base_css_type == 'withdrawal')
        {!! Tag::style('/css/withdrawal_20220829.css') !!}
        @endif
        @if ($base_css_type == 'recipe')
        {!! Tag::style('/css/recipe_20220809.css') !!}
        @endif
        @if ($base_css_type == 'friends')
        {!! Tag::style('/css/friends_20220826.css') !!}
        @endif
        @if ($base_css_type == 'remind')
        {!! Tag::style('/css/remind_20220829.css') !!}
        @endif
        @if ($base_css_type == 'status')
        {!! Tag::style('/css/status_20220823.css') !!}
        @endif
        @if ($base_css_type == 'about')
        {!! Tag::style('/css/about_20220819.css') !!}
        @endif
        @if ($base_css_type == 'support')
        {!! Tag::style('/css/support_20220809.css') !!}
        @endif
        @if ($base_css_type == 'question')
        {!! Tag::style('/css/questions_top.css?20240202') !!}
        @endif
        @if ($base_css_type == 'question_detail')
        {!! Tag::style('/css/questions_20230315.css') !!}
        @endif
        @if ($base_css_type == 'incentive')
        {!! Tag::style('/css/incentive_20230427.css') !!}
        @endif
        @if ($base_css_type == 'sitemap')
        {!! Tag::style('/css/sitemap_20220824.css') !!}
        @endif
        @if ($base_css_type == 'feature')
        {!! Tag::style('/css/feature.css?20240313') !!}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css">
        @endif
        @if ($base_css_type == 'credit_card')
        {!! Tag::style('/css/credit_card_20220715.css') !!}
        @endif
        @if ($base_css_type == 'guide')
        {!! Tag::style('/css/guide.css?20230823') !!}
        @endif
        @if ($base_css_type == 'skyflag' || $base_css_type == 'greeadsreward')
        <!-- 共通化できそうなのでcssファイル名は変えたほうがよい -->
        {!! Tag::style('/css/skyflag_20230309.css') !!}
        @endif
        @if ($base_css_type == 'api_login')
        {!! Tag::style('/css/api_login_20220728.css') !!}
        @endif
        @if ($base_css_type == 'api_confirm')
        {!! Tag::style('/css/api_confirm_20221019.css') !!}
        @endif
        @if ($base_css_type == 'receipt')
        {!! Tag::style('/css/receipt.css?20230713') !!}
        @endif
        @if ($base_css_type == 'gmo_tech')
            {!! Tag::style('/css/gmo_tech.css?20230609') !!}
        @endif
        @endif

        @if (isset($hidden_header) && $hidden_header == true)
            {!! Tag::style('/css/hidden_header.css?20231010') !!}
        @endif

        @stack('custom-css')

        {{ Tag::script('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', ['type' => 'text/javascript']) }}
        {!! Tag::script('/js/pop_win.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/jquery.collapser.min.js', ['type' => 'text/javascript']) !!}
        {!! Tag::script('/js/purl.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/base_20180925.js', ['type' => 'text/javascript']) !!}

        {!! Tag::script('/js/fixed_btn.js', ['type' => 'text/javascript']) !!}

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
        @if (config('app.env') == 'production' || config('app.env') == 'development')
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TP78Z7F"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        @endif
        @yield('layout.plane.body')
        {{ Tag::script('https://cache.img.gmo.jp/gmo/header/script.min.js', ['charset' => 'UTF-8', 'type' => 'text/javascript', 'id' => 'gmoheadertag', 'async' => 'async']) }}

        @if(request()->is('questions') || request()->is('questions/*') || request()->is('sp_programs'))
            <!-- PCオーバーレイ -->
            <script async src="https://cdn.gmossp-sp.jp/js/async/g942018/gc.js"></script>
            <div class="gmossp_core_g942018">
            <script>
            window.Gmossp=window.Gmossp||{};window.Gmossp.events=window.Gmossp.events||[];
            window.Gmossp.events.push({
            sid: "g942018",
            });
            </script>
            </div>
            <!-- PCインタースティシャル -->
            <script async src="https://cdn.gmossp-sp.jp/js/async/g942016/gc.js"></script>
            <div class="gmossp_core_g942016">
            <script>
            window.Gmossp=window.Gmossp||{};window.Gmossp.events=window.Gmossp.events||[];
            window.Gmossp.events.push({
            sid: "g942016",
            });
            </script>
            </div>
            <!-- 調整 -->
            <script>
                // 監視対象の要素を選択
                const targetNode = document.body;
                // 監視の設定
                const config = { attributes: true, attributeFilter: ['style'] };
                // 変更が検出されたときのコールバック関数
                const callback = function(mutationsList, observer) {
                    for(let mutation of mutationsList) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                            const currentPosition = targetNode.style.position;
                            if (currentPosition === 'initial') {
                                // position: initial; を撤去する
                                targetNode.style.position = '';
                                // 監視を停止
                                observer.disconnect();
                                // 変更が検出され、処理されたらループを抜ける
                                break;
                            }
                        }
                    }
                };
                // Mutation Observerインスタンスを作成
                const observer = new MutationObserver(callback);
                // 監視を開始
                observer.observe(targetNode, config);
            </script>
        @endif
    </body>
</html>
