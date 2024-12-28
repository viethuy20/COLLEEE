<h1>ショップカテゴリ検索</h1>
<?php
$shop_category_map = config('map.shop_category');
?>
@foreach($shop_category_map as $shop_category_id => $label)
{!! Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['shop_category_id' => $shop_category_id]), $label) !!}
@endforeach