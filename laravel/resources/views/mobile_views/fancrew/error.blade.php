@extends('layouts.fancrew')

@section('layout.title', 'お店でお得')
@section('layout.keywords', '')
@section('layout.description', '')
@section('fancrew.title', 'エラー')

@section('fancrew.content')
<section class="monitor_detail">
    {{ $errorMessage }}
</section><!--/monitor_detail-->
@endsection
