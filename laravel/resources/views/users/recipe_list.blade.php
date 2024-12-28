@extends('layouts.recipe')

@section('layout.title', 'クリップした記事一覧｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活では、ポイ活に役立つお得なキャンペーン・セール情報を日々更新しています。')

@section('layout.content')

<section class="contents">
    <h2 class="contents__ttl">クリップしたポイ活お得情報</h2>
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
                            <a class="clip__thumb" href="{{ $recipe->guid }}">
                                {{ Tag::image($recipe->img, $recipe->title) }}
                            </a>
                        </div>
                    </div>
                    <div class="recipe__flex__c">
                        <p class="recipe__ttl">{{ Tag::link($recipe->guid, $recipe->title, [], null, false) }}</p>
                        <p class="recipe__txt">{{ $recipe->catchText }}</p>
                    </div>
                    <div class="recipe__flex__r">
                        <div class="recipe__ico">
                        {{ Tag::link(route('users.remove_recipe', ['recipe' => $recipe->id]), Tag::image('/images//users/ico_dustbox.svg', '削除'), ['class' => 'save_scroll'], null, false) }}
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) { return route('users.recipe_list', ['page' => $page]); }]) !!}
    @endif
    <div class="recipe__btn">
        {{ Tag::link('/article/', 'ポイ活お得情報トップへ') }}
    </div>
</section>
@endsection
