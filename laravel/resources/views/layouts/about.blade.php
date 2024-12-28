@php
$base_css_type = 'about';
@endphp
@extends('layouts.plane')

@section('layout.sidebar')

<h2 class="contents__ttl">GMOポイ活について</h2>
<ul class="sidebar__list">
    <li>{{ Tag::link(route('abouts.membership_contract'), 'GMOポイ活会員利用規約') }}</li>
    <li> {{ Tag::link(config('url.privacy_policy'), 'プライバシーポリシー', ['target' => '_blank', 'class' => 'lnk_external']) }}</li>
    <li> {{ Tag::link(config('url.gmo_nikko'), '運営会社', ['target' => '_blank', 'class' => 'lnk_external']) }}</li>
</ul>

@if(Auth::check())
@include('elements.opinionbox')
@endif
@endsection