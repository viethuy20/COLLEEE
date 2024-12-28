@hasSection ('title')
━━━━━━━━━━━━━━━━━━━━
@yield('title')

━━━━━━━━━━━━━━━━━━━━
@endif

@yield('content')

━━━━━━━━━━━━━━━━
GMOポイ活
{!! route('website.index') !!}

■お問い合わせ・よくある質問
{!! route('website.index') !!}/support/

■運営会社：GMO NIKKO株式会社
{!! config('url.gmo_nikko') !!}
━━━━━━━━━━━━━━━━