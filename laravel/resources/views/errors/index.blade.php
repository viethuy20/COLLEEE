<?php $base_css_type = 'signup'; ?>
@extends('layouts.plane')

@section('layout.title', 'エラー')

@section('layout.content')
<section class="contents">
    <section class="contents__box">
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
                <div class="w840">
                    <p class="text--15 ta_c">{!! nl2br($back['message']) !!}</p>
                </div>
                @endif
                @endif
            </div>
        </div>
    </section>
</section><!--/container02--><!--/register-->
@endsection
