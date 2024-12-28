@php
$base_css_type = 'incentive';
@endphp
@extends('layouts.default')

@section('layout.head')
<script type="text/javascript">
    <!--
    $(function() {
        $('.sample').slick({
            autoplay: true,
            autoplaySpeed: 2000,
            dots: true,
            pauseOnHover: true,
            arrows: false
        });
    });
    $(function() {
        $(".itslide").slick({
            autoplay: true,
            pauseOnHover: true,
            arrows: false,
            infinite: true,
            dots: true,
            infinite: true,
            centerMode: true,
            //slidesToShow: 3,
            //slidesToScroll: 3,
            centerMode: true,
            centerPadding: '220px'
        });
    });
    $(function() {
        $('.txt_ureview').collapser({
            mode: 'chars',
            truncate: 70,
            showText: '続きを読む',
            hideText: '閉じる'
        });
    });
    //
    -->
</script>
@endsection
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

@section('layout.title', '毎日ゲット｜ポイントサイトならGMOポイ活')
@section('layout.description', 'ゲームで遊んで、クイズに答えて、記事の感想を投稿してポイントが貯めよう！コツコツ毎日ポイントをゲット♪貯めたポイントは現金やギフト券に交換することができます。')
@section('url', route('sp_programs.index') )
@section('layout.content')
<!-- page title -->
<div class="inner">

    <div class="sp_programs__ttl">
        <h1 class="contents__ttl">
            {{ Tag::image('/images/sp_programs/sp_programs_ttl.png', '毎日ゲット！') }}
        </h1>
    </div>
</div>

<!-- game -->
<div class="inner">
    <div class="sp_programs__ttl">
        <h2 class="contents__ttl">ゲームでゲット</h2>
    </div>

    <ul class="sp_programs__list">
        <li>
            @php
            $gacha_mainte = \App\Mainte::ofType(\App\Mainte::GACHA_TYPE)->first();
            @endphp
            @if (!isset($gacha_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::GACHA_TYPE]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', 'GMOポイ活ガチャ', {'nonInteraction': 1});" target="_blank">
                <p class="sp_programs__list__ttl">GMOポイ活ガチャ</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/gacha.png', 'GMOポイ活ガチャ') }}
                </div>

                <div class="sp_programs__list__txt">
                    最大100ptが当たる！ガチャを回してポイントを貯めよう！1日最大3回まで回せるよ♪
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">GMOポイ活ガチャ</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/fruful_safari.png', 'GMOポイ活ガチャ') }}
            </div>

            <div class="sp_programs__list__txt">
                {!! nl2br(e($gacha_mainte->message)) !!}
            </div>
            @endif
        </li>


        <li>
            @php
            $brain_exercies_mainte = \App\Mainte::ofType(\App\Mainte::BRAIN_EXERCIES)->first();
            @endphp
            @if (!isset($brain_exercies_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::BRAIN_EXERCIES]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', '頭の体操', {'nonInteraction': 1});" target="_blank">
                <p class="sp_programs__list__ttl">頭の体操</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/brain_exercies.png', '頭の体操') }}
                </div>

                <div class="sp_programs__list__txt">
                    計算ゲームや英単語クイズ、ナンプレ、クロスワード、詰将棋など様々な頭の体操を用意しています。1ゲームクリアで1スタンプ獲得でき、10スタンプ貯まるとポイントを獲得することができます！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">頭の体操</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/brain_exercies.png', '頭の体操') }}
            </div>

            <div class="sp_programs__list__txt">
                {!! nl2br(e($brain_exercies_mainte->message)) !!}
            </div>
            @endif
        </li>




        <li>
            @php
            $farmlife_mainte = \App\Mainte::ofType(\App\Mainte::FARM_LIFE)->first();
            @endphp
            @if (!isset($farmlife_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::FARM_LIFE]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', '農場生活', {'nonInteraction': 1});" target="_blank">
                <p class="sp_programs__list__ttl">農場生活</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/farm_life.png', '農場生活') }}
                </div>

                <div class="sp_programs__list__txt">
                    フルーツを育てる農場シミュレーションゲーム。1週間の間（月曜日00:00～日曜日23:59）に収穫したフルーツの「収穫ポイント」によってポイントが獲得できます。ランキング上位で高ポイントに！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">農場生活</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/farm_life.png', '農場生活') }}
            </div>

            <div class="sp_programs__list__txt">
                {!! nl2br(e($farmlife_mainte->message)) !!}
            </div>
            @endif
        </li>


        <li>
            @php
            $fruful_mainte = \App\Mainte::ofType(\App\Mainte::FRUFUL)->first();
            @endphp
            @if (!isset($fruful_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::FRUFUL]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', 'ふるふるサファリ', {'nonInteraction': 1});" target="_blank">
                <p class="sp_programs__list__ttl">ふるふるサファリ</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/fruful_safari.png', 'ふるふるサファリ') }}
                </div>

                <div class="sp_programs__list__txt">
                    木から落ちてくるポノスケとシマウマをクリックして、得点を貯めましょう！バッジを5コあつめると、1ポイントに自動交換されます。ふるふるサファリの世界へようこそ！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">ふるふるサファリ</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/fruful_safari.png', 'ふるふるサファリ') }}
            </div>

            <div class="sp_programs__list__txt">
                {!! nl2br(e($sansan_mainte->message)) !!}
            </div>
            @endif
        </li>
        <li>
            @php
            $easy_game_box_quiz_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_QUIZ)->first();
            @endphp
            @if (!isset($easy_game_box_quiz_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_QUIZ]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', 'まいにちクイズボックス', {'nonInteraction': 1});"
                target="_blank">
                <p class="sp_programs__list__ttl">まいにちクイズボックス</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/gmo_easy_game_box_quiz_banner.png', 'まいにちクイズボックス') }}
                </div>
                <div class="sp_programs__list__txt">
                    毎日3回開催されるクイズ大会に参加してスタンプを集めよう！スタンプはクイズに正解すると獲得でき12個集めると最大2,000ポイントが当たる抽選に参加できるよ！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">まいにちクイズボックス</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_quiz_banner.png', 'まいにちクイズボックス') }}
            </div>
            <div class="sp_programs__list__txt">
                {!! nl2br(e($easy_game_box_quiz_mainte->message)) !!}
            </div>
            @endif
        </li>
        <li>
            @php
            $easy_game_box_game_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_GAME)->first();
            @endphp
            @if (!isset($easy_game_box_game_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_GAME]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', 'かんたんゲームボックス', {'nonInteraction': 1});"
                target="_blank">
                <p class="sp_programs__list__ttl">かんたんゲームボックス</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/gmo_easy_game_box_game_banner.png', 'かんたんゲームボックス') }}
                </div>
                <div class="sp_programs__list__txt">
                    100種類以上のミニゲームをプレイしてみよう！ゲームを遊んだ結果に応じて「抽選券」を獲得し、100枚貯めると最大1,000ポイントが当たる抽選に参加できるよ！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">かんたんゲームボックス</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_game_banner.png', 'かんたんゲームボックス') }}
            </div>
            <div class="sp_programs__list__txt">
                {!! nl2br(e($easy_game_box_game_mainte->message)) !!}
            </div>
            @endif
        </li>
        <li>
            @php
            $easy_game_box_slot_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_SLOT)->first();
            @endphp
            @if (!isset($easy_game_box_slot_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_SLOT]) }}"
                onmousedown="ga('send', 'event', '所持メダルが3枚未満になるまで', 'click', '運だめし　スロットボックス', {'nonInteraction': 1});"
                target="_blank">
                <p class="sp_programs__list__ttl">運だめし　スロットボックス</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/gmo_easy_game_box_slot_banner.png', '運だめし　スロットボックス') }}
                </div>
                <div class="sp_programs__list__txt">
                    メダルを使ってスロットをまわそう！役がそろうとメダルがもらえます！メダルを300枚集めると最大2,000ポイントが当たる抽選に参加できるよ！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">運だめし　スロットボックス</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_slot_banner.png', '運だめし　スロットボックス') }}
            </div>
            <div class="sp_programs__list__txt">
                {!! nl2br(e($easy_game_box_slot_mainte->message)) !!}
            </div>
            @endif
        </li>
        <li>
            @php
            $easy_game_box_spot_mainte = \App\Mainte::ofType(\App\Mainte::EASY_GAME_BOX_SPOT)->first();
            @endphp
            @if (!isset($easy_game_box_spot_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::EASY_GAME_BOX_SPOT]) }}"
                onmousedown="ga('send', 'event', '最大9問', 'click', '間違い探しボックス', {'nonInteraction': 1});"
                target="_blank">
                <p class="sp_programs__list__ttl">間違い探しボックス</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/gmo_easy_game_box_spot_banner.png', '間違い探しボックス') }}
                </div>
                <div class="sp_programs__list__txt">
                    まちがい探しをクリアしてルーペを集めよう！
                    問題は最大9問遊ぶことができるよ！
                    ルーペを100個集めて最大2,000ポイント当たる抽選に参加しよう！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">間違い探しボックス</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/gmo_easy_game_box_spot_banner.png', '間違い探しボックス') }}
            </div>
            <div class="sp_programs__list__txt">
                {!! nl2br(e($easy_game_box_spot_mainte->message)) !!}
            </div>
            @endif
        </li>

        <li>
            @php
            $sansan_mainte = \App\Mainte::ofType(\App\Mainte::SANSAN_TYPE)->first();
            @endphp
            @if (!isset($sansan_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::SANSAN_TYPE]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', '魁！タイプ塾', {'nonInteraction': 1});" target="_blank">
                <p class="sp_programs__list__ttl">魁！タイプ塾</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/sansan_logo_sp.png', '魁！タイプ塾') }}
                </div>

                <div class="sp_programs__list__txt">
                    タイプ入力で無制限にポイントゲット！？デイリーランキング入賞でボーナスも貰える！
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">魁！タイプ塾</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/sansan_logo_sp.png', '魁！タイプ塾') }}
            </div>

            <div class="sp_programs__list__txt">
                {!! nl2br(e($sansan_mainte->message)) !!}
            </div>
            @endif
        </li>
        <li>
            @php
            $sansan_mainte = \App\Mainte::ofType(\App\Mainte::SANSAN_TYPE)->first();
            @endphp
            @if (!isset($sansan_mainte))
            <a href="{{ route('asps.click', ['asp' => \App\Asp::MEDAL_MALL_TYPE]) }}"
                onmousedown="ga('send', 'event', '毎日ゲット', 'click', 'メダルモール', {'nonInteraction': 1});" target="_blank">
                <p class="sp_programs__list__ttl">メダルモール</p>
                <div class="sp_programs__list__thumb">
                    {{ Tag::image('/images/medalmall_banner.gif', 'メダルモール') }}
                </div>

                <div class="sp_programs__list__txt">
                    アンケートやゲームに参加してメダルを集めよう！時間帯によって回数が決まっているから午前も午後も1日楽しめるよ。
                </div>
            </a>
            @else
            <p class="sp_programs__list__ttl">メダルモール</p>
            <div class="sp_programs__list__thumb">
                {{ Tag::image('/images/sansan_logo_sp.png', 'メダルモール') }}
            </div>

            <div class="sp_programs__list__txt">
                {!! nl2br(e($sansan_mainte->message)) !!}
            </div>
            @endif
        </li>
        
    </ul>
</div>

<!-- click -->
@if (!$click_list->isEmpty())
<div class="inner u-mt-7">

    <div class="sp_programs__ttl">
        <h2 class="contents__ttl">クリックでゲット</h2>
    </div>
    <div class="sp_programs__txt">
        1日1回リンク先のサイトを見てポイントゲット！最大4ポイントもらえる！
    </div>

    <ul class="sp_programs__click__list">
        @foreach($click_list as $sp_program)
        @php
        $click_url = route('sp_programs.click', ['sp_program' => $sp_program]);
        $sp_program_data = json_decode($sp_program->data);
        @endphp
        <li>
            <a href="{{ $click_url }}"
                onmousedown="ga('send', 'event', '{{ $click_url }}, 'click', 'luckyclick_{{ $sp_program->id }}', {'nonInteraction': 1});"
                target="_blank">
                <div class="sp_programs__click__list__pop">
                    @if ($sp_program->join_status > 0)
                    獲得済み
                    @else
                    毎日獲得
                    @endif
                </div>
                <div class="sp_programs__click__list__thumb">
                    {{ Tag::image($sp_program_data->img_url, $sp_program->title, ['border' => 0]) }}
                </div>
                <div class="sp_programs__click__list__point">
                    {{ number_format($sp_program->point) }}P
                </div>
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif
@endsection
