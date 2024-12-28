━━━━━━━━━━━━━━━━━━━━
@if (isset($user_id))
ID:{{ $user_id }}
@endif
IP:{{ $ip }}
UA:{{ $ua ?? '' }}
日時: {{ $created_at->format('Y-m-d H:i:s') }}
━━━━━━━━━━━━━━━━━━━━

{!! $body !!}

━━━━━━━━━━━━━━━━━━━━━