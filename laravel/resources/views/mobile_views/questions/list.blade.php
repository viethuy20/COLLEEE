@php
$base_css_type = 'question';
@endphp
@extends('layouts.default')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    $('.questions:not(.questions:first-of-type)').css('display','none');
    $('.daily__form__label_not_login input[type=radio]').attr('disabled', true);
});
//-->
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
            {{ Tag::link(route('questions.index'), 'アンケート') }}
        </li>
        <li>
            GMOポイ活アンケート
        </li>
    </ol>
</section>
@endsection

@section('layout.title', 'アンケート | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'アンケート,ポイント,無料,簡単,毎日')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪')
@section('og_type', 'website')

@section('layout.content')
<div class="inner u-mt-20">
    <div class="programs_list__ttl">
        <h1 class="contents__ttl">
            <img src="{{ asset('/images/questions/questions_ttl.png')}}" alt="ポイントが貯まる！ GMOポイ活アンケート">
        </h1>
    </div>
</div>

<div class="inner">
    <div class="contents__box">
        <h2 class="contents__ttl orange">いま聞きたい！GMOポイ活アンケート</h2>
        @php
            // GMOポイ活アンケート取得
            $question = \App\Question::ofEnableAnswer()
                ->where('type', '=', 1)
                ->first();
        @endphp
        @if (!isset($question->id))
            <!--準備中の表示-->
            <div class="daily__que">
                <div class="daily__que__img"><img src="{{ asset('/images/img_preparation.svg')}}" alt="準備中"></div>
                <div class="daily__que__txt"><span>GMOポイ活アンケートは準備中です。<br />開催までしばらくお待ちください。</span></div>
            </div>
        @else
            @php
                $answer_map = $question->answer_map;
                $question_url = route('questions.show', ['question' => $question]);
            @endphp
            <div class="daily__que">
                <div class="daily__que__img"><img src="{{ asset('/images/questions/ico_question.svg')}}" alt="Q"></div>
                <div class="daily__que__txt">{{ $question->title }}</div>
            </div>
            @if ($question->answer_total > 0)
                <p class="u-text-right u-mt-20">&nbsp;{{ Tag::link($question_url.'#tocomments', 'みんなの回答・コメントを見る', ['class' => 'textlink__arrow']) }}</p>
            @endif
            @if (Auth::check())
                @php
                    $user_answer = \App\UserAnswer::where('question_id', '=', $question->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
                @endphp
                    {{ Tag::formOpen(['url' => route('questions.answer'),'class' => 'daily__form']) }}
                    @csrf
                    {{ Tag::formHidden('question_id', $question->id) }}
                    <div class="daily__form__label">
                        @foreach($answer_map as $answer_id => $label)
                        <label class="which">{{ Tag::formRadio('answer_id', $answer_id, (isset($user_answer->answer_id) && $user_answer->answer_id == $answer_id)) }}{{ $label }}</label>
                        @endforeach
                    </div>
                    @if (isset($user_answer->id))
                        <!--回答済みの場合-->

                        {{ Tag::formButton('回答済みです') }}
                    @else
                        <p class="daily__form__txt">回答で<span>1ポイント</span>GET！</p>
                        {{ Tag::formSubmit('上記内容で回答する', ['class' => 'getapoint']) }}
                    @endif
                {{ Tag::formClose() }}
            @else
                <!--非ログインの場合-->
                <div class="daily__form__label daily__form__label_not_login">
                    @foreach($answer_map as $answer_id => $label)
                        <label class="which">{{ Tag::formRadio('answer_id', $answer_id)}}{{$label}}</label>
                    @endforeach
                </div>
            @endif
        @endif
        <p class="u-mt-20"><a class="textlink__arrow" href="#oldissue">過去のアンケート結果・バックナンバーはこちら</a></p>
    </div>
</div>

<!-- GMOポイ活アンケートの結果 -->
@php
    $now = \Carbon\Carbon::now();
    // アーカイブ取得
    $old_question_builder = \App\Question::ofEnable()
        ->where('type', '=', 1)
        ->where('start_at', '<=', $now)
        ->orderBy('start_at', 'desc')
        ->take(5);

    $old_question_list = $old_question_builder->get();
@endphp
@if (!$old_question_list->isEmpty())
    <div class="inner u-mt-20">
        <div class="contents__box">
            <h2 class="contents__ttl orange">GMOポイ活アンケートの結果</h2><!--本日から遡って5日分表示-->
            <div class="result__list">
                <ul>
                    @foreach ($old_question_list as $old_question)
                        <li>
                            <a href="{{ route('questions.show', ['question' => $old_question]) }}">
                                <p class="data">{{ $old_question->start_at->format('Y-m-d') }}</p>
                                <p class="ttl">{{ $old_question->title }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- GMOポイ活アンケートバックナンバー -->
@php
    // バックナンバー作成
    $backnumber_end_at = Carbon\Carbon::yesterday()->endOfDay()->startOfMonth();
    $backnumber_start_at = Carbon\Carbon::parse('2017-12-01 00:00:00');
    $backnumber_map = [];
    while (true) {
        $data = $backnumber_map[$backnumber_end_at->year] ?? [];
        $data[] = $backnumber_end_at->month;
        $backnumber_map[$backnumber_end_at->year] = $data;
        $backnumber_end_at->subMonths(1);
        if ($backnumber_start_at->gt($backnumber_end_at)) {
            break;
        }
    }
@endphp

<div class="inner u-mt-20" id="oldissue">
    <div class="contents__box">
        <h2 class="contents__ttl orange">GMOポイ活アンケートバックナンバー</h2>
        <div class="backnumber">
            <dl>
                @foreach ($backnumber_map as $year => $month_list)
                    <dt>{{ $year }}年</dt>
                    <dd>
                        <ul>
                            @foreach ($month_list as $month)
                            <li>{{ Tag::link(route('questions.monthly', ['target' => sprintf("%04d%02d", $year, $month)]), $month.'月') }}</li>
                            @endforeach
                        </ul>
                    </dd>
                </dl>
                <dl>
                @endforeach
                </dl>
            </dl>
        </div>
    </div>
</div>

@include('elements.pop_recipe_list')
@endsection
