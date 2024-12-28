@component('elements.base_searchbox')
<?php
$search_box_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_SEARCH_BOX)
        ->limit(10)
        ->get();
?>
<ul>
    @foreach ($search_box_content_list as $content)
    <?php $content_data = json_decode($content->data); ?>
    <li>{!! Tag::link($content_data->url, $content->title) !!}</li>
    @if (!$loop->last)
    &nbsp;|
    @endif
    @endforeach
</ul>
@endcomponent
