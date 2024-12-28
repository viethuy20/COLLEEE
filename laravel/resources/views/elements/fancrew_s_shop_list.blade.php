<?php
$fancrew_content_list = \App\Content::ofSpot(\App\Content::SPOT_FANCREW_S)
        ->limit(4)
        ->get();
?>
@if (!$fancrew_content_list->isEmpty())
<section class="classic">
    <h2>定番モニター</h2>
    <ul>
        @foreach ($fancrew_content_list as $content)
        <?php $content_data = json_decode($content->data); ?>
        <li>{!! Tag::link($content_data->url, Tag::image($content_data->img_url, $content->title), null, null, false) !!}</li>
        @endforeach
    </ul>
</section><!--/classic-->
@endif