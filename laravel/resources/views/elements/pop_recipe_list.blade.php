<?php $pop_recipe_data = App\External\Recipe::getPopRecipeList(); ?>
@if (isset($pop_recipe_data) && $pop_recipe_data->result->status && !empty($pop_recipe_data->items))
<script type="text/javascript"><!--
$(function() {
    $('.reciperank li dd.heading').each(function() {
        var $target = $(this);
        var html = $target.html();
        var $clone = $target.clone();
        $clone.css({
            display: 'none',
            position : 'absolute',
            overflow : 'visible'
        }).width($target.width()).height('auto');
        $target.after($clone);
        while((html.length > 0) && ($clone.height() > $target.height())) {
            html = html.substr(0, html.length - 1);
            $clone.html(html + '...');
        }
        $target.html($clone.html());
        $clone.remove();
    });
});
//-->
</script>

<h2 class="contents__ttl">人気のポイ活お得情報</h2>
<div class="recipe__list">
    <ul>
        <?php
            $recipe_list = $pop_recipe_data->items;
            $recipe_id_list = [];
            foreach ($recipe_list as $recipe) {
                $recipe_id_list[] = $recipe->id;
            }
            $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
        ?>
        <!-- 共通部品なのにSPは4個でPCは3個表示の為強制で3個表示したら終了する -->
        @foreach ($recipe_list as $i => $recipe)
            <li>
                <a href="{{ $recipe->guid }}">
                    <div class="recipe__thumb"><img src="{{ $recipe->img }}"></div>
                    <p class="recipe__ttl">{{ $recipe->title }}</p>
                    <p class="recipe__date">{{ $recipe->update }}</p>
                    <p class="recipe__clip">クリップ：<span>{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span></p>
                </a>
            </li>
            @if ($i == 2)
                @break
            @endif
        @endforeach
    </ul>
</div>
@endif