@php
$base_css_type = 'question_detail';
@endphp
@extends('layouts.default')

@section('layout.title', $target_month->format('Y年n月').'のGMOポイ活アンケート一覧 | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'アンケート,ポイント,無料,簡単,毎日')
@section('layout.description', 'いま聞きたい！GMOポイ活アンケートは1日1回、回答すると1ポイントもらえます。是非回答してみてください。')
@section('og_type', 'website')

@section('layout.content')
<section class="inner">

    <section class="contents__box"><!--アンケートは全件表示-->
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
        <div class="questions__btn">{{ Tag::link(route('questions.index'), 'アンケートTOPへ戻る') }}</div>
    </section><!--list_questionnaire-->
</section>

@include('elements.pop_recipe_list')

@endsection
