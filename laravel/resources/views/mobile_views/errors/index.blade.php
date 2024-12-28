<?php $base_css_type = 'signup'; ?>
@extends('layouts.default')

@section('layout.title', 'エラー')

@section('layout.content')
<section class="inner">
    <div class="contents__box u-mt-20">
        <div class="users__center__box">
            <div class="users__center__box__main">
                @if (Session::has('message'))
                <p class="u-font-bold u-text-ac text--18 red">{!! nl2br(Session::get('message')) !!}</p>
                @endif
                @if (Session::has('back'))
                <?php $back = Session::get('back'); ?>
                <div class="basic__change__btn">
                    {!! Tag::link($back['url'] ?? route('website.index'), $back['label'] ?? '前のページへ戻る') !!}
                </div>
                @if (isset($back['message']))
                <p class="text--15">{!! nl2br($back['message']) !!}</p>
                @endif
                @endif
            </div>
        </div>
    </div>
</section><!--/contentsbox--><!--/process-->
@endsection
