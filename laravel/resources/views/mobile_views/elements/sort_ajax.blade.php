<select class="AjaxContent">
    <?php $sort_id_list = ['eins', 'zwei', 'drei']; ?>
    @foreach($sort_map as $key => $val)
    <?php
    $selected = false;
    if ($key == $sort) {
        $selected = true;
    }
    ?>
    <option forUrl="{{ $val['url'] }}" forRender="{{ $render_id }}" {{ $selected ? 'selected' : ''}}>{{ $val['title'] }}順</option>
    @endforeach
</select>
