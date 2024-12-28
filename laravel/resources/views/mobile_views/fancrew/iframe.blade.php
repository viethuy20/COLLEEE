@extends('layouts.fancrew')

@section('layout.title', 'お店でお得')
@section('layout.keywords', '')
@section('layout.description', '')
@section('fancrew.title', 'お店でお得')

@section('fancrew.content')
<section class="monitor_detail">
    <iframe width="800" height="600" style="border: 0px" src="{!! $remoteControllerURL !!}"></iframe>
</section><!--/monitor_detail-->
@endsection
