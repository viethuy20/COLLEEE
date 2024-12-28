@php
$base_css_type = 'welkatsu';
@endphp
@extends('layouts.default')


@section('layout.title', 'ポイントの価値が1.5倍！ウエル活って知ってる？｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,ウエル活,ウエルシア')
@section('layout.description', 'ドラッグストアのウエルシア薬局等で、毎月20日に開催している『ウエルシアお客様感謝デー』を活用したお買い物方法の事です。ウエル活はGMOポイ活で！')
@section('url', route('welkatsu'))
@section('og_type', 'website')

@section('layout.content')
<!-- page nav -->
<div class="inner u-pt-5">
    <div class="contents__box u-mt-small u-pt-remove u-pb-remove" id="step">
        <h1 class="welkatsu__about_fv">
            <img src="images/welkatsu/fv.png" alt="ウエル活って知ってる？">
        </h1>
        <h2 class="welkatsu__title u-mt-20">
            <img src="images/welkatsu/title_01.png" alt="ウエル活ってなに？">
        </h2>

        <div class="welkatsu__about">
            <p class="text--18 u-font-bold u-mt-20">毎月20日の『ウエルシアお客様感謝デー』を活用したお得なお買い物方法</p>
            <p class="text--15 u-mt-20">
                ドラッグストアのウェルシア薬局やハックドラッグ等で、毎月20日に開催している『ウエルシアお客様感謝デー』を活用したお買物方法の事です。日用品や食料品を実質33％引きでお買い物ができるお得な節約術としても注目されています！
            </p>
            <div class="u-mt-20">
                <img src="images/welkatsu/point_flow.png" alt="毎月20日の『ウエルシアお客様感謝デー』を活用したお得なお買い物方法">
            </div>
        </div>

    </div>
</div>

<!-- how much -->
<div class="inner">
    <div class="contents__box u-mt-small u-pt-small howmuch_bg" id="step">
        <h2 class="welkatsu__title">
            <img src="images/welkatsu/title_02.png" alt="どのくらいお得なの？">
        </h2>
        <div class="welkatsu__about u-pt-5">
            <img src="images/welkatsu/howmuch_flow.png" alt="Tポイントの価値が1.5倍！">
        </div>
    </div>
</div>

<!-- exchange -->
<div class="inner">
    <div class="contents__box u-mt-small" id="step">
        <h2 class="welkatsu__title">
            <img class="multiple" src="images/welkatsu/title_03.png" alt="GMOポイ活ポイントをたくさん貯めてTポイントに交換しよう！">
        </h2>

        <div class="welkatsu__exchange">
            <p class="text--15 u-mt-20">どうやってGMOポイントを貯めればいいの？GMOポイントからTポイントへの交換方法は？ウエル活初心者の方に、ポイントの貯め方やTポイントへの交換方法をご紹介！</p>
            <img class="u-mt-20" src="images/welkatsu/tpoint_question.png" alt="GMOポイ活ポイントをたくさん貯めてTポイントに交換しよう！">

            <div class="u-mt-20">
                <img src="images/welkatsu/tpoint_title.png" alt="GMOポイ活おすすめの貯め方3選！">
                <ul class="u-mt-5">
                    <li>
                        <img src="images/welkatsu/tpoint_howto01.png" alt="ゲームを楽しみながら無料で貯める！">
                        <p>GMOポイ活での人気タイトルもたくさん！すきま時間にゲームして達成条件をクリアするとポイント獲得！</p>
                        <div class="welkatsu__exchange__btn">
                            <a href="/skyflag">
                                <img src="images/welkatsu/tpoint_btn01.png" alt="ゲームで貯める">
                            </a>
                        </div>
                    </li>
                    <li>
                        <img src="images/welkatsu/tpoint_howto02.png" alt="クレジットカード・投資で大量に貯める！">
                        <p>毎日コツコツと貯めるのは面倒という人にオススメ！クレジットカードや投資は高額のポイントがもらえる広告がたくさん！まだ持っていない、気になるクレジットカードがある場合はこの機会に発行してみては？</p>
                        <div class="welkatsu__exchange__btn">
                            <a href="/programs/list?ll%5B0%5D=125">
                                <img src="images/welkatsu/tpoint_btn02.png" alt="クレジットカードで貯める">
                            </a>
                        </div>
                    </li>
                    <li>
                        <img src="images/welkatsu/tpoint_howto03.png" alt="月額サービスで即貯める！（スマホ版限定）">
                        <p>スマホ限定の『月額サービス』は動画・コミック・ミュージックなどのコンテンツが盛りだくさん！初月無料や月額の100％以上還元、毎月繰り返し登録が可能な広告もあるので上手に利用してお得にお得にためてみよう！</p>
                        <div class="welkatsu__exchange__btn">
                            <a href="/programs/list?ll%5B0%5D=132">
                                <img src="images/welkatsu/tpoint_btn03.png" alt="月額サービスで貯める">
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- 交換方法 -->
<div class="inner">
    <div class="contents__box u-mt-small" id="step">
        <h2 class="welkatsu__title">
            <img class="multiple" src="images/welkatsu/title_04.png" alt="GMOポイントからTポイントへの交換方法">
        </h2>

        <div class="welkatsu__flow">
            <p class="text--15 u-mt-20">GMOポイ活では、貯めたポイントをドットマネー経由でTポイントに交換すれば<span class="u-font-bold red">手数料無料</span>で交換できます。</p>

            <ul class="u-mt-20">
                <li>
                    <img src="images/welkatsu/flow_01.png" alt="手順1">
                </li>
                <li class="u-mt-40">
                    <img src="images/welkatsu/flow_02.png" alt="手順2">
                </li>
            </ul>

            <p class="u-mt-20">
                ※Tポイントに交換するには、Tポイント利用手続き（YAHOO!JAPAN ID連携）が必要です。<br>
                ※ドットマネーからTポイントの最低交換額は1,000マネーです。<br>
                ※Tポイントへの交換タイミングは、土日を除いて4営業日かかります。
            </p>
        </div>
    </div>
</div>

<!-- ドットマネー -->
<div class="inner">
    <div class="contents__box u-mt-small" id="step">
        <img src="images/welkatsu/dotmoney_about.png" alt="ドットマネー">
        <a href="/dot_money">
            <img class="u-mt-20" src="images/welkatsu/dotmoney_btn.png" alt=".moneyに交換する">
        </a>
    </div>
</div>



@endsection
