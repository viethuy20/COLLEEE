@extends('layouts.mypage')

@section('layout.title', 'メールマガジン受信設定｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')
 
@section('layout.content')
<section class="contents"> 
    <h2 class="contents__ttl">メールマガジン受信設定完了</h2> 

    <section class="contents__box"> 
        <div class="users__center__box">
            <div class="users__center__box__text">
                <p class="text--15">メールマガジン受信設定が<br />完了しました。</p>
            </div>
        </div><!--/contentsbox--> 
        <div class="basic__change__btn">
            {!! Tag::link(route('users.show'), 'マイページ') !!}
        </div>
    </section><!--/setting--> 
</section><!--/contents-->
@endsection
