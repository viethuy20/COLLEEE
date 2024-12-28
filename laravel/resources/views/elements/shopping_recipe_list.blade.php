<?php $shopping_recipe_data = App\External\Recipe::getShoppingRecipeList(); ?>
@if (isset($shopping_recipe_data) && $shopping_recipe_data->result->status && !empty($shopping_recipe_data->items))
<div class="top__ttl">
    <div class="top__ttl__l">
        <p class="top__ttl__jp">ショッピングに関するポイ活お得情報</p>
        <h2 class="top__ttl__en">RECIPE</h2>
    </div>
    <div class="top__ttl__r">
        {{ Tag::link('/article', 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
    </div>
</div>
<ul class="top__list__3col">
    @php
        // レシピ情報
        $recipe_list = $shopping_recipe_data->items;
        $recipe_id_list = [];
        foreach ($recipe_list as $recipe) {
            $recipe_id_list[] = $recipe->id;
        }
        $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
    @endphp
    @foreach ($recipe_list as $i => $recipe)
    @php
    //3件まで表示
    if($i >= 3){ break; } 
    @endphp
    <li>
        <a href="{{ $recipe->guid }}">
            <div class="top__list__3col__thumb">{!! Tag::image($recipe->img, $recipe->title) !!}</div>
            <p class="top__list__3col__ttl3">{{ $recipe->title }}</p>
            <p class="top__list__3col__txt1">{{ $recipe->update }}</p>
            <p class="top__list__3col__clip"><span class="icon-clip orange"></span>&nbsp;クリップ：<span class="orange ">{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span></p>
        </a>
    </li>
    @endforeach
</ul>
<hr class="bd_orange u-mt-20">
@endif