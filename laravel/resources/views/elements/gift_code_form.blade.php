@php
$user = Auth::user();
$max_yen = $exchange_info->chargeYen($user->max_exchange_point);
if ($exchange_info->type == App\ExchangeRequest::PEX_GIFT_TYPE || $exchange_info->type == App\ExchangeRequest::KDOL_TYPE) {
    $exPexRate = config('exchange.point.'. $exchange_info->type. '.yen.rate') / config('exchange.yen_rate');
    $yen_map = $exchange_info->getYenLabelMap($max_yen * $exPexRate);
} else {
    $yen_map = $exchange_info->getYenLabelMap($max_yen);
}
@endphp

<section class="contents">
    <h1 class="ttl_exchange{!! ($exchange_info->type == App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE) ? ' fontR' : '' !!}">{{ $exchange_info->label }}へのポイント交換</h1>

    <section class="selectedbank">
        <h2{!! ($exchange_info->type == App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE) ? ' class="fontR"' : '' !!}>{{ $exchange_info->label }}への交換について</h2>
        <div class="m_20">
            {{ $slot }}

            <dl class="u-mt-20">
                <dt class="eins">最低交換pt</dt>
                <dd>
                    @if ($exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($exchange_info->message_body))
                    <span>{{ number_format($exchange_info->min_point) }}</span>
                    @else
                    {{ number_format($exchange_info->min_point) }}
                    @endif
                    ポイント～
                </dd>
                <dt class="eins u-mt-small">交換日</dt>
                <dd>{{ $exchange_info->exchange_at }}</dd>
                <dt class="eins u-mt-small">交換レート</dt>
                <dd>
                    @if ($exchange_info->status == \App\ExchangeInfo::SUCCESS_STATUS && isset($exchange_info->message_body))
                    <span>{{ number_format($exchange_info->chargePoint(100)) }}</span>
                    @else
                    {{ number_format($exchange_info->chargePoint(100)) }}
                    @endif
                    ポイント→{{ number_format($exchange_info->exchangeAmount(100)).$exchange_info->unit }}
                </dd>
                @if (isset($exchange_info->message_body))
                <dd class="info">{!! nl2br(e($exchange_info->message_body)) !!}</dd>
                @endif
            </dl>
        </div><!--/contentsbox-->
    </section><!--/exchange_individual-->

    <section class="selectedbank">
        <h2{!! ($exchange_info->type == App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE) ? ' class="fontR"' : '' !!}>{{ $exchange_info->label }}への交換申請</h2>

        @if (empty($yen_map))
        <div class="contentsbox"><p class="error_message"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p></div>
        @else
        <style type="text/css">
        .selectedbank button {background-color: transparent;border: none;cursor: pointer;outline: none;padding: 0;appearance: none;-moz-appearance: none;-webkit-appearance: none;}
        .selectedbank button img {width:16px;height:16px; vertical-align:middle; margin-left:5px;}
        .selectedbank button[class=GiftCodeButton] {display:block;width:30%;box-shadow: 0 2px 0 0 #dedede;background-color:#f39800;font-size:16px;padding: 5px 10px;border-radius:6px;float:left;margin-right:5px;margin-bottom:10px;height:35px;color:#333333;}
        .selectedbank button[class=GiftCodeButton]:disabled {background-color:#EEE;color:#808080;}
        #GiftCodeClearButton {background-color:#ccc;display:block;width:30%;font-size:16px;padding: 5px 10px;border-radius:6px;float:left;margin-right:5px;margin-bottom:10px;height:35px;color:#333333;}
        .selectedbank .detail_area { display: flex; gap: 1.6rem; }
        .selectedbank .detail_area .title_code { width: 33.33%; border: 1px solid #b9b9b9; background: #f9f9f9; border-radius: 6px; display: flex; flex-direction: column; justify-content: center; text-align: center; }
        .selectedbank .detail_area .putted_area {width: 66.66%;}
        </style>

        <script type="text/javascript"><!--
        var exRate = {{ $exchange_info->yen_to_point_rate }};
        var exPexRate = {{ config('exchange.point.'. $exchange_info->type. '.yen.rate') / config('exchange.yen_rate') }};
        var maxYen = {{ $max_yen }};
        var yenLabelMap = {};
        @foreach ($yen_map as $yen => $label)
        yenLabelMap[{{ $yen }}] = '{{ $label }}';
        @endforeach
        var clearCart = function() {
            $('#GiftCodeYen').val('');
            setCart();
        };

        var setCart = function() {
            var totalYen = 0;
            var yenMap = [];

            var p = $('#GiftCodeYen').val();
            if (p) {
            var yenList = p.split(",");
                yenList.sort(function(a,b){
                    if( a < b ) return -1;
                    if( a > b ) return 1;
                    return 0;
                });

                for (var i = 0; i < yenList.length; ++i) {
                    var yen = parseInt(yenList[i]);
                    @if ($exchange_info->type == App\ExchangeRequest::PEX_GIFT_TYPE || $exchange_info->type == App\ExchangeRequest::KDOL_TYPE)
                    totalYen = totalYen + yen / exPexRate;
                    @else
                    totalYen = totalYen + yen;
                    @endif
                    // 上限を超えていた場合
                    if (maxYen < totalYen) {
                        clearCart();
                        break;
                    }
                    if (yenMap[yen]) {
                        yenMap[yen] = yenMap[yen] + 1;
                    } else {
                        yenMap[yen] = 1;
                    }
                }
            }

            var totalPoint = 0;
            var unit ='{{ $exchange_info->unit }}';
            var cart = $('#GiftCodeCart');
            cart.empty();
            for (var yen in yenMap) {
                totalPoint = totalPoint + (Math.floor(yen * exRate) * yenMap[yen]);
                var label = yenLabelMap[yen] + unit + '×' +  yenMap[yen];
                cart.append('<li>' + label + '</li>');
            }
            @if ($exchange_info->type == App\ExchangeRequest::PEX_GIFT_TYPE || $exchange_info->type == App\ExchangeRequest::KDOL_TYPE)
                totalPoint = totalPoint / exPexRate;
                $('#GiftCodeUsePoint').html(totalPoint.toLocaleString());
            @else
                $('#GiftCodeUsePoint').html(totalPoint.toLocaleString());
            @endif
            var defYen = maxYen - totalYen;
            $(".GiftCodeButton").each(function(){

                var yen = parseInt($(this).attr('forYen'));
                @if ($exchange_info->type == App\ExchangeRequest::PEX_GIFT_TYPE || $exchange_info->type == App\ExchangeRequest::KDOL_TYPE)
                $(this).attr('disabled', (yen / exPexRate > defYen));                    
                @else
                $(this).attr('disabled', (yen > defYen)); 
                @endif
            });

            $('#GiftCodeSend').attr('disabled', (totalYen <= 0));
        };
        $(function(){
            //
            $(".GiftCodeButton").on('click', function(event) {
                var yen = $('#GiftCodeYen');

                var yenList = (yen.val() != '') ? yen.val().split(",") : [];
                yenList.push($(this).attr('forYen'));
                yen.val(yenList.join(','));

                setCart();
            });
            $("#GiftCodeClearButton").on('click', function(event) {
                clearCart();
            });
            setCart();
        });
        //-->
        </script>

        {{ Tag::formOpen(['url' => isset($point_confirm_route)? route($point_confirm_route, ['type' => $exchange_info->type]):route('gift_codes.confirm', ['type' => $exchange_info->type])]) }}
        @csrf    
        {{ Tag::formHidden('yens', old('yens', $exchange['yens'] ?? ''), ['id' => 'GiftCodeYen']) }}
            {{ Tag::formHidden('number', old('number', $exchange['number'] ?? '')) }}
            {{ Tag::formHidden('profileIdentifier', old('profileIdentifier', $exchange['profileIdentifier'] ?? '')) }}
            <div class="detail_area">
                <div class="title_code">
                    <h3>交換ポイント</h3>
                </div>
                <div class="putted_area">
                    <h3>消費ポイント</h3>
                    <p id="GiftCodeUsePoint"></p>
                    <h3 class="u-mt-small">カート</h3>
                    <ul id="GiftCodeCart"></ul>
                    @if($exchange_info->type == App\ExchangeRequest::KDOL_TYPE)
                    <h3>ハート</h3>
                    @else
                    <h3>ギフトコード</h3>
                    @endif
                    @foreach ($yen_map as $yen => $label)
                        {{ Tag::formButton($label.Tag::image('/images/ico-plus.svg', '+'), ['class' => 'GiftCodeButton', 'forYen' => $yen]) }}
                    @endforeach
                    {{ Tag::formButton('クリア', ['id' => 'GiftCodeClearButton']) }}
                    @if ($errors->has('yens'))
                    <p class="error_message clearboth"><span class="icon-attention"></span>{{ $errors->first('yens') }}</p>
                    @endif
                </div><!--/putted_area-->
            </div><!--/detail_area-->
            {{ Tag::formSubmit('入力情報の確認', ['class' => 'banks__auth__btn', 'id' => 'GiftCodeSend', 'disabled' => 'true']) }}
        {{ Tag::formClose() }}
        @endif
    </section><!--/detailinput-->

    <section><div class="btn_y">{{ Tag::link(route('exchanges.index'), '戻る') }}</div></section>
</section><!--/contents-->
