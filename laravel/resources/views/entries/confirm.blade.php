<?php
    $base_css_type = 'entries';
    $hidden_header = true;
?>
@extends('layouts.plane')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')

@php
    $meta = new \App\Services\Meta;
    $arr_breadcrumbs = $meta->setBreadcrumbs(null);
    $colleeeAccountEntriesCreate = 'entries.create';
    $previouseRouteName = Route::getRoutes()->match(request()->create(URL::previous()))->getName();

    if ($previouseRouteName == $colleeeAccountEntriesCreate && Cookie::has('cookie_social_callback')) {
        Cookie::queue(Cookie::forget('cookie_social_callback'));
    }
@endphp

@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>
        @endforeach
        <li>
            新規会員登録
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
    <div class="contents">
        <h2 class="contents__ttl">会員情報確認</h2>

        <div class="contents__box">
            <div class="contents__box__inner">
                <ol class="entries__flow">
                    <li class="prev"><i><img src="/images/entries/ico_flow_input.svg"></i><p>入力</p></li>
                    <li class="current"><i><img src="/images/entries/ico_flow_confirm.svg"></i><p>確認</p></li>
                    <li class=""><i><img src="/images/entries/ico_flow_call.svg"></i><p>電話認証</p></li>
                    <li class=""><i><img src="/images/entries/ico_flow_success.svg"></i><p>完了</p></li>
                </ol>
                <div class="contents__box__txt">
                    <p>入力内容を確認してください。内容をご確認の上、よろしければ「電話による発信認証」ボタンを押してください。<br>次ページはご本人確認のための電話による発信認証になります。メモが必要な際は事前にご準備ください。</p>
                </div>

                <div class="entries__create">
                    <div class="entries__form">
                        <div class="entries__form__inner">
                            <dl class="entries__form__table confirm">
                                <dt><label for="email">メールアドレス</label></dt>
                                <dd>
                                    <p>{{ $entry_user['email'] }}</p>
                                </dd>
                                <dt><label for="email">電話番号</label></dt>
                                <dd>
                                    <p>{{ $entry_user['tel'] }}</p>
                                    <ul class="notes">
                                        <li>本人確認のための「電話認証」で使用いたします</li>
                                    </ul>
                                </dd>
                                <dt><label for="email">生年月日</label></dt>
                                <dd>
                                    <p>{{ $entry_user['birthday']['year'] }}年{{ $entry_user['birthday']['month'] }}月{{ $entry_user['birthday']['day'] }}日</p>
                                </dd>
                                @if(isset($entry_user['sex']))
                                <dt><label for="email">性別</label></dt>
                                <dd>
                                    <p>{{ config('map.sex')[$entry_user['sex']] ?? 'その他' }}</p>
                                </dd>
                                @endif
                                @if(isset($entry_user['prefecture_id']))
                                <dt><label for="email">居住地</label></dt>
                                <dd>
                                    <p>{{ config('map.prefecture')[$entry_user['prefecture_id']] }}</p>
                                </dd>
                                @endif
                                @if(isset($entry_user['carriers']))
                                <dt><label for="email">ご使用のスマートフォン</label></dt>
                                <dd>
                                    <p>{{ $entry_user['carriers'] }}</p>
                                </dd>
                                @endif
                                @if(isset($entry_user['invitation_code']))
                                <dt><label for="email">紹介コード</label></dt>
                                <dd>
                                    <p>{{ $entry_user['invitation_code'] }}</p>
                                </dd>
                                @endif

                            </dl>

                            <div class="contents__btn__wrap">
                                <div class="contents__btn prev">
                                    <a href="{{ route('entries.create') }}?r=entry-tel">上記内容を修正する</a>
                                </div>
                                <div class="contents__btn orange">
                                    {!! Tag::formButton('電話による発信認証', ['onclick' => "location.href='".url(route('entries.confirm_tel'))."'"]) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div><!--/container02-->
    </div>
    @if(session()->has('error_phone'))
        @include('inc.modal')
    @endif

    {!! Tag::script('/js/modal.js', ['type' => 'text/javascript']) !!}
    {!! Tag::script('/js/entries.js', ['type' => 'text/javascript']) !!}
@endsection
