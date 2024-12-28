@extends('emails.layouts.default')

@section('title', 'GMOポイ活基本情報変更URLのご連絡')

@section('content')
[{{ $user->name }}]様

下記URLより基本情報変更画面へお進み下さい。

{!! route('users.confirm_tel', ['email_token_id' => $email_token_id]) !!}

※上記URLクリック後、「発信認証電話番号」が表示され、
　2分以内に発信、認証作業が必要です。
　メモが必要な際は事前にご準備ください。
※お心当たりのない方は、大変お手数ですがご連絡くださいますようお願い申し上げます。
@endsection
