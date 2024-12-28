<?php $new_recipe_data = App\External\Recipe::getNewRecipeList(); ?>
@if (isset($new_recipe_data) && $new_recipe_data->result->status && !empty($new_recipe_data->items))
<section class="newrecipe">
    <h2>新着ポイ活お得情報</h2>
    <div class="contentsbox">
        <ul>
            <?php
            $class_list = ['eins', 'zwei', 'drei'];
            $recipe_list = $new_recipe_data->items;
            $recipe_id_list = [];
            foreach ($recipe_list as $recipe) {
                $recipe_id_list[] = $recipe->id;
            }
            $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
            ?>
            @foreach ($recipe_list as $i => $recipe)
            <?php $class_name = ($class_list[$i] ?? 'vier').' clearfix'; ?>
            <li class="{{ $class_name }}"><a href="{{ $recipe->guid }}"><dl class="clearfix">
                <dt>{!! Tag::image($recipe->img, $recipe->title) !!}</dt>
                <dd class="heading">{{ $recipe->title }}</dt>
                <dd class="txt_blog">{{ $recipe->catchText }}</dd>
                <dd class="date">{{ $recipe->update }}<!--更新日--></dd>
                <dd class="helpful"><span class="icon-clip"></span>&nbsp;クリップ：<span>{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span></dd>
            </dl></a></li>
            @endforeach
        </ul>
<p class="btn_more btn_w150">{!! Tag::link('/article/?s=', 'もっと見る') !!}</p><!--ポイ活お得情報の「新着」で検索した結果ページに飛ぶ-->
    </div><!--/contentsbox-->
</section><!--/newrecipe-->
@endif
