@php
$d_point_exchange_info = \App\ExchangeInfo::ofNow()
    ->ofType(\App\ExchangeRequest::D_POINT_TYPE)
    ->first();
@endphp

<section class="selectedbank">
    <h3 class="ttl_exchange u-mb-20">dポイントへの交換について</h3>

    <p class="ta_c text-link text--14 u-text-ac">
    ドコモのケータイ電話をご購入いただく際やケータイの利用料金はもちろん、ファーストフードやコンビニなど街のお店でもご利用いただけるポイント、それがdポイント！
    </p>
    <dl class="caution u-mt-20">
        <dt><span class="icon-attention"></span>&nbsp;dポイントへのポイント交換にあたっての注意事項</dt>
        <dd><ul class="note">
            <li class="text--14">dポイントに交換するには、dポイントクラブへの登録が必要です。詳細およびご登録についてはこちらをご参照ください。
            <p class="ta_c text-link text--14 u-text-ac">{{ Tag::link(config('url.d_point_help1'), config('url.d_point_help1'), ['target' => '_blank', 'class' => 'icon-arrowr external']) }}</p>
            </li>
            <li class="text--14">お申込み完了後、dポイントに即時交換されます（数日かかる場合があります）</li>
            <li class="u-mb-20 text--14">ポイント有効期限は獲得月から48ヶ月後の月末までとなります。</li>
            <li class="text--14">dポイントへの交換が完了する前にdポイントクラブを退会された場合、GMOポイ活ポイントは失効となりますのでご注意ください。</li>
            <li class="text--14">一度交換したポイントのキャンセルはできませんので、あらかじめご了承ください。</li>
            <li class="text--14">dポイントクラブと連携後はdポイントクラブ会員番号をGMOポイ活画面から変更することが出来ません。dポイントクラブを退会して再度会員になった等の理由で番号変更行う場合は連携解除手続きを行いますので、GMOポイ活お客様サポートまでお問い合わせ下さい。</li>
            <li class="text--14">その他dポイントの注意事項などがこちら<p class="ta_c text-link text--14 u-text-ac">{{ Tag::link(config('url.d_point_help2'), config('url.d_point_help2'), ['target' => '_blank', 'class' => 'icon-arrowr external']) }}</p></li>
        </ul></dd>
    </dl>
    <dl class="u-mt-20">
        <dt class="eins">最低交換pt</dt>
        <dd>
            @if ($d_point_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($d_point_exchange_info->message_body))
            <span>{{ number_format($d_point_exchange_info->min_point) }}</span>
            @else
            {{ number_format($d_point_exchange_info->min_point) }}
            @endif
            ポイント～
        </dd>
        <dt class="eins u-mt-small">交換日</dt>
        <dd>{{ $d_point_exchange_info->exchange_at }}</dd>
        <dt class="eins u-mt-small">交換レート</dt>
        <dd>
            @if ($d_point_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($d_point_exchange_info->message_body))
            <span>{{ number_format($d_point_exchange_info->chargePoint(100)) }}</span>
            @else
            {{ number_format($d_point_exchange_info->chargePoint(100)) }}
            @endif
            ポイント→{{ number_format($d_point_exchange_info->exchangeAmount(100)).$d_point_exchange_info->unit }}
        </dd>
        @if (isset($d_point_exchange_info->message_body))
        <dd class="info">{!! nl2br(e($d_point_exchange_info->message_body)) !!}</dd>
        @endif
    </dl>
</section><!--/exchange_individual-->
