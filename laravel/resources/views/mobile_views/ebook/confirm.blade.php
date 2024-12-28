@php
    $base_css_type = 'api_confirm';
@endphp

@extends('layouts.default')

@section('layout.title', 'ID連携 | ポイントサイトならGMOポイ活')

@section('layout.content')
<div class="contents">
    <div class="inner u-mt-20">
        <h2 class="contents__ttl">ID連携</h2>
    </div>

    <section class="inner">
        <div class="contents__box u-mt-20">
            <h2 class="id_relation__ttl">ネットマンガ総研 byGMO</h2>
            <div class="text--15 u-mt-20">
                <p>ネットマンガ総研 byGMO のサイトの会員IDとGMOポイ活のサイトの会員IDを連携するには、「ID連携する」ボタンをクリックしてください。</p>
            </div>

            @php
                $url = ($redirect_uri) ? route('api.login.redirect') ."?type=$ems_type&redirect_uri=$redirect_uri" : '';
            @endphp

            <div class="id_relation__btn">
                {{ Tag::link($url, '<p class="btn_toall">ID連携する</p>', null, null, false) }}
            </div>
        </div>
    </section>
</div>
@endsection
