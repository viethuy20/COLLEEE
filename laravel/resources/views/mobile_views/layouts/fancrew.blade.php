<?php $base_css_type = 'fancrew'; ?>
@extends('layouts.default')

@section('layout.content')
<h1 class="ttl_review">@yield('fancrew.title')</h1>
@yield('fancrew.content')
<div class="flogo">{!! Tag::image('/images/logo_fancrew.png', 'ファンくる') !!}</div>
@endsection
