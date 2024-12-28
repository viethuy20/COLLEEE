@php
$base_css_type = 'question_detail';
@endphp
@extends('layouts.default')

@section('layout.title', $question->start_at->format('Y年m月d日').'GMOポイ活アンケート | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'アンケート,ポイント,無料,簡単,毎日')
@section('layout.description', 'いま聞きたい！GMOポイ活アンケートは1日1回、回答すると1ポイントもらえます。是非回答してみてください。')
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
            {{ Tag::link(route('questions.my_list'), 'GMOポイ活アンケート') }}
        </li>
        <li>
            {{ $question->start_at->format('Y年m月d日') }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

@php
$sex_map = config('map.sex');
$generation_map = config('map.generation');
$answer_map = $question->answer_map;
$now = Carbon\Carbon::now();
@endphp
<section class="inner">

    <div class="questions__content">
        <div class="questions__ttl">
            {{ $question->start_at->format('Y-m-d') }}GMOポイ活アンケート
        </div><!--/questionbox-->
        <div class="questions__que">
			<div class="questions__que__img">
				<img src="{{ asset('/images/questions/ico_question.svg')}}" alt="Q">
			</div>
			<div class="questions__que__txt">
				{{ $question->title }}
			</div>
		</div>

		@if ($question->answer_total > 0)
        <div class="questions__seeall">
				▶︎ <a href="#tocomments">みんなの回答・コメントを見る</a>
		</div>
		@endif

		@if ($now->lt($question->start_at) || $now->gt($question->stop_at))

        @elseif (Auth::check())
			{{ Tag::formOpen(['url' => route('questions.answer'), 'class' => 'questions__form']) }}
            @csrf
			{{ Tag::formHidden('question_id', $question->id) }}
                <div class="questions__form__label">
                    @foreach($answer_map as $answer_id => $label)
                    <label class="which">{{ Tag::formRadio('answer_id', $answer_id, (isset($user_answer->answer_id) && $user_answer->answer_id == $answer_id)) }}{{ $label }}</label>
                    @endforeach
                </div>
                @if (isset($user_answer->id))
                <!--回答済みの場合-->
                <p class="done">回答済みです</p>
                @else
                <p class="questions__form__txt">回答で<span>1ポイント</span>GET!</p>
                <button id="" type="submit">上記内容で回答する</button>
                @endif
            {{ Tag::formClose() }}
		@else
			<div class="toregist">
				<p class="text--14 u-mt-3 u-mb-3">ポイントの獲得及びGMOポイ活アンケートのコメント投稿には、GMOポイ活への会員登録が必要です。</p>
				{{ Tag::link(route('entries.index'), Tag::image('/images/btn_newregist.svg', '簡単1分無料会員登録はコチラ！'), null, null, false) }}
			</div>
		@endif
    </div><!--/contentsbox-->
</section><!--/daily-->

<section class="inner u-mt-7">
	@if (isset($user_answer->id) && $user_answer->status == 2 && $question->stop_at->isToday())
	<div class="questions__ttl">
		<h2 class="contents__ttl">回答についてのコメント</h2>
	</div>
	<div class="questions__content">
		<p class="questions__form__lead">アンケートへの回答ありがとうございました！<br>テーマに関するエピソード等ございましたら、<br>コメント欄に投稿ください！</p>
			{{ Tag::formOpen(['url' => route('questions.answer_message'), 'class' => 'questions__form']) }}
			@csrf
			{{ Tag::formHidden('question_id', $question->id) }}
			<div class="questions__form__inner">
				<div class="questions__form__inner__l">
				{{ Tag::formTextarea('message', '', ['placeholder' => 'コメント', 'rows' => '']) }}
				</div>
				<div class="questions__form__inner__r">
					<dl>
						<dd>
						<div class="questions__form__select">{{ Tag::formSelect('sex', ['' => '年代を選択'] + $sex_map + [0 => 'その他'], null) }}</div>
						</dd>
					</dl>
					<dl>
						<dd>
							<div class="questions__form__select">{{ Tag::formSelect('generation', [0 => '年代を選択'] + $generation_map, null) }}</div>
						</dd>
					</dl>
				</div>
			</div>
			<p class="questions__form__caution text--15"><img src="{{ asset('/images/questions/ico_caution.svg')}}">コメント投稿についての注意事項</p>
			<p class="text--15">ご記入いただいたコメントの内容に宣伝・営利目的の投稿、誹謗中傷・公序良俗に反する内容（と受け取れる内容も含む）の投稿、その他管理者が不適切と判断した内容の投稿に関しては、予告無く削除する事があります。あらかじめご了承ください。</p>
			<button id="" type="submit">上記内容でコメントする</button>
		{{ Tag::formClose() }}
	</div>
	@endif
</section>

@if ($question->answer_total > 0)
<section class="inner">
	<div class="questions__content">
		<div class="questions__ttl">
			アンケート結果
		</div>
		@if ($now->gt($question->stop_at))
		<p class="questions__result__data">集計日：{{ $question->stop_at->addDays(1)->format('Y-m-d') }}</p>
		@endif
		@php
		$answer_total = array_sum($result_map);
		// 百分率を求める
		$rate_map = [];
		foreach ($result_map as $answer_id => $value) {
			$rate_map[$answer_id] = floor($value * 100 / $answer_total);
		}

		// 誤差丸め
		$answer_max_key = array_search(max($result_map), $result_map);
		$rate_map[$answer_max_key] = $rate_map[$answer_max_key] + (100 - array_sum($rate_map))
		@endphp

		<div class="questions__result__chart__wrap">
			@foreach ($answer_map as $answer_id => $label)
			@php
			$rate = $rate_map[$answer_id] ?? 0;
			@endphp
			<div class="questions__result__chart__inner">
				<dl class="questions__result__chart__answer">
					<dt>{{ $label }}</dt>
					<dd>{{ $rate }}%</dd>
				</dl>
				<div class="questions__result__chart__bar">
					<div style=" width:{{ $rate }}%;"></div>
				</div>
			</div><!--/answer-->
			@endforeach
		</div>
	</div>
</section>
@endif

<section class="dailycomment" id="tocomments">
    @include('elements.question_message_list', ['question' => $question])
</section><!--/dailycomment-->

@include('elements.pop_recipe_list')

<div class="questions__btn">{{ Tag::link(route('questions.index'), 'アンケートTOPへ戻る') }}</div>
@endsection