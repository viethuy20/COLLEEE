<?php $pop_recipe_data = App\External\Recipe::getPopRecipeList(); ?>
@if (isset($pop_recipe_data) && $pop_recipe_data->result->status  && !empty($pop_recipe_data->items))

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

<div class="inner u-mt-20">
    <h2 class="contents__ttl">人気のポイ活お得情報</h2>
    <div class="recipe__list">
        <ul>
            <?php
                $data_list = [['class' => 'eins', 'label' => '1位', 'img' => 'images/ico_ranking1.svg'],
                    ['class' => 'zwei', 'label' => '2位', 'img' => 'images/ico_ranking2.svg'],
                    ['class' => 'drei', 'label' => '3位', 'img' => 'images/ico_ranking3.svg'],
                    ['class' => 'vier', 'label' => '4位', 'img' => 'images/ico_ranking4.svg']];
                $recipe_list = $pop_recipe_data->items;
                $recipe_id_list = [];
                foreach ($recipe_list as $recipe) {
                    $recipe_id_list[] = $recipe->id;
                }
                $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
            ?>
            @foreach ($recipe_list as $i => $recipe)
                <?php
                    $class_name = null;
                    $img_name = null;
                    $label_name = null;
                    if (isset($data_list[$i])) {
                        $class_name = $data_list[$i]['class'];
                        $img_name = $data_list[$i]['img'];
                        $label_name = $data_list[$i]['label'];
                    }
                ?>
                <li class="{{ $class_name }}">
                    <a href="{{ $recipe->guid }}">
                        <div class="recipe__thumb" style="background-image: url('{{ $recipe->img }}')"></div>
                        <div class="recipe__content">
                            <p class="recipe__ttl">{{ $recipe->title }}</p>
                            <p class="recipe__date">{{ $recipe->update }}</p>
                            <p class="recipe__clip">クリップ：<span>{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span></p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif