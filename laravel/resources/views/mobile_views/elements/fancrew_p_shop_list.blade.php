<?php
$fancrew_content_list = \App\Content::ofSpot(\App\Content::SPOT_FANCREW_P)
        ->limit(3)
        ->get();
?>
@if (!$fancrew_content_list->isEmpty())
<section class="pickup_monitor">
    <h2>Pickupモニター</h2>
    <ul>
        @foreach ($fancrew_content_list as $content)
        <?php $content_data = json_decode($content->data); ?>
        <li>
            <a href="{{ $content_data->url }}">
            <div class="eachbnr">{!! Tag::image($content_data->img_url, $content->title) !!}</div>
            <p class="heading fr">{{ $content->title }}</p>
            <p class="txt_picup">{!! nl2br(e($content_data->description)) !!}</p>
            </a>
        </li>
        @endforeach
    </ul>
</section><!--/pickup_monitor-->
@endif