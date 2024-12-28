@php
$base_css_type = 'support';
@endphp
@extends('layouts.plane')

@section('layout.title', 'お問い合わせ｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。分からないことや困ったことなどがございましたら、こちらでも質問を承ります。')

@section('layout.content')
<section class="inquiry">
    <h1>お問い合わせ完了</h1>
    <div class="contentsbox">
        <div class="store_inquiry">
        <p class="ends">お問い合わせ頂きありがとうございました！</p>
        <p>
            ※土日・祝日・年末年始及び受付時間外は対応を行っておりません。<br />
            ※返信はお問い合わせの内容により、2週間程度お時間をいただく場合がございます。<br />
            なお、「ポイント獲得」に関するお問い合わせにつきましては、関係各所への確認が必要となる為、1～3ヶ月程度のお時間をいただく場合がございます。<br />
            ※ご質問の内容によってはお答えできない場合がございますので、 あらかじめご了承ください。<br />
        </p>
        <p class="btn_top btn_more"><a href="/support/">お客様サポートトップへ</a></p>
        </div><!--/store_inquiry-->
    </div><!--/contentsbox-->
</section><!--/inquiry-->

@endsection
