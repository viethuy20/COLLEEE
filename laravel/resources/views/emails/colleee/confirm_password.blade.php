@extends('emails.layouts.default')

@section('title', 'GMOポイ活パスワード再設定URLのご連絡')

@section('content')
[{{ $user->name }}]様

下記のURLからパスワード変更作業を行ってください。

{!! route('reminders.password', ['email_token_id' => $email_token_id]) !!}

※お心当たりのない方は、大変お手数ですがご連絡くださいますようお願い申し上げます。
@endsection
