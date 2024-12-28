@extends('emails.layouts.default')

@section('title', 'GMOポイ活パスワード変更完了のご連絡')

@section('content')
[{{ $user->name }}]様

パスワードの変更を完了しました。
※お心当たりのない方は、大変お手数ですがご連絡くださいますようお願い申し上げます。
@endsection