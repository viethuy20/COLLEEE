    @component('elements.base_searchbox')
    <?php
    $search_box_content_list = \App\Content::ofSpot(\App\Content::SPOT_SEARCH_BOX)
            ->limit(10)
            ->get();
    ?>

    <!-- TODO 必要か調整予定 -->
    <!-- <ul class="search__box__oftenuse">
        @foreach ($search_box_content_list as $content)
        <?php $content_data = json_decode($content->data); ?>
        <li>{!! Tag::link($content_data->url, $content->title) !!}</li>
        @endforeach
    </ul> -->
    @endcomponent
