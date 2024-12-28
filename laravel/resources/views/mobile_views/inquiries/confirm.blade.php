@php
$base_css_type = 'support';
@endphp
@extends('layouts.default')

@section('layout.title', 'お問い合わせ｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。分からないことや困ったことなどがございましたら、こちらでも質問を承ります。')

@section('layout.content')
<section class="inquiry">
    <h1>お問い合わせ内容の確認</h1>
    <div class="contentsbox">
        {{ Tag::formOpen(['route' => 'inquiries.store', 'method' => 'post']) }}
        @csrf    
        <div class="form_inquiry">
            <dl>
                <dt>お問い合わせ項目</dt>
                <dd>{{ config('map.inquiries')[$inquiry['inquiry_id']] }}</dd>

                <div>
                @if ($inquiry['inquiry_id'] == '3')
                <dt>対象広告名</dt>
                <dd>{{ $inquiry['program_name'] }}</dd>
                @endif

                @if ($inquiry['inquiry_id'] == '3')
                <dt><th>決済情報番号（月額広告の場合）</dt>
                <dd>{{ $inquiry['payment_number'] }}</dd>
                @endif

                <dt>{{ ($inquiry['inquiry_id'] == 10) ? '' : 'ご参加者様の' }}メールアドレス</dt>
                <dd>{{ $inquiry['email'] }}</dd>

                @if ($inquiry['inquiry_id'] == '3')
                <dt>ご参加者様のお名前</dt>
                <dd>{{ $inquiry['name'] }}</dd>
                @endif

                @if ($inquiry['inquiry_id'] == '3')
                <dt>参加日時</dt>
                <dd class="joined">
                    <span>{{ $inquiry['joined_at']['year'] }}</span>年
                    <span>{{ $inquiry['joined_at']['month'] }}</span>月
                    <span>{{ $inquiry['joined_at']['day'] }}</span>日
                    <span>{{ $inquiry['joined_at']['hour'] }}</span>時頃
                </dd>
                @endif

                <dt>お問い合わせ詳細</dt>
                <dd>{{ $inquiry['inquiry_detail'] }}</dd>

                @if ($inquiry['inquiry_id'] == '3')
                <dt>購入・登録完了メール</dt>
                <dd>{{ $inquiry['mail_message'] }}</dd>
                @endif
                </div>
            </dl>
            </div>

            {{ Tag::link(route('inquiries.index', ['inquiry_id' => $inquiry['inquiry_id']]), '入力内容をリセットする', ['class' => 'btn_cancel']) }}
            {{ Tag::formSubmit('送信する', ['class' => 'btn_send']) }}
        {{ Tag::formClose() }}
    </div><!--/contentsbox-->
</section><!--/inquiry-->
@endsection
