@php
$kdol_exchange_info = \App\ExchangeInfo::ofNow()
    ->ofType(\App\ExchangeRequest::KDOL_TYPE)
    ->first();
@endphp

<section class="selectedbank">
    <h3 class="ttl_exchange u-mb-20">KDOLのハートへのポイント交換について</h3>

    <dl class="caution u-mt-20">
        <dt><span class="icon-attention"></span>&nbsp;KDOLサービスについて</dt>
        <li class="text--14">KDOLはグローバルKーPOPのファン投票サービスです。</li>
        <li class="text--14">アプリをダウンロードして登録するだけで、誰でも無料で利用できます。</li>
        <li class="text--14">ハートを貯めて応援するアイドルの広告をプレゼントすることができます。</li>
        </ul></dd>
    </dl>
    <dl class="caution u-mt-20">
        <dt><span class="icon-attention"></span>&nbsp;KDOLのハートへのポイント交換にあたっての注意事項</dt>
        <li class="text--14">GMOポイ活ポイント1ポイントあたり100ハートに交換されます。</li>
        <li class="text--14">交換されたハートはKDOLサービス内でのみ利用可能で、交換申請後の変更やキャンセルはできません。</li>
        </ul></dd>
    </dl>
    <dl class="u-mt-20">
        <dt class="eins">最低交換pt</dt>
        <dd>
            @if ($kdol_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($kdol_exchange_info->message_body))
            <span>{{ number_format($kdol_exchange_info->min_point) }}</span>
            @else
            {{ number_format($kdol_exchange_info->min_point) }}
            @endif
            ポイント～
        </dd>
        <dt class="eins u-mt-small">交換日</dt>
        <dd>{{ $kdol_exchange_info->exchange_at }}</dd>
        <dt class="eins u-mt-small">交換レート</dt>
        <dd>
            @if ($kdol_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($kdol_exchange_info->message_body))
            <span>{{ number_format($kdol_exchange_info->chargePoint(100)) }}</span>
            @else
            {{ number_format($kdol_exchange_info->chargePoint(100)) }}
            @endif
            ポイント→{{ number_format($kdol_exchange_info->exchangeAmount(100)).$kdol_exchange_info->unit }}
        </dd>
        @if (isset($kdol_exchange_info->message_body))
        <dd class="info">{!! nl2br(e($kdol_exchange_info->message_body)) !!}</dd>
        @endif
    </dl>
</section><!--/exchange_individual-->
