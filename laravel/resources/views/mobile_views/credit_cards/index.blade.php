@php
$base_css_type = 'credit_card';
@endphp
@extends('layouts.default')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    $('.txt_ureview').collapser({
        mode: 'chars',
        truncate: 70,
        showText: '続きを読む',
        hideText: '閉じる'
    });
});
//-->
</script>
@endsection

@section('layout.title', 'クレジットカード徹底比較｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'クレジットカード,比較,お得,ポイント,GMOポイ活')
@section('layout.description', 'クレジットカード徹底比較！便利で使いやすいお得なカードは？GMOポイ活経由でクレジットカードを発行すればポイントがもらえます！')

@section('layout.breadcrumbs')
    <section class="header__breadcrumb">
        <ol>
            <li>
                <a href="{{ route('website.index') }}">ホーム </a>
            </li>
            <li>クレジットカード徹底比較</li>
        </ol>
    </section>
@endsection

@section('layout.content')
@php
$brand_map = config('map.credit_card_brand');
$emoney_map = config('map.credit_card_emoney');
$insurance_map = config('map.credit_card_insurance');
$point_type_map = config('map.credit_card_point_type');
$apple_pay_map = config('map.credit_card_apple_pay');
@endphp

<!-- page title -->
<section class="inner">
    <div class="credit_cards_list__ttl">
        <h2 class="contents__ttl">
            {{ Tag::image("/images/credit_cards/credit_cards_ttl.png", "クレジットカード比較") }}
        </h2>
    </div>
</section>

<section class="inner">
    <h2 class="contents__ttl u-mt-20">検索条件</h2>
    <section class="contents">
        <div class="contents__box">
            {{ Tag::formOpen(['url' => route('credit_cards.list'), 'method' => 'GET', 'class' => 'credit_cards_list__search']) }}
            @csrf
            <div class="credit_cards_list__search__checkbox">
            {{ Tag::formHidden('searched', 1) }}
                <dl>
                    <dt>ブランド</dt>
                    @foreach ($brand_map as $key => $label)
                    <dd>
                        {{ Tag::formCheckbox('brand[]', $key, '', ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">{{ $label }}</span>
                    </dd>
                    @endforeach
                </dl>
                <dl>
                    <dt>電子マネー</dt>
                    @foreach ($emoney_map as $key => $label)
                    <dd><label>
                        {{ Tag::formCheckbox('emoney[]', $key, '', ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">{{ $label }}</span>
                    </label></dd>
                    @endforeach
                </dl>
                <dl>
                    <dt>付帯保険付き</dt>
                    @foreach ($insurance_map as $key => $label)
                    <dd><label>
                        {{ Tag::formCheckbox('insurance[]', $key, '', ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">{{ $label }}</span>
                    </label></dd>
                    @endforeach
                </dl>
                <dl>
                    <dt>こだわり条件</dt>
                    <dd><label>
                        {{ Tag::formCheckbox('annual_free', '1', '', ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">年会費永年無料</span>
                    </label></dd>
                    <dd><label>
                        {{ Tag::formCheckbox('etc', '1', '', ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">ETCカード付き</span>
                    </label></dd>
                    <dd><label>
                        {{ Tag::formCheckbox('apple_pay', '1', '', ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">ApplePay 対応</span>
                    </label></dd>
                </dl>
            </div>
            {{ Tag::formButton('この条件で検索する', ['type' => 'submit']) }}
            {{ Tag::formClose() }}
        </div>
    </section>
@php
// スタッフおすすめカード情報取得
$credit_card_recommend_list = App\Content::ofSpot(App\Content::SPOT_CREDIT_CARD_RECOMMEND)
    ->orderBy('id', 'asc')
    ->take(2)
    ->get();
@endphp
@if (!$credit_card_recommend_list->isEmpty())
    <h2 class="contents__ttl">スタッフのオススメ</h2>
    <ul>
        @foreach ($credit_card_recommend_list as $credit_card_recommend)
        @php
        $credit_card_recommend_data = json_decode($credit_card_recommend->data);

        $program = App\Program::find($credit_card_recommend_data->program_id);
        $affiriate = $program->affiriate;
        @endphp
        <li>
            <a class="credit_cards_list__staff" href="{{ route('programs.show', ['program'=> $program]) }}">
                <div class="credit_cards_list__staff__img">{{ Tag::image($affiriate->img_url, $program->title) }}</div>
                <div class="credit_cards_list__staff__txt">
                    <p class="credit_cards_list__staff__txt__main">{{$program->title}}</p>
                    <p class="credit_cards_list__staff__txt__sub">{{ $program->fee_condition }}</p>
                    <p class="credit_cards_list__staff__txt__point"><span>{{ $program->point->fee_label }}P</span></p>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
@endif

@php
$credit_card_recipe_data = App\External\Recipe::getRecipeListFromTag('クレジットカード');
@endphp
@if (isset($credit_card_recipe_data) && $credit_card_recipe_data->result->status && !empty($credit_card_recipe_data->items))
<section class="popularrecipe">
    <h2>クレジットカードに関するポイ活お得情報</h2>
    <div class="contentsbox_r"><ul>
        @php
        $recipe_list = array_splice($credit_card_recipe_data->items, 3);

        $recipe_id_list = [];
        foreach ($recipe_list as $recipe) {
            $recipe_id_list[] = $recipe->id;
        }
        $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
        @endphp
        @foreach ($recipe_list as $i => $recipe)
        <a href="{{ $recipe->guid }}"><li><dl class=" clearfix">
            <dt class="img_recipe">{{ Tag::image($recipe->img, $recipe->title) }}</dt>
            <dd class="heading">{{ $recipe->title }}</dd>
            <dd class="date">{{ $recipe->update }}</dd>
            <dd class="clipped">
                <span class="icon-clip"></span>&nbsp;クリップ：
                <span class="clipCount-242">{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span>
            </dd>
        </dl></li></a>
        @endforeach
    </ul></div><!--/contentsbox_r-->
</section><!--/popularrecipe-->
@endif

@php
// クレジットカード口コミ取得
$review_list = App\Review::ofEnableLabel([58])
    ->ofSort(0)
    ->take(3)
    ->get();
@endphp
@if (!$review_list->isEmpty())
@include('elements.review_list', ['review_list' => $review_list])
@endif
@php
    $links_for_category = [
                  ['link_1' => '/programs/list?ll%5B0%5D=125', 'text_link_1' => 'クレジットカードの広告一覧を見る'],
                  ['link_1' => '/article/category/creditcard/', 'text_link_1' => 'クレジットカードの新着お得情報を見る']
                ];
@endphp
<div class="programs_list__list">
    <h2 class="contents__ttl u-mt-40">関連コンテンツ</h2>
    <div class="article__cat" id="article__cat">
        <ul>
            @foreach ($links_for_category as $link)
                <li><a href="{{ $link['link_1'] }}">{{$link['text_link_1']}}</a></li>
            @endforeach
        </ul>
    </div>
</div>
</section>
@endsection
