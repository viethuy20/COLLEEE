<?php
$c_place = App\Search\FancrewCondition::getPrefectureMap();
$prefecture_map = $c_place->pluck('name', 'id');
$prefecture_id = $prefecture_id ?? 0;
$s_prefecture = $c_place->where('id', $prefecture_id)
        ->first();
$s_area_map = $s_prefecture['area_map'] ?? [];
$area_id = $area_id ?? 0;
?>
<script type="text/javascript"><!--
var areaMap = {
@foreach ($c_place as $prefecture)
<?php $area_map = $prefecture['area_map']; ?>
"{{ $prefecture['id'] }}" : {
@foreach ($area_map as $key => $value)
"{{ $key }}" : "{{ $value }}"
@if (!$loop->last)
,
@endif
@endforeach
}
@if (!$loop->last)
,
@endif
@endforeach
};

$(function() {
    $('#FancrewPrefecture').on('change', function(event) {
        // エリアを変更させる
        var s = $('#FancrewArea');
        s.children().remove();
        var dataMap = areaMap[$(this).val()];
        for (var key in dataMap) {
            s.append($('<option>').attr({ value: key }).text(dataMap[key]));
        }
    });
});
//-->
</script>
<div class="contentsbox mb_20">
    {!! Tag::formOpen(['url' => route('fancrew.pages', ['action' => 'pages']), 'method' => 'get']) !!}
    @csrf    
    <input type="hidden" name="_pf" value="search" />
        <input type="hidden" name="_pf_action" value="search" />
        @if ($use_freeword ?? true)
            <div class="search_keyword">
                {!! Tag::formText('freeword', $freeword ?? '', ['placeholder' => 'キーワードで検索', 'class' => 'searchbox']) !!}
            </div>
        @endif
        <div class="search_box">
            <p class="label_select">都道府県：</p>
            {!! Tag::formSelect('prefecture_id', $prefecture_map, $prefecture_id, ['class' => 'search_select', 'id' => 'FancrewPrefecture']) !!}
        </div>

        <div class="search_box">
            <p class="label_select">エリア：</p>
            {!! Tag::formSelect('area_id', $s_area_map, $area_id, ['class' => 'search_select', 'id' => 'FancrewArea']) !!}
        </div>

        @if ($use_category ?? true)
        <?php
        $c_category = App\Search\FancrewCondition::getCategoryMap();
        $category_map = $c_category->pluck('name', 'id');
        $category_id = $category_id ?? 0
        ?>
        <div class="search_category"><ul class="check_category mb_15">
            @foreach ($category_map as $c_id => $c_label)
            <li>
                {!! Tag::formRadio('category_id', $c_id, $c_id == $category_id) !!}
                <label>{{ $c_label }}</label>
            </li>
            @endforeach
        </ul></div>
        @endif

        {!! Tag::formSubmit('検索', ['class' => 'btn_select btn_more']) !!}
    {!! Tag::formClose() !!}
</div><!--/contentsbox-->
