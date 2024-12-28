==============================================================
お客様の情報
==============================================================
@if (isset($name))
お名前　　　　　　　：{{ $name }}
@endif
@if (isset($email))
メールアドレス　　　：{{ $email }}
@endif
@if (isset($user_name))
GMOポイ活 ID　　　　　：{{ $user_name }}
@endif
@if (isset($inquiry_id))
問い合わせ項目　　　：{{ config('map.inquiries')[$inquiry_id] }}
@endif
@if (isset($program_name))
対象広告名　　　　　：{{ $program_name }}
@endif
@if (isset($payment_number))
決済情報番号　　　　：{{ $payment_number }}
@endif
==============================================================
【　具体的なお問い合わせの内容　】
==============================================================
@if (isset($joined_at))
参加日時　　　　　　：{{ sprintf("%04d年%02d月%02d日 %02d時頃", $joined_at['year'], $joined_at['month'], $joined_at['day'], $joined_at['hour']) }}
@endif
{{ $inquiry_detail }}
@if (isset($mail_message))
==============================================================
【　購入・登録完了メール　】
==============================================================
{{ $mail_message }}
@endif
==============================================================
@if (isset($user_agent))
ユーザーエージェント：{{ $user_agent }}
@endif
@if (isset($ip_address))
IPアドレス　　　　　：{{ $ip_address }}
@endif
@if (isset($request_timestamp))
ブラウザの時間　　　：{{ \Carbon\Carbon::createFromTimestamp($request_timestamp,config('app.timezone'))->format('Y-m-d H:i:s') }}
@endif
@if (isset($complite_time))
問い合わせ完了時間　：{{ $complite_time }}
@endif
==============================================================
