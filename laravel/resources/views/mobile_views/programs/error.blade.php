@php
$base_css_type = 'detail';
@endphp
@extends('layouts.default')

@section('layout.title', $program->title.' | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,広告')
@section('layout.description', $program->title.'の詳細 | ここからの利用で、1P=1円相当のポイントが貯まります。')

@section('layout.content')

@switch ($error_type)
@case (1)
<div class="inner">
    <div class="programs_detail__content">
        <div class="programs_detail__ttl"><h2 class="programs_error__title">{{ $program->title }}</h2></div>
        <p class="programs_error__txt"><span>アクセスありがとうございます。<br />この広告は終了させていただきました。<br />ご了承ください。</span></p>
        <div class="programs_detail__item__btn">{{ Tag::link(route('website.index'), 'TOPへ', ['class' => 'cvbtn'], null, false) }}</div>
    </div><!--/programs_detail__content-->
</div><!--/inner-->
@break
@case (2)
<div class="inner">
    <div class="programs_detail__content">
        <div class="programs_detail__ttl"><h2 class="programs_error__title">{{ $program->title }}</h2></div>
        <p class="programs_error__txt"><span>
            アクセス頂いた広告は<br />
            @switch($program->devices)
            @case(1)
            PC専用広告です。
            @break
            @case(2)
            iOS専用広告です。
            @break
            @case (4)
            Android専用広告です。
            @break
            @case (6)
            iOS・Android専用広告です。
            @break
            @endswitch
        </span></p>
    </div><!--/programs_detail__content-->
</div><!--/inner-->
@break
@endswitch

@endsection
