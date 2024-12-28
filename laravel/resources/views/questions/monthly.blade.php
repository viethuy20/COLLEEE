@extends('layouts.question')
@section('layout.use_question_daily', true)

@section('layout.title', $target_month->format('Y年n月').'のGMOポイ活アンケート一覧 | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'アンケート,ポイント,無料,簡単,毎日')
@section('layout.description', 'いま聞きたい！GMOポイ活アンケートは1日1回、回答すると1ポイントもらえます。是非回答してみてください。')

@section('layout.content')

<section class="contents">
    <div class="contents__box">
        <h1 class="contents__ttl orange">{{ $target_month->format('Y年n月') }}のGMOポイ活アンケート一覧</h1>
        <div class="result__list">
            <ul>
            @foreach ($question_list as $question)
                <li>
                    <a href="{{ route('questions.show', ['question' => $question]) }}">
                        <p class="data">{{ $question->start_at->format('Y-m-d') }}</p>
                        <p class="ttl">{{ $question->title }}</p>
                        <p class="responses">回答数：{{ number_format($question->answer_total) }}人</p>
                    </a>
                </li>
            @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-20">
        @include('elements.pop_recipe_list')
    </div>
</section><!--/contents-->



@endsection
