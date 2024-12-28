━━━━━━━━━━━━━━━━━━━━
@if (isset($user_id))
ID:{{ $user_id }}
@endif
@if (isset($name))
名前:{{ $name }}
@endif
@if (isset($nickname))
ニックネーム:{{ $nickname }}
@endif
退会理由:
@if (isset($withdrawal_reasons))
@foreach($withdrawal_reasons as $key => $label)
{{ $label }}
@endforeach
@endif
━━━━━━━━━━━━━━━━━━━━

文面未定

━━━━━━━━━━━━━━━━━━━━━