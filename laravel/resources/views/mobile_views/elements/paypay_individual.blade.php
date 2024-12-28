@php
$paypay_exchange_info = \App\ExchangeInfo::ofNow()
    ->ofType(\App\ExchangeRequest::PAYPAY_TYPE)
    ->first();
@endphp

<section class="selectedbank">
    <h3 class="ttl_exchange u-mb-20">PayPayポイントについて</h3>

    <p class="ta_c text-link text--14 u-text-ac">
        PayPayは、スマホで利用できるQR決済サービスです。アプリをダウンロード・登録するだけで誰でも無料で使えます。<br>
        PayPayポイントは、1ポイント＝1円として全国のPayPayが使えるお店で利用することができます。
    </p>
    <dl class="caution u-mt-20">
        <dt><span class="icon-attention"></span>&nbsp;PayPayポイントへのポイント交換にあたっての注意事項</dt>
        <dd><ul class="note">
            
            <li class="text--14">1ポイント=1円分としてPayPayの加盟店でご利用いただけます。</li>
            <li class="text--14">PayPayポイントは出金や譲渡はできません。</li>
            <li class="text--14">PayPay公式ストアでも利用可能です。</li>
            <li class="text--14">交換申請後の変更やキャンセルはお受けできません。</li>
            <li class="text--14">詳細はこちらから<p class="ta_c text-link text--14 u-text-ac">{{ Tag::link(config('paypay.help_url'), config('paypay.help_url'), ['target' => '_blank', 'class' => 'icon-arrowr external']) }}</p></li>
        </ul></dd>
    </dl>
    <dl class="u-mt-20">
        <dt class="eins">最低交換pt</dt>
        <dd>
            @if ($paypay_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($paypay_exchange_info->message_body))
            <span>{{ number_format($paypay_exchange_info->min_point) }}</span>
            @else
            {{ number_format($paypay_exchange_info->min_point) }}
            @endif
            ポイント～
        </dd>
        <dt class="eins u-mt-small">交換日</dt>
        <dd>{{ $paypay_exchange_info->exchange_at }}</dd>
        <dt class="eins u-mt-small">交換レート</dt>
        <dd>
            @if ($paypay_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($paypay_exchange_info->message_body))
            <span>{{ number_format($paypay_exchange_info->chargePoint(100)) }}</span>
            @else
            {{ number_format($paypay_exchange_info->chargePoint(100)) }}
            @endif
            ポイント→{{ number_format($paypay_exchange_info->exchangeAmount(100)).$paypay_exchange_info->unit }}
        </dd>
        @if (isset($paypay_exchange_info->message_body))
        <dd class="info">{!! nl2br(e($paypay_exchange_info->message_body)) !!}</dd>
        @endif
    </dl>
</section><!--/exchange_individual-->
