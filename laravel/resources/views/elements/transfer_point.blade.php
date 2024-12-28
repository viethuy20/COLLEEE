@php
    $user = Auth::user();
    $max_yen = $exchange_info->chargeYen($user->max_exchange_point);
    $yen_map = $exchange_info->getYenPointLabelMap($max_yen);
@endphp
<div>
    <tr class="remove_border_bottom">
        <th><span>交換ポイント</span></th>
        <td>
            <div class="putted_area">
                @if (!empty($yen_map))
                    <script type="text/javascript"><!--
                        var setTransferAmmount = function (transferYen) {
                            var yen = transferYen.val();
                            var yen2 = Math.max(yen - {{ $charge }}, 0);

                            $('#TransferAmmount').text((yen + '').replace(/([0-9]+?)(?=(?:[0-9]{3})+$)/g, '$1,') + '円');
                            $('#TransferAmmountResult').text((yen2 + '').replace(/([0-9]+?)(?=(?:[0-9]{3})+$)/g, '$1,'));
                        };
                        $(function () {
                            //
                            var transferYen = $('#TransferYen');
                            setTransferAmmount(transferYen);
                            transferYen.on('change', function (event) {
                                setTransferAmmount($(this));
                            });
                        });
                        //-->
                    </script>
                    <div class="banks__form__select">
                        <div class="banks__form__select__inner">
                            {{ Tag::formSelect('yen', [0 => '選択してください'] + $yen_map, $transfer['yen'] ?? 0, ['class' => 'muchpoint', 'id' => 'TransferYen']) }}
                        </div>
                    </div>
                    @if ($errors->has('yen'))
                        <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('yen') }}</p>
                    @endif

                    <p class="subtraction mb_5"><span id="TransferAmmount"></span>から手数料<span>{{ number_format($charge) }}円</span>を減算致します。
                    </p>
                    <div class="total mb_10"><p class="transfer">お振込金額</p>
                        <p class="amount" id="TransferAmmountResult"></p></div>
                    <p class="error_message"><span class="icon-attention"></span>交換申請ポイントから手数料を減算してのお振込となります。
                    </p>
                @else
                    <p class="error_message"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p>
                @endif
            </div><!--/putted_area-->
        </td>
    </tr>
    @if (!empty($yen_map))
        <tr>
            <td colspan="2">{{ Tag::formSubmit('入力情報の確認', ['class' => 'banks__auth__btn u-mt-remove']) }}</td>
        </tr>
    @endif
</div>
