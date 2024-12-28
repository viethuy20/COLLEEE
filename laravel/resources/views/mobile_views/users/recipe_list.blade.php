@php
$base_css_type = 'recipe';
@endphp
@extends('layouts.default')

@section('layout.title', 'クリップした記事一覧｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活では、ポイ活に役立つお得なキャンペーン・セール情報を日々更新しています。')

@section('layout.content')
<section class="inner">
    <h2 class="contents__ttl u-mt-small">クリップしたポイ活お得情報</h2>
    @if ($paginator->total() < 1)
    <p class="u-font-bold u-text-ac u-mt-20 text--18 red">クリップに追加したポイ活お得情報はありません。</p>
    @else
    <div class="recipe__list">
        <ul>
            @foreach($paginator as $recipe)
            <li>
                <div class="recipe__flex">
                    <div class="recipe__flex__l">
                        <div class="recipe__thumb">
                            <a href="{{ $recipe->guid }}">
                                {{ Tag::image($recipe->img, $recipe->title) }}
                            </a>
                        </div>
                    </div>
                    <div class="recipe__flex__r">
                        <div class="recipe__ttl">
                        {{ Tag::link($recipe->guid, $recipe->title, [], null, false) }}
                        </div>
                    </div>
                </div>
                <p class="recipe__txt">{{ $recipe->catchText }}</p>
                <div class="recipe__delete">
                    <a class="recipe__delete" href="{{ route('users.remove_recipe', ['recipe' => $recipe->id]) }}">
                        <i>{{ Tag::image('/images/users/ico_dustbox.svg', '削除') }}</i>
                        お気に入り削除
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) { return route('users.recipe_list', ['page' => $page]); }]) !!}
    @endif
    <div class="recipe__btn">
        {{ Tag::link('', 'ポイ活お得情報TOPへ', null, null, false) }}
    </div>
</section>
@endsection
