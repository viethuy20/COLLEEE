@extends('emails.layouts.fancrew')

@section('content')
{{ $user['name'] }}さん、こんにちは。

いつもご利用ありがとうございます。
ご当選店舗のキャンセル処理がされましたので、ご連絡させていただきます。

ご当選の「{{ $shop['name'] }}」のモニターですが、{{ $reason }}により、キャンセルとして処理させていただきました。（提出期限は予めマイページにてご案内していた日時です。）

※モニターをキャンセルされても、お店の予約の取り消しや、
商品購入のキャンセルにはなりません。
※既に予約や、商品購入の手続きがお済みの場合は、
必ずご自身でお店（通販元）にキャンセルの連絡をしてください。

そのほか、たくさんのモニターを実施しておりますので、いきたい店舗を探してご応募ください。

※このメールに返信しても受信できません。お問い合わせは必ずサイト内「問い合わせフォーム」よりお願い致します。

@endsection