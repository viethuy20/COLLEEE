<?php $shopping_recipe_data = App\External\Recipe::getShoppingRecipeList(); ?>
@if (isset($shopping_recipe_data) && $shopping_recipe_data->result->status && !empty($shopping_recipe_data->items))
<div class="inner">
    <div class="top__ttl">
        <div class="top__ttl__l">
            <p class="top__ttl__jp">ショッピングに関するポイ活お得情報</p>
            <h2 class="top__ttl__en">RECIPE</h2>
        </div>
        <div class="top__ttl__r">
            {{ Tag::link('/article', 'もっと見る', ['class' => 'top__ttl__link'], false, false)}}
        </div>
    </div>
    <ul class="hot_campaign_box">
        <?php
        $recipe_list = $shopping_recipe_data->items;
        $recipe_id_list = [];
        foreach ($recipe_list as $recipe) {
            $recipe_id_list[] = $recipe->id;
        }
        $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
        ?>
        @foreach ($recipe_list as $i => $recipe)
        <li>
            <a href="{{ $recipe->guid }}">
                <div class="imgbox">{!! Tag::image($recipe->img, $recipe->title, ['class' => 'bnr_l']) !!}</div>
                <dl>
                    <dd class="heading">{{ $recipe->title }}</dd><!--案件名-->
                    <dd class="txt_campaign">{{ $recipe->update }}</dd>
                    <dd class="clipped"><span class="icon-clip"></span>&nbsp;クリップ：<span>{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span></dd><!--/clipped-->
                </dl>
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif