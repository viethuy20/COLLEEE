@php
$base_css_type = 'feature';
@endphp
@extends('layouts.default')

@section('layout.title', '特集一覧｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活がオススメする特集一覧！GMOポイ活経由で利用するだけでポイントを貯めることができます。貯めたポイントは、現金やギフト券に交換することができます。')
@section('url', route('features.index') )
@section('og_type', 'website')

@section('layout.head')
    {!! Tag::style('/css/sp_feature_index.css') !!}
@endsection

@section('layout.breadcrumbs')
    <section class="header__breadcrumb">
        <ol>
            <li>
                <a href="{{route('website.index')}}">ホーム </a>
            </li>
            <li>特集一覧</li>
        </ol>
    </section>
@endsection

@section('layout.content')

<div class="inner">
    <div class="feature__ttl">
        <h2 class="contents__ttl">特集一覧</h2>
    </div>
</div>


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
<div class="inner">
    @if (!$feature_category_list->isEmpty())
    <ul class="feature__list">
        @foreach($feature_category_list as $category)
        @php $category_data = $category->json_data;
        @endphp
        <li class="">
            <a href="{{ route('features.show', ['feature_id' => $category->id]) }}">
                <p class="feature__list__ttl">{{ $category->title }}</p>
                <div class="feature__list__thumb">{{ Tag::image($category_data->banner_img_url, $category_data->banner_img_alt) }}</div>
                <p class="feature__list__txt">{{ $category_data->detail }}</p>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection
