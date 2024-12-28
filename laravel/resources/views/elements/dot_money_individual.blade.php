@php
$dot_money_exchange_info = \App\ExchangeInfo::ofNow()
    ->ofType(\App\ExchangeRequest::DOT_MONEY_POINT_TYPE)
    ->first();
@endphp
<section class="exchange_individual">
    <div class="selectedbank">
        <h1 class="ttl_exchange u-mb-20">{{ $dot_money_exchange_info->label }}への交換について</h1>

        <p class="text-link">
            {{ Tag::link(route('dot_money.setting'), $dot_money_exchange_info->label.'のログインはこちら', ['target' => '_blank', 'class' => 'icon-arrowr external']) }}
        </p>

        <dl class="caution">
            <dt><span class="icon-attention"></span>&nbsp;{{ $dot_money_exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
            <dd><ul class="note">
                <li>Amebaの会員IDをお持ちでない場合、GMOポイ活IDを使った口座開設・ログインが可能です。（開設の際には{{ $dot_money_exchange_info->label }}が案内している規約などをよくお読みいただきますようお願い申し上げます。） </li>
                <li>ドットマネーで「マネーをつかう」からポイント・ギフト券などに交換する際にSMS認証が必要です。</li>
                <li>{{ $dot_money_exchange_info->label }}はGMOポイ活IDと紐付いた口座に交換したポイントが振り込まれるため、既に口座をお持ちの場合は「GMOポイ活IDに紐付いた口座」と「開設済み口座」のID連携が必要になります。詳しくは{{ $dot_money_exchange_info->label }}の設定画面やヘルプをご確認ください。</li>
                <li>
                    {{ $dot_money_exchange_info->label }}には有効期限があります。有効期限を過ぎると失効いたします。失効したマネーはご利用いただけません。なお、失効予定は{{ $dot_money_exchange_info->label }}通帳にて、ご確認いただけます。マネーの獲得方法によって失効までの期間が異なりますので、ご注意ください。<br />
                    <p class="text-link">{{ Tag::link(config('url.d_money_help2'), '詳細はこちら', ['target' => '_blank', 'class' => 'icon-arrowr external']) }}</p>
                </li>
                <li>
                    {{ $dot_money_exchange_info->label }}への交換の前に必ず利用規約をご覧ください。 <br />
                    <p class="text-link">{{ Tag::link(config('url.d_money_term'), '詳細はこちら', ['target' => '_blank', 'class' => 'icon-arrowr external']) }}</p>
                </li>
                <li>{{ $dot_money_exchange_info->label }}への交換申請後、交換完了までにAmebaを退会した場合、マネーは付与されませんので、ご注意ください。</li>
                <li>一度交換したポイントのキャンセルはできませんので、あらかじめご了承ください。</li>
                <li>同一人物が複数IDにてポイント交換した場合、当社が不正な申込みと判断した場合、交換手続きを無効とさせて頂きます。尚、その際にはポイントの返却はできませんので、あらかじめご了承ください。</li>
                <li>口座番号の記入間違いや口座解約等の理由により、{{ $dot_money_exchange_info->label }}への交換ができない場合、交換依頼されたポイントはお返しできませんのであらかじめ御了承ください。</li>
            </ul></dd>
        </dl>

        <dl class="u-mt-20">
            <dt class="eins">最低交換pt</dt>
            <dd>
                @if ($dot_money_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($dot_money_exchange_info->message_body))
                <span>{{ number_format($dot_money_exchange_info->min_point) }}</span>
                @else
                {{ number_format($dot_money_exchange_info->min_point) }}
                @endif
                ポイント～
            </dd>
            <dt class="eins u-mt-small">交換日</dt>
            <dd>{{ $dot_money_exchange_info->exchange_at }}</dd>
            <dt class="eins u-mt-small">交換レート</dt>
            <dd>
                @if ($dot_money_exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($dot_money_exchange_info->message_body))
                <span>{{ number_format($dot_money_exchange_info->chargePoint(100)) }}</span>
                @else
                {{ number_format($dot_money_exchange_info->chargePoint(100)) }}
                @endif
                ポイント→{{ number_format($dot_money_exchange_info->exchangeAmount(100)).$dot_money_exchange_info->unit }}
            </dd>
            @if (isset($dot_money_exchange_info->message_body))
            <dd class="info">{!! nl2br(e($dot_money_exchange_info->message_body)) !!}</dd>
            @endif
        </dl>
    </div><!--/contentsbox-->
</section><!--/exchange_individual-->
