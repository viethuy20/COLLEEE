    <?php $base_css_type = 'signup'; ?>
    @extends('layouts.default')

    @section('layout.title', 'GMOポイ活ってどんなサービス？ | ポイントサイトならGMOポイ活')
    @section('layout.keywords', 'GMOポイ活,GMOポイ活ってどんなサービス？,無料')
    @section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')
    @section('url', route('entries.about'))

    @section('layout.head')
    {{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.css') }}
    {!! Tag::style('/css/common_20240613.css') !!}
    {{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js', ['type' => 'text/javascript']) }}
    @endsection

    @php
    $meta = new \App\Services\Meta;
    $arr_breadcrumbs = $meta->setBreadcrumbs(null);

    $application_json = '';
    $position = 1;
    foreach($arr_breadcrumbs as $key => $val) {
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
        $position++;
    }
    $link = route('entries.about');
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "GMOポイ活ってどんなサービス？", "item": "' . $link . '"},';

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
                GMOポイ活ってどんなサービス？
            </li>
        </ol>
    </section>
    @endsection

    @section('layout.content')
    <div class="contents">

        <h2>{{ Tag::image('/images/about/about_ttl.png', 'GMOポイ活ってどんなサービス？') }}</h2>

        <div class="contents__box u-mt-20">
            <p class="text--15">いつものネットショッピングやサービスを利用する前に「GMOポイ活」を経由するだけで、GMOポイ活のポイントが貯まります。<br>
            貯まったポイントは現金や各種ポイントなどと交換できます。</p>
            <div class="about__point">
                <ul>
                    <li>
                        <div class="num">1</div>
                        <div class="txt">GMOポイ活を経由して<br>ショップやサービスを利用</div>
                        <div class="image">{{ Tag::image('/images/login/login_point1.png') }}</div>
                    </li>
                    <li>
                        <div class="num">2</div>
                        <div class="txt">GMOポイ活の<br>ポイントが貯まる！</div>
                        <div class="image">{{ Tag::image('/images/login/login_point2.png') }}</div>
                    </li>
                    <li>
                        <div class="num">3</div>
                        <div class="txt">貯まったポイントを<br>現金やギフト券に交換！</div>
                        <div class="image">{{ Tag::image('/images/login/login_point3.png') }}</div>
                    </li>
                </ul>
            </div>

            <h3 class="about__ttl u-mt-40">ポイントを貯めよう！</h3>
            <p class="text--15 u-mt-20">ネットショッピングやサービスをはじめ、ゲームで遊んだり、アンケートに答えるだけでポイントが貯まります。</p>
            <div class="about__save">
                <ul>
                    <li>
                        <div class="txt">ショッピングで</div>
                        <div class="image">{{ Tag::image('/images/about/about_save1.png') }}</div>
                    </li>
                    <li>
                        <div class="txt">ゲームで</div>
                        <div class="image">{{ Tag::image('/images/about/about_save2.png') }}</div>
                    </li>
                    <li>
                        <div class="txt">アンケートで</div>
                        <div class="image">{{ Tag::image('/images/about/about_save3.png') }}</div>
                    </li>
                </ul>
            </div>

            <h3 class="about__ttl u-mt-40">ポイントを交換しよう！</h3>
            <p class="text--15 u-mt-20">貯まったポイントは300ポイント（300円相当）から交換可能！現金・ギフト券・各種ポイントなどさまざまな交換先からお好きなものを選べます。</p>
            <div class="about__exchange">
                <ul>
                    <li><img src="/images/exchanges/img_bank.png" alt="現金（銀行振込）"></li>
					<li><img src="/images/exchanges/img_amazon.png" alt="Amazonギフトカード"></li>
					<li><img src="/images/exchanges/img_pex.png" alt="PeXポイントギフト"></li>
					<li><img src="/images/exchanges/img_money.png" alt="ドットマネー"></li>
                    <li><img src="/images/exchanges/img_paypay.png" alt="PayPay"></li>
					<li><img src="/images/exchanges/img_edy.png" alt="EdyギフトID"></li>
					<li><img src="/images/exchanges/img_waon.png" alt="WAONポイントID"></li>
                    <li><img src="/images/exchanges/img_dpoint.png" alt="d POINT"></li>
                    <li><img src="/images/exchanges/img_digital-gift.png" alt="デジタルギフト"></li>
                    <li><img src="/images/exchanges/img_jal.png" alt="JALマイル"></li>
                    <li><img src="/images/exchanges/img_paypal.png" alt="PayPal"></li>
					{{-- <li><img src="/images/exchanges/img_linepay.png" alt="LINE Pay"></li> --}}
					<li><img src="/images/exchanges/img_google.png" alt="Google Play ギフトコード"></li>
					<li><img src="/images/exchanges/img_apple.png" alt="App Store &amp; iTunes ギフトカード"></li>
					<li><img src="/images/exchanges/img_ponta.png" alt="Pontaポイント"></li>
					<li><img src="/images/exchanges/img_pssticket.png" alt="プレイステーション ストアチケット"></li>
                    <li><img src="/images/exchanges/img_kdol.png" alt="KDOL" /></li>
                </ul>
            </div>

            <h3 class="about__ttl u-mt-40">GMOポイ活のここがおすすめ！</h3>
            <div class="about__suggest">
                <ul>
                    <li>
                        <div class="txt">GMOインターネットグループが運営しているので安全性はおりがみ付き！初心者でも安心です</div>
                    </li>
                    <li>
                        <div class="txt">毎週100件以上の新規広告を掲載！</div>
                    </li>
                    <li>
                        <div class="txt">ポイント交換は最低300ポイント（300円相当）から可能！</div>
                    </li>
                    <li>
                        <div class="txt">記事コンテンツでキャンペーン情報がすぐわかる！</div>
                    </li>
                </ul>
            </div>
        </div>


        <!-- 新規会員登録 -->
        <div class="contents__box u-mt-20">
            {!! Tag::formOpen(['url' => route('entries.send'), 'class' => 'about_form']) !!}
            @csrf    
            <p class="text--15">入力されたメールアドレスに、無料会員登録のご案内メールが届きます。</p>
                <div class="u-mt-small">
                    {!! Tag::formText('email', '', ['placeholder' => 'メールアドレスを入力してください', 'required' => 'required']) !!}
                </div>
                @if ($errors->has('email'))
                <!--エラーの場合はここに-->
                <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('email') }}</p>
                @endif
                @if (Session::has('message'))
                <p class="error_message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
                @endif

                <div class="about_form__privacy">
                    <p class="u-text-ac">
                        {!! Tag::formCheckbox('consent', 1, false) !!}
                        「{!! Tag::link(route('abouts.membership_contract'), 'GMOポイ活会員利用規約') !!}」および
                        「個人情報の取扱いについて」
                        を確認し、同意します。
                    </p>
                </div>
                <textarea class="contentTerm" name="content" rows="10" id="contentTerm" disabled>{{ config('text.about_handing_of_personal_information') }}</textarea>
                @if ($errors->has('consent'))
                <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('consent') }}</p>
                @endif

                <div class="about_form__btn">
                    {!! Tag::formButton('メールアドレスで登録する', ['type' => 'submit']) !!}
                </div>

                <div class="about_form__privacy u-mt-40">
                    <p>入力・送信頂いた個人情報は、<a href="https://www.koukoku.jp" target="_blank" class="external">GMO NIKKO株式会社</a>が適切に管理し、「個人情報の取り扱いについて」に記載する利用目的の範囲内で利用いたします。</p>
                </div>
                @php
                     $lineService = new \App\Services\Line\LineService();
                     $urlLine = $lineService->getLoginBaseUrl();
                @endphp
                <div class="line_register_about">
                    <p class="head">LINE と連携して会員登録</p>
                    <p class="below">LINEと連携すると、次回からログインが簡単になります!</p>
                    <div class="login__form__btn_line">
                        <a href="{{$urlLine}}">
                            {{ Tag::image('/images/regist/btn_line_register.png')}}</a>
                    </div>
                </div>
                <hr class="bd_dot_gray u-mt-40">
                @php
                if (isset($referer)) {
                    $loginUrl = route('login') . '?'. http_build_query(['referer' => $referer]);
                } else {
                    $loginUrl = route('login', ['back' => 0]);
                }
                 $lineService = new \App\Services\Line\LineService();
                 $urlLine = $lineService->getLoginBaseUrl();
                @endphp
                <p class="text--15 u-font-bold u-text-ac u-mt-40">すでに会員の方</p>
                <div class="about_form__btn__login">
                    {!! Tag::link($loginUrl, 'ログインする') !!}
                </div>
            {!! Tag::formClose() !!}
        </div>


        <!-- お買い物ピックアップ -->
        @php
        $program_pickup_list = \App\Content::ofSpot(\App\Content::SPOT_PROGRAM_PICKUP)
            ->orderBy('priority', 'asc')
            ->get();

        // 有効なプログラムを取得
        $program_id_list = [];
        foreach ($program_pickup_list as $program_pickup) {
            $program_pickup_data = json_decode($program_pickup->data);
            $program_id_list[] = $program_pickup_data->program_id;
        }
        $program_list = collect();
        if (!empty($program_id_list)) {
            $program_list = \App\Program::ofEnable()
                ->whereIn('id', $program_id_list)
                ->orderBy('id', 'desc')
                ->get();
        }
        @endphp
        @if (!$program_list->isEmpty())
        @php
        $program_list = $program_list->splice(0, 6);
        $accept_days_map = config('map.accept_days');
        @endphp
        <div class="about__pickup u-mt-40">
            <div class="about__pickup__ttl">
                <div class="about__pickup__ttl__jp">お買い物ピックアップ</div>
                <div class="about__pickup__ttl__en">PICK UP</div>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-container swiper2">
                <ul class="swiper-wrapper">
                    @foreach ($program_list as $program)
                    <li class="swiper-slide">
                        <div class="slide-img">
                            {{ Tag::image($program->affiriate->img_url, $program->title, ['width' => '100%']) }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="swiper-pagination2"></div>
        </div>
        @endif

        <!-- ゲームでゲット -->
        <h2 class="contents__ttl u-mt-40">ゲームでゲット</h2>
        <ul class="about__list">
            <li>
                <p class="about__list__ttl">まいにちクイズボックス</p>
                <div class="about__list__thumb">
                    {{ Tag::image('/images/gmo_easy_game_box_quiz_banner.png', 'まいにちクイズボックス') }}
                </div>
                <dl class="about__list__chart">
                    <dt>プレイ上限</dt>
                    <dd>1日3回</dd>
                    <dt>獲得時期</dt>
                    <dd>1週間</dd>
                </dl>
                <p class="about__list__txt">毎日3回開催されるクイズ大会に参加してスタンプを集めよう！スタンプはクイズに正解すると獲得でき12個集めると最大2,000ポイントが当たる抽選に参加できるよ！</p>
            </li>
            <li>
                <p class="about__list__ttl">かんたんゲームボックス</p>
                <div class="about__list__thumb">
                    {{ Tag::image('/images/gmo_easy_game_box_game_banner.png', 'かんたんゲームボックス') }}
                </div>
                <dl class="about__list__chart">
                    <dt>プレイ上限</dt>
                    <dd>なし</dd>
                    <dt>獲得時期</dt>
                    <dd>1週間</dd>
                </dl>
                <p class="about__list__txt">100種類以上のミニゲームをプレイしてみよう！ゲームを遊んだ結果に応じて「抽選券」を獲得し、100枚貯めると最大1,000ポイントが当たる抽選に参加できるよ！</p>
            </li>
            <li>
                <p class="about__list__ttl">魁！タイプ塾</p>
                <div class="about__list__thumb">
                    {{ Tag::image('/images/sansan_logo_pc.gif', '魁！タイプ塾') }}
                </div>
                <dl class="about__list__chart">
                    <dt>プレイ上限</dt>
                    <dd>なし</dd>
                    <dt>獲得時期</dt>
                    <dd>翌日</dd>
                </dl>
                <p class="about__list__txt">タイプ入力で無制限にポイントゲット！？デイリーランキング入賞でボーナスも貰える！</p>
            </li>
        </ul>

    </div><!-- /.contents -->
    <script type="text/javascript">
        let swiper2 = new Swiper('.swiper2', {
            loop: true,
            speed: 500,
            slidesPerView: 'auto',
            spaceBetween: 10,
            centeredSlides : true,
            initialSlide: 1,
            effect:'slide',
            // autoplay: {
            // 	delay: 4000,
            // 	disableOnInteraction: false,
            // },
            pagination: {
                el: '.swiper-pagination2',
                type: 'bullets',
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }
        });
    </script>
    @endsection
    @section('layout.footer_notes')
        @php
            $footNotes = '';
        @endphp
        @include('inc.foot-notes', ['footNotes' => $footNotes])
    @endsection
 