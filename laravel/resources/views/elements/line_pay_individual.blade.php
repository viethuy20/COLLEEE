@php
$line_pay_exchange_info = \App\ExchangeInfo::ofNow()
    ->ofType(\App\ExchangeRequest::LINE_PAY_TYPE)
    ->first();
@endphp
<section class="exchange_individual">
    <div class="selectedbank">
        <h1 class="ttl_exchange u-mb-20">{{ $line_pay_exchange_info->label }}への交換について</h1>
        <p>LINE Pay残高は、LINE Payが使えるお店でご利用いただけることはもちろん、PayPay加盟店（一部を除くユーザースキャン方式の加盟店）でもご利用いただけます。また、LINEの友だちへの送金や銀行口座へのお振り込み、セブン銀行ATMにてご出金いただくこともできます。</p>
        <p class="text-link no-before text--14">※LINE Pay残高の詳しいご利用方法については、{{ Tag::link(config('url.line_pay.promotion'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご確認ください。</p>
        <dl class="caution">
            <dt><span class="icon-attention"></span>&nbsp;{{ $line_pay_exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
            <dd>
                <ul class="note">
                    <li class="text-link no-before text--14">LINE Pay残高のお受け取りには、LINE Payアカウントが必要です。詳細およびご登録については{{ Tag::link(config('url.line_pay.sign_up'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご参照ください。</li>

                    <li>LINE Payには、2種類のアカウントタイプがあり、アカウントの種類によって利用できる機能や保有できるLINE Pay残高の上限が異なります。</li>
                    <p class="text-link no-before text--14">※それぞれのアカウントタイプの概要と機能の詳しいご説明については、{{ Tag::link(config('url.line_pay.account_type'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご確認ください。</p>
                    <p class="text-link no-before text--14">※保有できるLINE Pay残高上限の詳しいご説明については、{{ Tag::link(config('url.line_pay.limit'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご確認ください。</p>

                    
                    <li>LINE Pay残高のお受け取り時に、あらかじめ保有するLINE Pay残高と受け取る残高の合計額が、保有できる残高の上限を超える場合は、全額を受け取ることができませんのでご注意ください。</li>
                    <li>LINE Pay残高のお受け取りには、お客様のLINE Payナンバーの情報を提供いただく必要がございます。</li>
                    <p class="text-link no-before text--14">※LINE Pay ナンバーの確認方法については、{{ Tag::link(config('url.line_pay.confirm_number'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご確認ください。</p>
                    <p>※誤ったLINE Pay ナンバーを入力すると、残高をお受け取りいただけませんのでご注意ください。</p>


                    <li>手続き完了前に退会されますと無効となります。</li>
                    <li>一度交換したポイントのキャンセルはできませんので、あらかじめご了承ください。</li>
                </ul>
            </dd>
            <dt><span class="icon-attention"></span>&nbsp;手数料について</dt>
            <dd>
                <ul class="note">
                    <li>LINE Payへのポイント交換の際、50円（50ポイント分）の交換手数料が発生いたします。あらかじめご了承ください。</li>
                    <br>
                    <h4>（例）交換額1,000円の場合</h4>
                    <p>&nbsp;&nbsp;交換ポイント：1,000P</p>
                    <p>&nbsp;&nbsp;交換手数料：50P</p>
                    <p>&nbsp;&nbsp;合計交換ポイント：1,050P</p>
                </ul>
            </dd>

        </dl>
    </div><!--/contentsbox-->
</section><!--/exchange_individual-->
