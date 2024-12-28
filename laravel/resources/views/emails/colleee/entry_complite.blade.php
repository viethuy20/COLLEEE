@extends('emails.layouts.default')

@section('title', 'GMOポイ活本登録完了')

@section('content')
ようこそGMOポイ活へ！
ご登録頂きありがとうございます。


ご登録いただきました内容は以下になります。
GMOポイ活を利用するのに必要となりますので、大切に保管下さい。

━━━━━━━━━━━━━━━━
■メールアドレス：{{ $email }}
■ユーザーID：{{ $user_name }}
━━━━━━━━━━━━━━━━

「GMOポイ活」を使うだけでいつもの生活がちょっとお得に♪
クレジットカードの賢い使い方、公共料金を1%オフにできる裏ワザなど、あなたの生活をお得にする情報が盛り沢山の「ポイ活お得情報」は必見！

▼さっそく広告を探す
{!! route('programs.list') !!}

▼ポイ活お得情報を読みに行く
{!! route('website.index') !!}/article/

▼サイトを利用する上で、ご不明点・お困りの場合はお客様サポートへ！
{!! route('website.index') !!}/support/
@endsection