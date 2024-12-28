@extends('layouts.question')
@if (!$question->start_at->isToday())
@section('layout.use_question_daily', true)
@endif

@php
$sex_map = config('map.sex');
$generation_map = config('map.generation');
$answer_map = $question->answer_map;
$now = Carbon\Carbon::now();
@endphp

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

<section class="contents">
    <section class="daily_detail"><!--ここの仕様は右カラムのGMOポイ活アンケートと同じ-->
        <div class="contents__box">
            <h2 class="contents__ttl orange">{{ $question->start_at->format('Y年m月d日') }} GMOポイ活アンケート</h2>
            @if ($now->lt($question->start_at) || $now->gt($question->stop_at))
            <div class="questions__que">
                <div class="questions__que__img"><img src="{{ asset('/images/questions/ico_question.svg')}}" alt="Q"></div>
                <div class="questions__que__txt">{{ $question->title }}</div>
            </div>
            @else
            {{ Tag::formOpen(['url' => route('questions.answer'), 'class' => 'daily__form']) }}
            @csrf
            {{ Tag::formHidden('question_id', $question->id) }}
            <div>
                <div class="questions__que">
                    <div class="questions__que__img"><img src="{{ asset('/images/questions/ico_question.svg')}}" alt="Q"></div>
                    <div class="questions__que__txt">{{ $question->title }}</div>
                </div>
                <div class="daily__form__label">
                    @foreach($answer_map as $answer_id => $label)
                    <label class="which">{{ Tag::formRadio('answer_id', $answer_id, (isset($user_answer->answer_id) && $user_answer->answer_id == $answer_id)) }}{{ $label }}</label>
                    @endforeach
                </div>
                @if (Auth::check())
                @if (isset($user_answer->id))
                <!--回答済みの場合-->
                <p class="done">回答済みです</p>
                @else
                <p class="daily__form__txt">回答で<span>1ポイント</span>GET!</p>
                <button id="" type="submit">上記内容で回答する</button>
                @endif
                @else
                <div class="toregist">
                    <p class="txt_regi">ポイントの獲得及びGMOポイ活アンケートのコメント投稿には、GMOポイ活への会員登録が必要です。</p>
                    {{ Tag::link(route('entries.index'), Tag::image('/images/btn_newregist.svg', '簡単1分無料会員登録はコチラ！'), null, null, false) }}
                </div>
                @endif
            </div>
            {{ Tag::formClose() }}
            @endif
        </div><!--/contentsbox-->
    </section><!--/daily_detail-->

    <section class="daily_post" id="toanswer_message">
        @if (isset($user_answer->id) && $user_answer->status == 2 && $question->stop_at->isToday())
        <!--当日のアンケートであり且つ回答済でコメント未投稿の場合に表示-->
        <h2 class="contents__ttl mt-20">回答についてのコメント</h2>
        <div class="contents__box">
            <p class="questions__form__lead">
                アンケートへの回答ありがとうございました！<br />
                テーマに関するエピソード等ございましたら、コメント欄に投稿下さい！
            </p>
                {{ Tag::formOpen(['url' => route('questions.answer_message'), 'class' => 'questions__form']) }}
                @csrf
                {{ Tag::formHidden('question_id', $question->id) }}
                <div class="questions__form__inner">
                    <div class="questions__form__inner__l">
                        {{ Tag::formTextarea('message', '', ['placeholder' => 'コメント', 'rows' => '']) }}
                    </div>
                    <div class="questions__form__inner__r">
                        <dl>
                            <dt>性別：</dt>
                            <dd>
                            <div class="select_radio mb_20">
                                @foreach($sex_map as $key => $label)
                                <label><input type="checkbox" name="sex" value="{{ $key }}">{{ $label }}</label>
                                @endforeach
                                <label><input type="checkbox" name="sex" value="0">その他</label>
                            </div>
                              
                            </dd>
                        </dl>
                        <dl>
			    			<dt>年代：</dt>
			    			<dd>
                                <div class="questions__form__select">{{ Tag::formSelect('generation', [0 => '年代を選択'] + $generation_map, null) }}</div>
                            </dd>
			    		</dl>
                    </div>
                </div>
                <p class="questions__form__caution text--15">
                    <img src="{{ asset('/images/questions/ico_caution.svg')}}" alt="0">コメント投稿についての注意事項
                </p>
			    <p class="text--15">ご記入いただいたコメントの内容に宣伝・営利目的の投稿、誹謗中傷・公序良俗に反する内容（と受け取れる内容も含む）の投稿、その他管理者が不適切と判断した内容の投稿に関しては、予告無く削除する事があります。あらかじめご了承ください。</p>
                <button id="" type="submit">上記内容でコメントする</button>
            {{ Tag::formClose() }}
        </div><!--/contentsbox-->
        @endif
    </section><!--/daily_post-->

    @if ($question->answer_total > 0)
    <section class="contents__box">
        <h2 class="contents__ttl orange">アンケート結果</h2>
        <div class="contentsbox"><div id="chart">
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
                <div id="chart">
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
        </div></div><!--/chart--><!--/contentsbox-->
    </section><!--/dailyresult-->
    @endif

    <section class="dailycomment" id="tocomments">
        @include('elements.question_message_list', ['question' => $question])
    </section><!--/dailycomment-->
</section><!--/contents-->
@endsection
