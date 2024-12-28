@php
    $base_css_type = 'api_confirm';
@endphp
@extends('layouts.plane')

@section('layout.title', 'ID連携 | ポイントサイトならGMOポイ活')

@section('layout.content')
<div class="contents" style="width: 100%; max-width: none;">
    <h2 class="contents__ttl">ID連携</h2>

    <div class="contents__box">
        <h2 class="id_relation__ttl">ネットマンガ総研 byGMO</h2>
        <div class="text--15 u-mt-20">
            <p>ネットマンガ総研 byGMO のサイトの会員IDとGMOポイ活のサイトの会員IDを連携するには、「ID連携する」ボタンをクリックしてください。</p>
        </div>

        @php
            $url = ($redirect_uri && $ems_type) ? route('api.login.redirect') ."?type=$ems_type&redirect_uri=$redirect_uri" : '';
        @endphp

        <section class="u-mt-20">
            <p class="id_relation__btn">{{ Tag::link($url, 'ID連携する') }}</p>
        </section>

        <section class="bnrarea mt_20">
            <script>
                googletag.cmd.push(function() {
                    googletag.defineSlot('/25183360/Co_web_exchange_pc', [1080, 150], 'div-gpt-ad-1545114545045-0').addService(googletag.pubads());
                    googletag.pubads().enableSingleRequest();
                    googletag.pubads().collapseEmptyDivs();
                    googletag.enableServices();
                });
            </script>
        </section>
    </div>
</div>
@endsection
