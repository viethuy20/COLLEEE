@extends('layouts.plane')

@section('layout.title', 'ロック')

@section('layout.content')
<section class="lock">
    <section class="contents__box">
        <div class="errors__center__box">
            <div class="errors__center__box__main">
                <p class="u-font-bold u-text-ac text--18 red">都合によりアクセスが制限されています。</p>
                <div class="u-mt-20">
                    <p class="text--15" style="text-align: center;">
                        下記お問い合せリンクにてご連絡ください。
                    </p>
                </div>
                <div class="inquiries__btn">
                    {!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせ') !!}
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
