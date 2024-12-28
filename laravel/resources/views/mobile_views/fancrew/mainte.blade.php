@extends('layouts.fancrew')

@section('layout.title', 'モニター（お店でお得）')
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
            モニター（お店でお得）
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
<div class="contentsbox">
    <section class="maintenance">
        <p class="mb_15 mt_20">「モニター（お店でお得）」は<br />PC専用コンテンツです。<br />
        <br />
        <span>お手数ですが、PCから<br />再度アクセスしてください。</span></p>
    </section><!--/maintenance-->

    <section>
        <div class="btn_y">
            {!! Tag::link(route('website.index'), 'トップページ') !!}
        </div>
    </section>
</div>
@endsection