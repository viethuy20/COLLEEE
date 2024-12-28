<section class="category">
    <h2>カテゴリ</h2><!--変更あり-->
    <div class="carousel"><ul class="list_category">
        <?php
        $category_map = config('map.shop_category');
        $l1_shop_category_id_map = [1 => 'icon-shop', 15 => 'icon-fashion',
            2 =>'icon-cosme', 3 => 'icon-health', 9 => 'icon-gourmet', 4 => 'icon-book',
            11 => 'icon-car', 13 => 'icon-outdoor'];
        $l2_shop_category_id_map = [6 => 'icon-furniture', 14 => 'icon-office',
            8 =>'icon-present', 12 => 'icon-baby', 5 => 'icon-game', 7 => 'icon-monitor',
            10 => 'icon-pet', 16 => 'icon-shopping'];
        ?>
        <!--1-->
        <li><ul>
            @foreach($l1_shop_category_id_map as $shop_category_id => $class)
            <li><a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['shop_category_id' => $shop_category_id]) }}">
                <div class="circle-ico"><span class="{{ $class }}"></span></div><div class="name-category">{{ $category_map[$shop_category_id] }}</div>
            </a></li>
            @endforeach
        </ul></li>
        <!--/1-->
        <!--2-->
        <li><ul>
            @foreach($l2_shop_category_id_map as $shop_category_id => $class)
            <li><a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['shop_category_id' => $shop_category_id]) }}">
                <div class="circle-ico"><span class="{{ $class }}"></span></div><div class="name-category">{{ $category_map[$shop_category_id] }}</div>
            </a></li>
            @endforeach
        </ul></li>
        <!--/2-->
    </ul></div>
</section><!--/category02-->

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