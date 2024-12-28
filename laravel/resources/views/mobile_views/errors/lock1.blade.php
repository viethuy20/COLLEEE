@extends('layouts.default')

@section('layout.title', 'ロック')

@section('layout.content')
<section class="inner">
    <div class="contents__box u-mt-20">
        <div class="errors__center__box">
            <div class="errors__center__box__main">
                <p class="u-font-bold u-text-ac text--18 red">以下の理由によりアクセスが</p>
                <p class="u-font-bold u-text-ac text--18 red">制限されています。</p>
                <div class="text--15 u-mt-20" style="text-align: center;">
                    <p>下記お問合せリンクにてご連絡ください。</p>
                </div>
                <div class="inquiries__btn">
                    {!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせ') !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
