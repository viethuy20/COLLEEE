@php
$base_css_type = 'incentive';
@endphp
@extends('layouts.default')

@section('layout.title', 'このページの表示期間は終了しました。｜ポイントサイトならGMOポイ活')

@section('layout.content')

<div class="contents__ttl">
    <p class="ends">アクセスありがとうございます。<br />
    このページの表示期間は終了しました。<br />
    ご了承ください。</p>
    {{ Tag::link(route('website.index'), '<span style="color:#f39800">TOPへ</span>', null, null, false) }}
</div>

@endsection
