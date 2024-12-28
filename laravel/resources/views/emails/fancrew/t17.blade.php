@extends('emails.layouts.fancrew')

@section('content')
{{ $user['name'] }}さん、こんにちは(○´∀｀)☆

モニター来店期限延長のお知らせです♪

「{{ $shop['name'] }}」の来店期限が過ぎてしまいましたが、

まだまだ応募人数が少なく、

{{ $user['name'] }}さんにぜひご来店頂きたいということで、

来店期間を延長させていただきました！

来店期限延長モニターを利用していただきますと、

他のお店も ☆当選☆ しやすくなりますので、

ぜひモニターとして「{{ $shop['name'] }}」へご来店ください♪

※モニターを行う前に必ず、マイページにてご当選店舗のモニター注意事項をご確認ください。
{{ $mypage_url }}


※もし既に来店している場合、提出期限に遅れる事なくアンケート、レシート等をご提出くださいませ。
※このメールに返信しても受信出来ません。お問い合わせは必ずサイト内「問い合わせフォーム」よりお願い致します。

@endsection