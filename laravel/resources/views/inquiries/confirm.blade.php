@php
$base_css_type = 'support';
@endphp
@extends('layouts.plane')

@section('layout.title', 'お問い合わせ｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。分からないことや困ったことなどがございましたら、こちらでも質問を承ります。')

@section('layout.content')

<section class="inquiry">
    <h1>お問い合わせ内容の確認</h1>
    <div class="contentsbox">
        {{ Tag::formOpen(['route' => 'inquiries.store', 'method' => 'post']) }}
        @csrf    
        <table><tr>
                <th>お問い合わせ項目</th>
                <td>{{ config('map.inquiries')[$inquiry['inquiry_id']] }}</td>
            </tr></table>

            @if (isset($inquiry['program_name']))
            <table><tr>
                <th>対象広告名</th>
                <td>{{ $inquiry['program_name'] }}</td>
            </tr></table>
            @endif

            @if (isset($inquiry['payment_number']))
            <table><tr>
                <th>決済情報番号<br />（月額広告の場合）</th>
                <td>{{ $inquiry['payment_number'] }}</td>
            </tr></table>
            @endif

            <table><tr>
                <th>
                    @if ($inquiry['inquiry_id'] != '10')
                    ご参加者様の<br />
                    @endif
                    メールアドレス
                </th>
                <td>{{ $inquiry['email'] }}</td>
            </tr></table>

            @if (isset($inquiry['name']))
            <table><tr>
                <th>ご参加者様の<br />お名前</th>
                <td>{{ $inquiry['name'] }}</td>
            </tr></table>
            @endif

            @if (isset($inquiry['joined_at']))
            <table><tr>
                <th>参加日時</th>
                <td>
                    @if (isset($inquiry['joined_at']['year']))
                    <span>{{ $inquiry['joined_at']['year'] }}</span>年
                    @endif
                    @if (isset($inquiry['joined_at']['month']))
                    <span>{{ $inquiry['joined_at']['month'] }}</span>月
                    @endif
                    @if (isset($inquiry['joined_at']['day']))
                    <span>{{ $inquiry['joined_at']['day'] }}</span>日
                    @endif
                    @if (isset($inquiry['joined_at']['hour']))
                    <span>{{ $inquiry['joined_at']['hour'] }}</span>時頃
                    @endif
                </td>
            </tr></table>
            @endif

            <table><tr>
                <th>お問い合わせ詳細</th>
                <td>{{ $inquiry['inquiry_detail'] }}</td>
            </tr></table>

            @if (isset($inquiry['mail_message']))
            <table><tr>
                <th>購入・登録<br />完了メール</th>
                <td>{{ $inquiry['mail_message'] }}</td>
            </tr></table>
            @endif

            <div class="clearfix btns">
                {{ Tag::link(route('inquiries.index', ['inquiry_id' => $inquiry['inquiry_id']]), '入力内容をリセットする', ['class' => 'btn_cancel']) }}
                {{ Tag::formSubmit('送信する', ['class' => 'btn_send btn_more']) }}
            </div><!--/clearfix btns-->
        {{ Tag::formClose() }}
    </div><!--/contentsbox-->
</section><!--/inquiry-->
@endsection
