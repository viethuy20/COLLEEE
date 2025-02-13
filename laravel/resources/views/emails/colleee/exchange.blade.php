@extends('emails.layouts.default')

@section('content')
交換受付が完了しました。

━━━━━━━━━━━━━━━━
受付番号:{{ $exchange_request_number }}
━━━━━━━━━━━━━━━━

受付番号は交換申請に関するお問い合わせに使用いたしますので、正常に交換が完了するまで大切に保管してください。

ポイント交換にあたって交換手数料が発生する交換先の場合、お振込み金額は手数料を差し引いた金額となりますので、ご了承ください。

ポイント交換に関するご不明点は、サポートページをご参照ください。
▼お客様サポートはこちら
{!! route('website.index') !!}/support/
@endsection