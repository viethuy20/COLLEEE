@extends('layouts.fancrew')

@section('layout.title', 'お店でお得検索結果 | いつもの生活がちょっとお得になるGMOポイ活')
@section('layout.keywords', 'モニター,覆面調査')
@section('layout.description', 'お得になるお店・商品の検索結果が一覧で表示されます。エリアやカテゴリで検索が可能！')
@section('og_type', 'website')
@section('fancrew.title', '検索結果')

@section('layout.head')
    @if ($paginator->currentPage() > 1)
        <link rel="prev"
              href="{{ $condition->getListUrl((object) ['sort' => $condition->getParam('sort'), 'page' => $paginator->currentPage() - 1]) }}"/>
    @endif
    @if (($paginator->currentPage() < $paginator->lastPage()))
        <link rel="next"
              href="{{ $condition->getListUrl((object) ['sort' => $condition->getParam('sort'), 'page' => $paginator->currentPage() + 1]) }}"/>
    @endif
@endsection

@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "モニター（お店でお得）", "item": "' . $link . '"},';
$position++;
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "検索結果", "item": "' . $link . '"},';

@endphp
@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>
        @endforeach
        <li>
            {{ Tag::link(route('fancrew.pages'), 'モニター（お店でお得）') }}
        </li>
        <li>
            検索結果
        </li>
    </ol>
</section>
@endsection

@section('fancrew.content')
    <section class="search_monitor">
        <?php
        $c_place = $condition::getPrefectureMap();
        $prefecture_map = $c_place->pluck('name', 'id');

        $prefecture_id = $condition->getParam('prefecture_id');
        $area_id = $condition->getParam('area_id');
        $freeword = $condition->getParam('freeword');
        $category_id = $condition->getParam('category_id');

        $s_prefecture = $c_place->where('id', $prefecture_id)
            ->first();
        $s_area_map = $s_prefecture['area_map'] ?? [];
        ?>
        <dl class="search_element clearfix">
            <dt>キーワード</dt>
            <dd>{{ $freeword }}&nbsp;</dd>
            <dt>都道府県</dt>
            <dd>{{ $prefecture_map[$prefecture_id] ?? ''}}</dd>
            <dt>エリア</dt>
            <dd>{{ $s_area_map[$area_id] ?? ''}}</dd>
        </dl>

        @include('elements.fancrew_form', ['freeword' => $freeword, 'prefecture_id' => $prefecture_id, 'area_id' => $area_id, 'category_id' => $category_id])
    </section><!--/search_monitor-->

    <section class="search_result">
        <div class="contentsbox">
            <ul id="refinement" class="clearfix">
                <?php
                $sort_value = $condition->getParam('sort') ?? 0;
                $sort_map = [1 => ['label' => '新着順', 'id' => 'new'],
                    2 => ['label' => '謝礼（％）順', 'id' => 'reward'],
                    0 => ['label' => '当たりやすい順', 'id' => 'probability']];
                ?>
                @foreach ($sort_map as $sort_id => $data)
                    @if ($sort_value == $sort_id)
                        <li class="" id="{{ $data['id'].'_on' }}">{{ $data['label'] }}</li>
                    @else
                        <li class="" id="{{ $data['id'].'_off' }}">
                            {!! Tag::link($condition->getListUrl((object) ['sort' => $sort_id]), $data['label']) !!}
                        </li>
                    @endif
                @endforeach
            </ul>

            <?php $new_border_at = \Carbon\Carbon::now()->addDays(-7); ?>
            <ul class="list_result">
                @foreach ($paginator as $shop_xml)
                        <?php
                        $shop_attributes = $shop_xml->attributes();
                        $release_at = \Carbon\Carbon::parse($shop_attributes->releaseTimestamp);
                        $shop_name = $shop_attributes->name;
                        $rate = $shop_xml->Monitor->Rate;
                        $rate_attributes = $rate->attributes();
                        $category_attributes = $shop_xml->Category->attributes();
                        ?>
                    <li>
                        <a href="{{ route('fancrew.pages', ['action' => 'pages']).'?'.http_build_query(['_pf' => 'shop', '_pf.shop_id' => intval($shop_attributes->id, 10)]) }}">
                            <span class="category">{{ $category_attributes->name ?? '' }}</span>
                            @if ($new_border_at->lt($release_at))
                                <span class="new">NEW</span>
                            @endif
                            <p class="heading">{{ $shop_name }}</p>
                            <div class="clearfix mt_5">
                                <div
                                    class="eachbnr">{!! Tag::image($shop_attributes->topImageUrl, $shop_name) !!}</div>
                                <dl class="fr">
                                    <dt>平均予算</dt>
                                    <dd>{{ $shop_attributes->averageBudget }}</dd>
                                    <dt>アクセス</dt>
                                    <dd>{{ $shop_attributes->access }}</dd>
                                </dl>
                                <p class="point">
                                    <span class="icon-point"></span>
                                    @if ($rate_attributes->type == '固定')
                                        {{ number_format($rate_attributes->value * 10) }}pt
                                    @else
                                        お代金の{{ $rate_attributes->value }}%(上限
                                        @if (is_null($rate_attributes->limit) || $rate_attributes->limit == '')
                                            なし
                                        @else
                                            {{ number_format($rate_attributes->limit * 10) }}pt)
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </a></li>
                @endforeach
            </ul>
            {!! $paginator->render('elements.pager', ['pageUrl' => function($page) use($condition) { return $condition->getListUrl((object) ['page' => $page]); }]) !!}
        </div><!--/contents_box-->
        <p class="btn_y for_link" forUrl="{{ route('fancrew.pages', ['action' => 'pages'])}}">お店でお得トップに戻る</p>
    </section><!--/search_monitor-->
@endsection
