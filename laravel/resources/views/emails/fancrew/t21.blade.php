@extends('emails.layouts.fancrew')

@section('content')
モニターにご協力いただきありがとうございました。
購入確認を行いましたところ、下記の理由により
承認を行う事が出来ませんでした。
下記内容をご確認ください。

******** 再提出理由 *********
{{ $reason }}
*****************************

入力データに誤りがある場合、
マイページ購入確認「コチラをクリックして、購入確認を再入力してください」より
正しい購入内容をご入力いただき、「提出する」ボタンを押してください。
（返品等された場合には差し引いた金額にて入力をお願いします。）

再提出後、再度、通販元による購入確認が入りますので
長くお待たせすることになりますが、ご理解の程宜しくお願い致します。

※再提出はこちらから
↓↓↓↓↓↓↓↓↓↓
{{ $mypage_url }}


※このメールは配信専用の為、返信いただいても受信が出来ません。
お問い合わせはサイト内、問い合わせフォームよりお願い致します。


@endsection