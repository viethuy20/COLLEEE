@php
$base_css_type = 'feature';
@endphp
@extends('layouts.plane')

@section('layout.title', '特集一覧｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活がオススメする特集一覧！GMOポイ活経由で利用するだけでポイントを貯めることができます。貯めたポイントは、現金やギフト券に交換することができます。')
@section('url', route('features.index') )
@section('og_type', 'website')

@section('layout.head')
    {!! Tag::style('/css/feature_index.css') !!}
@endsection

@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
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
            特集一覧
        </li>

    </ol>
</section>
@endsection

@section('layout.content')

<div class="contents">
    {{--
    <ul class="breadcrumb">
        <li>{{ Tag::link(route('website.index'), 'トップページ') }}</li>
        <li>＞特集一覧</li>
    </ul>
    --}}

    <script>
        window.addEventListener('load', (event) => {
            const boxes = document.querySelectorAll(".feature__list li");
            boxes.forEach(box => {
                box.addEventListener('click', function() {
                    location.href =  box.firstChild.nextSibling.href;
                });
            });
        });
    </script>

    <h2 class="contents__ttl">特集一覧</h2>
    @if (!$feature_category_list->isEmpty())
    <ul class="feature__list">
        @foreach($feature_category_list as $category)
        @php
        $category_data = $category->json_data;
        @endphp
        <li>
            <a href="{{ route('features.show', ['feature_id' => $category->id]) }}">
                <p class="feature__list__ttl">{{ $category->title }}</p>
                <div class="feature__list__thumb">
                    {{ Tag::image($category_data->banner_img_url, $category_data->banner_img_alt, ['width' => 300, 'height' => 120]) }}
                </div>
                <div class="feature__list__txt"  style="margin-bottom:15px">
                    {{ $category_data->detail }}
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection
