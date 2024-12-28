<section class="search_box">
    <div class="contentsbox_r">
        @component('elements.base_searchbox')
        <ul>
            <?php
            $search_box_content_list = \App\Content::ofSpot(\App\Content::SPOT_SHOP_SEARCH_BOX)
                    ->limit(10)
                    ->get();
            ?>
            @foreach ($search_box_content_list as $content)
            <?php $content_data = json_decode($content->data); ?>
            <li>{!! Tag::link($content_data->url, $content->title) !!}&nbsp;|</li>
            @endforeach
        </ul>
        @endcomponent
    </div><!--/contentsbox_r-->
</section>