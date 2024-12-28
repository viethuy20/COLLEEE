@php
$base_css_type = 'question_detail';

$now = \Carbon\Carbon::now();
$old_question_list = \App\Question::ofEnable()
    ->where('type', '=', 1)
    ->where('start_at', '<=', $now)
    ->orderBy('start_at', 'desc')
    ->take(5)
    ->get();

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
@extends('layouts.plane')

@section('layout.sidebar')

@hasSection ('layout.use_question_daily')
<section class="daily">
    <h2 class="contents__ttl">いま聞きたい！<br />GMOポイ活アンケート</h2>
    <div class="contentsbox_r">
        @php
        // デイリーアンケート取得
        $question_daily = \App\Question::ofEnableAnswer()
            ->where('type', '=', 1)
            ->first();
        @endphp
        @if (!isset($question_daily->id))
        <!--すでに締め切られた場合-->
        <div class="preparation">
            <div class="preparation__img">
                <img src="{{ asset('/images/img_preparation.svg')}}" alt="準備中">
            </div>
            <div class="preparation__txt">
                <span>GMOポイ活アンケートは<br />準備中です。<br />開催までしばらくお待ちください。</span>
            </div>
        </div>
        @else
        @php
        $answer_daily_list = json_decode($question_daily->answers);
        $answer_daily_map = $question_daily->answer_map;
        $question_url = route('questions.show', ['question' => $question_daily]);
        @endphp
        <div class="orange"> {{ $question_daily->start_at->format('Y-m-d') }}</div>
        <div class="daily__que__sb">
			<div class="daily__que__sb__img"><img src="{{ asset('/images/questions/ico_question.svg')}}" alt="Q"></div>
			<div class="daily__que__sb__txt">{{ $question_daily->title }}</div>
		</div>
        @if ($question_daily->answer_total > 0)
        <p class="u-text-right">{{ Tag::link($question_url.'#tocomments', 'みんなの回答・コメントを見る', ['class' => 'textlink__sb__arrow']) }}</p>
        @endif

        @if (Auth::check())
        @php
        $user_answer_daily = \App\UserAnswer::where('question_id', '=', $question_daily->id)
            ->where('user_id', '=', Auth::user()->id)
            ->first();
        @endphp
            {{ Tag::formOpen(['url' => route('questions.answer'), 'class' => 'daily__form__sb']) }}
            @csrf
            {{ Tag::formHidden('question_id', $question_daily->id) }}
            <div class="daily__form__label__sb">
                @foreach($answer_daily_map as $answer_id => $label)
                <label class="which">{{ Tag::formRadio('answer_id', $answer_id, (isset($user_answer_daily->answer_id) && $user_answer_daily->answer_id == $answer_id)) }}{{ $label }}</label>
                @endforeach
            </div>
            @if (isset($user_answer_daily->id))
            <!--回答済みの場合-->
            <p class="done">回答済みです</p>
            @else
            <p class="daily__form__txt">回答で<span>1ポイント</span>GET!</p>
            <button id="" type="submit">上記内容で回答する</button>
            @endif
        {{ Tag::formClose() }}
        @else
        <!--非ログインの場合-->
        <div class="daily__form__sb">
            <div class="daily__form__label__sb">
                @foreach($answer_daily_map as $answer_id => $label)
                <label class="which">{{ Tag::formRadio('answer_id', $answer_id, false) }}{{ $label }}</label>
                @endforeach
            </div>
        </div>
        @endif
        @endif
    </div><!--/contentsbox_r-->
</section><!--/daily-->
@endif

@if (!$old_question_list->isEmpty())
<section class="recent_results">
    <h2 class="contents__ttl mt-20">GMOポイ活アンケートの結果</h2><!--当日から遡って5日分表示-->
    <div>
        <ul class="sidebar__list">
            @foreach ($old_question_list as $old_question)
            <li class="eachone">
                <a href="{{ route('questions.show', ['question' => $old_question]) }}">
                    <dl class="clearfix">
                        <dt class="data">{{ $old_question->start_at->format('Y-m-d') }}</dt>
                        <dd class="ttl">{{ $old_question->title }}</dd>
                    </dl>
                </a>
            </li><!--/eachone-->
            @endforeach
        </ul>
    </div><!--/contentsbox_r-->
</section><!--/recent_results-->

@endif

<section class="oldissue">
    <h2 class="contents__ttl mt-20">GMOポイ活アンケート<br />バックナンバー</h2>
    <div class="wrap__daily backnumber-custom">
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
    </div>
</section><!--/oldissue-->
@endsection