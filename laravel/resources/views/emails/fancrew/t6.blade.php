@extends('emails.layouts.fancrew')

@section('content')
{{ $user['name'] }}さん

「{{ $shop['name'] }}」モニターにご協力ありがとうございます。
ご回答いただきましたアンケート内容を確認させていただきましたところ、

問題はございませんでした。

ありがとうございました。

※リアルモニターではレシート画像を、通販モニターは購入確認番号入力にて確認作業を開始します。マイページにてご自身のステイタスをご確認の上、ご提出がまだの方はお早めに提出して下さい。アンケート、レシート（購入確認）両方が承認された時点でのポイント付与となります。

※アンケートのみの提出では謝礼対象としての取り扱いが出来ません、ご注意下さい！

このメールに返信しても受信できません。お問い合わせはサイト内「問い合わせフォーム」よりお願い致します。

@endsection