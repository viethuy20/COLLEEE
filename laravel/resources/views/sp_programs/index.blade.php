@php
$base_css_type = 'incentive';
@endphp
@extends('layouts.default')

@section('layout.title', '毎日ゲット｜ポイントサイトならGMOポイ活')
@section('layout.description', 'ゲームで遊んで、クイズに答えて、記事の感想を投稿してポイントが貯めよう！コツコツ毎日ポイントをゲット♪貯めたポイントは現金やギフト券に交換することができます。')
@section('url', route('sp_programs.index') )
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
            毎日ゲット
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

<!-- main contents -->
<div class="contents">
    <!-- page title -->
    <h1>
        {{ Tag::image('/images/sp_programs/sp_programs_ttl.png', '毎日ゲット！') }}
    </h1>

    <!-- ゲームでゲット -->
    <h2 class="contents__ttl">ゲームでゲット</h2>
    <ul class="get_game__list">
        <!-- </ul>

    <ul class="get_game__list"> -->
        <li>
            @php
            $gacha_mainte = \App\Mainte::ofType(\App\Mainte::GACHA_TYPE)->first();
            @endphp
            <p class="get_game__list__ttl">GMOポイ活ガチャ</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/gacha.png', 'GMOポイ活ガチャ') }}
            </div>
            <div class="get_game__list__chart__li">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>1日最大3回</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>即日</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($gacha_mainte))
                最大100ptが当たる！ガチャを回してポイントを貯めよう！1日最大3回まで回せるよ♪
                @else
                {!! nl2br(e($gacha_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($gacha_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::GACHA_TYPE]), 'あそぶ', ['target' => '_blank',
                'onmousedown' => "ga('send', 'event', '毎日ゲット', 'click', 'GMOポイ活ガチャ', {'nonInteraction': 1});"]) }}
            </div>
            @endif
        </li>




        <li>
            @php
            $brain_exercies_mainte = \App\Mainte::ofType(\App\Mainte::BRAIN_EXERCIES)->first();
            @endphp
            <p class="get_game__list__ttl">頭の体操</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/brain_exercies.png', '頭の体操') }}
            </div>
            <div class="get_game__list__chart__li">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>各ゲーム1日1回</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>即日</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($brain_exercies_mainte))
                計算ゲームや英単語クイズ、ナンプレ、クロスワード、詰将棋など様々な頭の体操を用意しています。1ゲームクリアで1スタンプ獲得でき、10スタンプ貯まるとポイントを獲得することができます！
                @else
                {!! nl2br(e($brain_exercies_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($brain_exercies_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::BRAIN_EXERCIES]), 'あそぶ', ['target' => '_blank',
                'onmousedown' => "ga('send', 'event', '毎日ゲット', 'click', '頭の体操', {'nonInteraction': 1});"]) }}
            </div>
            @endif
            </li>
            <li>
                @php
                $farmlife_mainte = \App\Mainte::ofType(\App\Mainte::FARM_LIFE)->first();
                @endphp
            <p class="get_game__list__ttl">農場生活</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/farm_life.png', '農場生活') }}

            </div>
            <div class="get_game__list__chart__li">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>1日最大9回</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>即日～1週間</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($farmlife_mainte))
                フルーツを育てる農場シミュレーションゲーム。1週間の間（月曜日00:00～日曜日23:59）に収穫したフルーツの「収穫ポイント」によってポイントが獲得できます。ランキング上位で高ポイントに！
                @else
                {!! nl2br(e($farmlife_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($farmlife_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::FARM_LIFE]), 'あそぶ', ['target' => '_blank',
                'onmousedown' => "ga('send', 'event', '毎日ゲット', 'click', '農場生活', {'nonInteraction': 1});"]) }}
            </div>
            @endif
        </li>
        <li>
            @php
            $fruful_mainte = \App\Mainte::ofType(\App\Mainte::FRUFUL)->first();
            @endphp
            <p class="get_game__list__ttl">ふるふるサファリ</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/fruful_safari.png', 'ふるふるサファリ') }}
            </div>
            <div class="get_game__list__chart__li">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>6時間毎に3回</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>即日</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($fruful_mainte))
                木から落ちてくるポノスケとシマウマをクリックして、得点を貯めましょう！バッジを5コあつめると、1ポイントに自動交換されます。ふるふるサファリの世界へようこそ！
                @else
                {!! nl2br(e($fruful_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($fruful_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::FRUFUL]), 'あそぶ', ['target' => '_blank',
                'onmousedown' => "ga('send', 'event', '毎日ゲット', 'click', 'ふるふるサファリ', {'nonInteraction': 1});"]) }}
            </div>
            @endif
        </li>

        <li>
            @php
            $easy_game_box_quiz_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_QUIZ)->first();
            @endphp
            <p class="get_game__list__ttl">まいにちクイズボックス</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_quiz_banner.png', 'まいにちクイズボックス') }}
            </div>
            <div class="get_game__list__chart">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>1日3回</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>1週間</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($easy_game_box_quiz_mainte))
                毎日3回開催されるクイズ大会に参加してスタンプを集めよう！スタンプはクイズに正解すると獲得でき12個集めると最大2,000ポイントが当たる抽選に参加できるよ！
                @else
                {!! nl2br(e($easy_game_box_quiz_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($easy_game_box_quiz_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_QUIZ]), 'あそぶ', ['target' =>
                '_blank', 'onmousedown' => "ga('send', 'event', '毎日ゲット', 'click', 'まいにちクイズボックス', {'nonInteraction':
                1});"]) }}
            </div>
            @endif
        </li>
        <li>
            @php
            $easy_game_box_game_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_GAME)->first();
            @endphp
            <p class="get_game__list__ttl">かんたんゲームボックス</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_game_banner.png', 'かんたんゲームボックス') }}
            </div>
            <div class="get_game__list__chart">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>なし</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>1週間</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($easy_game_box_game_mainte))
                100種類以上のミニゲームをプレイしてみよう！ゲームを遊んだ結果に応じて「抽選券」を獲得し、100枚貯めると最大1,000ポイントが当たる抽選に参加できるよ！
                @else
                {!! nl2br(e($easy_game_box_game_mainte->message)) !!}
                @endif
            </p>

            @if (!isset($easy_game_box_game_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_GAME]), 'あそぶ', ['target' =>
                '_blank', 'onmousedown' => "ga('send', 'event', '毎日ゲット', 'click', 'かんたんゲームボックス', {'nonInteraction':
                1});"]) }}
            </div>
            @endif
        </li>

        <li>
            @php
            $easy_game_box_slot_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_SLOT)->first();
            @endphp
            <p class="get_game__list__ttl">運だめし　スロットボックス</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_slot_banner.png', '運だめし　スロットボックス') }}
            </div>
            <div class="get_game__list__chart">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>所持メダルが3枚未満になるまで</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>1週間</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($easy_game_box_slot_mainte))
                メダルを使ってスロットをまわそう！役がそろうとメダルがもらえます！メダルを300枚集めると最大2,000ポイントが当たる抽選に参加できるよ！
                @else
                {!! nl2br(e($easy_game_box_slot_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($easy_game_box_slot_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_SLOT]), 'あそぶ', ['target' =>
                '_blank', 'onmousedown' => "ga('send', 'event', '所持メダルが3枚未満になるまで', 'click', '運だめし　スロットボックス',
                {'nonInteraction': 1});"]) }}
            </div>
            @endif
        </li>
        <li>
            @php
            $easy_game_box_spot_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_SPOT)->first();
            @endphp
            <p class="get_game__list__ttl">間違い探しボックス</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_spot_banner.png', '間違い探しボックス') }}
            </div>
            <div class="get_game__list__chart">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>最大9問</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>1週間</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($easy_game_box_spot_mainte))
                まちがい探しをクリアしてルーペを集めよう！
                問題は最大9問遊ぶことができるよ！
                ルーペを100個集めて最大2,000ポイント当たる抽選に参加しよう！
                @else
                {!! nl2br(e($easy_game_box_spot_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($easy_game_box_spot_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_SPOT]), 'あそぶ', ['target' =>
                '_blank', 'onmousedown' => "ga('send', 'event', '最大9問', 'click', '間違い探しボックス',
                {'nonInteraction': 1});"]) }}
            </div>
            @endif
        </li>

        <li>
            @php
            $sansan_mainte = \App\Mainte::ofType(\App\Mainte::SANSAN_TYPE)->first();
            @endphp
            <p class="get_game__list__ttl">魁！タイプ塾</p>
            <div class="get_game__list__thumb">
                {{ Tag::image('/images/sansan_logo_pc.gif', '魁！タイプ塾') }}
            </div>
            <div class="get_game__list__chart">
                <div class="get_game__list__chart__list">
                    <h2>プレイ条件</h2>
                    <ul>
                        <li>なし</li>
                    </ul>
                </div>
                <div class="get_game__list__chart__list">
                    <h2>獲得時期</h2>
                    <ul>
                        <li>翌日</li>
                    </ul>
                </div>
            </div>
            <p class="get_game__list__txt">
                @if (!isset($sansan_mainte))
                タイプ入力で無制限にポイントゲット！？デイリーランキング入賞でボーナスも貰える！
                @else
                {!! nl2br(e($sansan_mainte->message)) !!}
                @endif
            </p>
            @if (!isset($sansan_mainte))
            <div class="get_game__list__btn">
                {{ Tag::link(route('asps.click', ['asp' => \App\Asp::SANSAN_TYPE]), 'あそぶ', ['onmousedown' =>
                "ga('send', 'event', '毎日ゲット', 'click', '魁！タイプ塾', {'nonInteraction': 1});"]) }}
            </div>
            @endif
        </li>

        
    </ul>

    <!-- クリックでゲット -->
    @if (!$click_list->isEmpty())
    <h2 class="contents__ttl">クリックでゲット</h2>
    <ul class="get_click__list">
        @foreach($click_list as $sp_program)
        @php
        $click_url = route('sp_programs.click', ['sp_program' => $sp_program]);
        $onmousedown = "ga('send', 'event', '{{ ".$click_url." }}', 'click', 'luckyclick_{{ ".$sp_program->id." }}',
        {'nonInteraction': 1});";
        $sp_program_data = json_decode($sp_program->data);
        @endphp
        <li>
            <div class="get_click__list__ttl">{{ $sp_program->title }}</div>
            <div class="get_click__list__link">{{ Tag::link($click_url, '※バナー（画像）が表示されない場合は、ここをクリックしてください。', ['target'
                => '_blank', 'onmousedown' => $onmousedown]) }}
                @if ($sp_program->join_status > 0)
                <br><span>本日分はすでにクリック済みです。</span>
                @endif
            </div>
            <div class="get_click__list__bnr">
                {{ Tag::link($click_url, Tag::image($sp_program_data->img_url, $sp_program->title), ['target' =>
                '_blank', 'onmousedown' => $onmousedown], null, false) }}
            </div>
        </li>
        @endforeach

        {{-- <li>
            <div class="get_click__list__ttl">【PR】※クリックしてもポイントは付きません</div>
            <div class="get_click__list__link"></div>
            <div class="get_click__list__bnr" style="margin: 10px auto 0;width: 468px;">
                <script async
                    src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5058246241263959"
                    crossorigin="anonymous"></script>

                <ins class="adsbygoogle" style="display:inline-block;width:468px;height:60px"
                    data-ad-client="ca-pub-5058246241263959" data-ad-slot="8476509447"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </li> --}}
    </ul>
    @endif
</div>
@endsection
