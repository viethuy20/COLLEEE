@extends('layouts.fancrew')

@section('layout.title', 'お店でお得')
@section('layout.keywords', '')
@section('layout.description', '')

@section('fancrew.content')
@if (isset($remoteControllerURL))
<iframe width="800" height="600" style="border: 0px" src="{!! $remoteControllerURL !!}"></iframe>
@endif
@endsection

@section('layout.fancrew')
<div class="P_fancrew">
    <div class="flogo">{!! Tag::image('/images/logo_fancrew.png', 'ファンくる') !!}</div>
</div>
@endsection