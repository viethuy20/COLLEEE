<section class="category">
    <h2>カテゴリ</h2>
    <div class="carousel"><ul class="list_category">
        @php 
        $search_map_list = [
            [78 => 'icon-time', 93 => 'icon-worksheet', 1 => 'icon-monitor', 16 => 'icon-fashion',
                108 => 'icon-app', 8 => 'icon-travel', 77 => 'icon-shopping', 99 => 'icon-month'],
            [27 => 'icon-furniture', 35 => 'icon-aesthetic', 41 => 'icon-cosme', 50 => 'icon-gourmet',
                64 => 'icon-bag', 57 => 'icon-piggyabank', 23 => 'icon-network', 68 => 'icon-car'],
        ];

        $label_id_list = [];
        foreach ($search_map_list as $search_map) {
            $label_id_list = array_merge($label_id_list, array_keys($search_map));
        }

        $label_map = \App\Label::whereIn('id', $label_id_list)
            ->pluck('name', 'id')
            ->all();
        @endphp

        @foreach ($search_map_list as $search_map)
        <li><ul>
            @foreach($search_map as $label_id => $icon_name)
            <li><a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$label_id]]) }}">
                <div class="circle-ico"><span class="{{ $icon_name }}"></span></div>
                <div class="name-category">{{ $label_map[$label_id] }}</div>
            </a></li>
            @endforeach
        </ul></li>
        @endforeach
    </ul></div>
</section><!--/category-->

<script type="text/javascript"><!--
$(function(){
    $('.list_category').slick({
        autoplay:false,
        //autoplaySpeed:2000,
        dots:true,
        pauseOnHover:true,
        arrows: true
    });
});
//-->
</script>
