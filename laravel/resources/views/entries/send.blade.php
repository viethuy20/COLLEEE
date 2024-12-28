<?php
    $base_css_type = 'entries';
    $hidden_header = true;
?>
@extends('layouts.plane')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
@endphp
@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>
        @endforeach
        <li>
            新規会員登録
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
@php
$parsed_email = explode('@', $email);
$email_domain = array_pop($parsed_email);

@endphp
<div class="contents">

    <h2 class="contents__ttl">仮登録メール送信</h2>
    <div class="contents__box">
        <div class="contents__box__inner">
            <div class="contents__box__txt">
                <h3 class="contents__box__ttl"><i><img src="/images/common/ico_check.svg"></i>仮登録メールを送信しました</h3>
                <p><span id="entries-address" class="entries__address">**********{!! '@' !!}{{ $email_domain }}</span>宛に仮登録メールを送信しました。<br>記載されたURLをクリックし、24時間以内に本登録を完了させて下さい。</p>
            </div>
            <div class="contents__btn__wrap">
                <div class="contents__btn orange">
                    <a href="/" id="entries-mail-btn">トップページへもどる</a>
                </div>
            </div>
            @if (config('app.env') != 'production' && isset($email_token_id))
            <div class="text--15 u-mt-20">
                テスト環境用URL:{{ route('entries.create', ['email_token_id' => $email_token_id]) }}<br />
            </div>
            @endif
            <dl class="contents__notes">
                <dt class="contents__notes__ttl">注意事項</dt>
                <dd  class="contents__notes__box">
                    <ul class="contents__notes__list">
                        <li>24時間以内に本登録されない場合は、仮登録データが自動で削除されますのでご注意下さい。</li>
                        <li>メールが届かない場合には、拒否設定などをご確認いただき再度登録をお願いします。</li>
                        <li>「仮登録完了メール」が迷惑メールフォルダに振り分けられることもありますので、メールが届かない場合は、念のため迷惑メールフォルダ内のチェックもお願いいたします。</li>
                        <li>キャリアメールアドレスをご利用の場合、設定によりメールが受信できない場合がございます。指定受信設定をされている場合は、{{ config('mail.from.address') }}を受信可能に設定して下さい。</li>
                        <li>フリーメールアドレスやネットワーク事業者によっては、「GMOポイ活」からのメールを受信していただけない場合がございます。</li>
                        <li>上記に当てはまらない場合は、お手数ですが「{{ Tag::link(route('inquiries.index', ['inquiry_id' => 10,]), 'お問い合わせフォーム' , ['target' => '_blank', 'class' => 'textlink blank']) }}」よりご連絡下さい。</li>
                    </ul>
                </dd>
            </dl>
        </div>
    </div>
</div><!-- /.contents -->
{!! Tag::script('/js/entries.js', ['type' => 'text/javascript']) !!}
@endsection
