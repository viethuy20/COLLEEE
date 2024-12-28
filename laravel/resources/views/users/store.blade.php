@extends('layouts.mypage')

@section('layout.title', $item.'変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents">
    <!-- page title -->
    <h2 class="contents__ttl">{{ $item }}変更完了</h2>

    <section class="contents__box">
        <p class="u-font-bold u-text-ac text--18 red">{{ $item }}の<br />変更が完了しました。</p>
        <div class="basic__change__btn">
            {!! Tag::link(route('users.edit'), '基本情報変更へ戻る') !!}
        </div>
    </section>
</section>
@endsection
