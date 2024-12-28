@extends('layouts.mypage')

@section('layout.title', '発信認証｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。無料で会員登録して、いつもの生活を賢くお得に！')

@section('layout.content')
<section class="contents">
    <h1 class="contents__ttl">認証</h1>
    <section class="contents__box">
        <div class="users__center__box">
            <div class="users__center__box__text">
                <p>※期限2分※</p>
                <p>ご登録の電話番号から、「発信認証電話番号」へ発信してください。<br />呼び出し音の後、自動的に通話が終了します。（音声アナウンス等は流れません）</p><br />
                <p>上記完了後、「認証」ボタンを押してください。</p><br />
                <p>※通話料金は無料です。<br />※電話番号の入力間違いにご注意ください。</p>
            </div>
        </div>
    </section><!--/setting-->
    {!! Tag::formOpen(['url' => route('phones.auth')]) !!}
    @csrf    
    {!! Tag::formHidden('referer', $referer) !!}
        <h2 class="contents__ttl u-mt-20">発信認証電話番号</h2>
        <div class="contents__box">
            <div class="users__center__box">
                <div class="users__center__box__main">
                    <p class="text--15">{{ $ost_token->authentic_number }}</p>
                    @if (isset($message))
                    <!--エラーの場合はここに-->
                    <p class="error_message"><span class="icon-attention"></span>{{ $message }}</p>
                    @endif
                </div>
                {!! Tag::formSubmit('認証', ['class' => 'users_auth__btn']) !!}
            </div>
        </div>
    {!! Tag::formClose() !!}
</section><!--/contents-->
@endsection
