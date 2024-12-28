@extends('layouts.mypage')

@section('layout.title', 'メールアドレス変更｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

@section('layout.content')
<section class="contents">
    <h1 class="contents__ttl">メール送信完了</h1>

    <section class="contents__box">
        <p class="u-font-bold u-text-ac text--18 red">送信が完了しました</p>
        @if (config('app.env') != 'production')
        <div class="text--15 u-mt-20">
            テスト環境用URL:{!! route('users.confirm_email', ['email_token_id' => $email_token_id]) !!}<br />
        </div>
        @endif
        <div class="mypage__back__btn">
            {!! Tag::link(route('users.edit'), '戻る') !!}
        </div>
    </section><!--/setting-->
</section>
@endsection
