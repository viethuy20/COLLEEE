@extends('emails.layouts.fancrew')

@section('content')
{{ $user['name'] }}さん、こんにちは(○´∀｀)☆

「{{ $shop['name'] }}」モニターへのご応募ありがとうございます♪
厳正な抽選の結果、{{ $user['name'] }}さんは
☆。.゜★。.*・☆。.*゜★。.*・☆。*゜★。.*・☆。
┏━┓┏━┓┏━┓┏━┓┏━┓┏━┓
┃☆┣┫★┣┫当┣┫選┣┫★┣┫☆┃
┗━┛┗━┛┗━┛┗━┛┗━┛┗━┛
☆。.゜★。.*・☆。.*゜★。.*・☆。*゜★。.*・☆。
…となりました！！

{{ $user['name'] }}さん、おめでとうございます！
／(*^ー^)//"""" パチパチ

※モニターを行う前に必ず、マイページにてご当選店舗の事前確認内容をご確認ください。
{{ $mypage_url }}

必ずご自身の提出期限を確認し、遅れる事のないようご注意下さい。

※このメールに返信しても受信出来ません。お問い合わせは必ずサイト内「お問い合わせフォーム」よりお願い致します。

@endsection