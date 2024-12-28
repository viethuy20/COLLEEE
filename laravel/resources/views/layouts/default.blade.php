@extends('layouts.plane')

@php
    $path = Request::path();
    $pattern = '/^programs\/\d+/';
@endphp
@if (preg_match($pattern, $path))
@else
@section('layout.sidebar')
@include('elements.sidebar')
@endsection
@endif
