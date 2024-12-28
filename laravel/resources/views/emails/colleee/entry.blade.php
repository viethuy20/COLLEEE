@extends('emails.layouts.default')

@section('title', 'GMOポイ活仮登録')

@section('content')
まだ本登録は完了していません。
下記URLより本登録へお進み下さい。

▼本登録用URL
{!! route('entries.create', ['email_token_id' => $email_token_id]) !!}


本登録用URLの有効期限は24時間となっております。
24時間を経過した場合は、再度新規登録を行って下さい。

※このメールはGMOポイ活へ無料会員登録を行った方に自動で配信しています。
※メールに心当たりのない方はお手数ですが削除をお願い致します。
@endsection