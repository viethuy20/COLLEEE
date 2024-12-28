@extends('layouts.plane')

@section('layout.title', 'ロック')

@section('layout.content')
<section class="lock">
    <section class="contents__box">
        <div class="errors__center__box">
            <div class="errors__center__box__main">
                <p class="u-font-bold u-text-ac text--18 red">以下の理由によりアクセスが制限されています。</p>
                <div class="u-mt-20">
                    <p class="text--15" style="text-align: center;">
                        会員利用規約に違反したと受け取れるサイト内行動が見られたので、<br />
                        会員資格を停止させていただきました。<br /><br>
                        下記リンクのお問い合わせフォームにてご連絡ください。
                    </p>
                </div>
                <div class="inquiries__btn" style="margin-top: 30px;">
                    {!! Tag::link(route('inquiries.index', ['inquiry_id' => 10]), 'お問い合わせ') !!}
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
