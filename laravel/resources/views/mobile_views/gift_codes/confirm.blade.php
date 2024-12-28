@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', $exchange_info->label.'へのポイント交換 | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、' . $exchange_info->label . 'に交換することができます。')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    lockForm('ExchangeForm');
});
//-->
</script>
@endsection
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
@endphp
@section('layout.breadcrumbs')
<section class="header__breadcrumb">
    <ol>
        @foreach($arr_breadcrumbs as $item)
            <li>
                <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
            </li>
        @endforeach
        <li>
            {{ Tag::link(route('exchanges.index'), 'ポイント交換') }}
        </li>
        <li>
            {{ $exchange_info->label }}
        </li>
    </ol>
</section>
@endsection

@section('layout.content')
<h1 class="ttl_exchange">交換内容確認</h1>
<section class="selectedbank">
    {{ Tag::formOpen(['url' => route('gift_codes.store', ['type' => $exchange_info->type]), 'id' => 'ExchangeForm', 'class' => 'banks__form']) }}
    @csrf    
    @if ($exchange_info->type == App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE)
        <h2 class="fontR">{{ $exchange_info->label }}</h2>
        @else
        <h2>{{ $exchange_info->label }}</h2>
        @endif

        <table>
            <tr>
                <th><span>交換ポイント</span></th>
                <td><p class="putted">{{ number_format($exchange['point']) }}&nbsp;ポイント</p></td>
            </tr>
            <tr>
                <th><span>ギフトコード</span></th>
                <td>
                    @php
                    $yen_map = $exchange_info->getYenLabelMap();
                    @endphp
                    @foreach($exchange['yen_total_map'] as $yen => $total)
                    <p class="putted">{{ $yen_map[$yen].$exchange_info->unit }}×{{ $total }}&nbsp;</p>
                    @endforeach
                </td>
            </tr>
        </table>

        @if ($exchange_info->type == App\ExchangeRequest::ITUNES_GIFT_TYPE)
        <ul class="u-mt-20 u-mb-20">
            <li class="u-mb-10">・ギフトカードはEメールで届きます。</li>
            <li class="u-mb-10">・{{ implode('円、', $yen_map).'円' }}の金額から選べます。</li>
            <li class="u-mb-10">
                <strong>ギフトコードが届かない場合について</strong>
                <ol>
                    <li class="u-mb-10">
                        1.迷惑メールフォルダ、ゴミ箱フォルダに入ってしまう場合があります。
                        メールフィルタなどが作用して迷惑メールフォルダ、ゴミ箱フォルダに入ってしまう場合があります。ギフトコードにつきましては info@colleee.net から配信させていただいております(GMOポイ活にご登録のメールアドレスへ送信されます)。 受信フォルダ以外に info@colleee.net からのメールが届いていないかどうかのご確認をお願いいたします。
                    </li>
                    <li class="u-mb-10">
                        2.受信拒否設定が作用している場合があります。
                        受信拒否設定を特に行っていない場合も、通信会社の迷惑メールフィルタなどが作用してメールが届かない場合がございます。 info@colleee.net をドメイン・アドレス指定での受信設定にしていただくと受信可能となります。 ギフトコード記載のメールを再送ご希望の場合は、お問い合わせフォームよりGMOポイ活サポートへお問い合わせください。
                    </li>
                </ol>
            </li>

            <li class="u-mb-20">
                <strong>ギフトコードが届かない場合の注意事項</strong><br />
                ギフトコード記載メールの再送可能期間について<br />
                初回のギフトコード送信から60日以内<br />
                60日を超過した場合、ギフトコード記載メールの再送はできません。<br />
                交換手続き後にメールが届かない場合は、お早めにご対応ください。<br />
                ※ギフトコードの有効期限は、上記の「再送可能期間」とは異なります。<br />
                各ギフトコードに設定されている有効期限をご確認ください。
            </li>
            <li class="u-mb-10">
                ・日本国内のすべてのApple Storeでの購入のほか、Apple Store App、apple.com、App Store、iTunes Store、Apple Music、Apple TV+、Apple Books、Apple Arcade、iCloud、Apple OneなどのAppleのサービスで利用できます。<br/>
                ・Apple Storeで購入の際には、未使用のギフトカードをApple Storeにお持ちください。<br/>
                ・Appleアカウントでのオンライン購入に利用するには、apple.com/redeemにアクセスして、カードの金額を残高にチャージしてください。<br/>
                ・そのほかのお支払いには利用できません。<br/>
                ・Apple Gift Cardの返品や払い戻しはできません。諸条件が適用されます。
            </li>
            <li>
                ■&nbsp;Apple Gift Cardの利用規約<br/>
                日本国内におけるAppleからの購入のみに利用できます。<br/>
                Apple Gift Cardに関するお問い合わせは、support.apple.com/giftcard をご覧いただくか、0120-277-535までお電話ください。<br/>
                Apple製品取扱店では利用できません。<br/>
                また、法律で定められている場合を除き、現金との引換、転売、払い戻し、または商品交換はできません。
                Appleは、Apple Gift Cardの不正使用に対して責任を負いません。諸条件が適用されます。apple.com/jp/go/legal/gc をご覧ください。<br/>
                これらの条件が法的権利に影響を及ぼすことはありません。<br/>
                Apple Gift Cardの有効期限はありません。<br/>
                発行：iTunes株式会社
            </li>
            <li>&copy; {{ \Carbon\Carbon::now()->year }} iTunes K.K. All rights reserved.</li>
        </ul>
        @endif

        {{ Tag::formSubmit(\App\Http\Middleware\Phone::authenticate() ? '次へ' : '認証', ['class' => 'banks__auth__btn__pink u-mt-20']) }}
    {{ Tag::formClose() }}
</section><!--/detailinput-->
@if ($exchange_info->type == App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE)
    <div style="margin: 0 0 15px 15px;">Google Play は Google LLC の商標です。</div>
@endif

<section><div class="btn_y">{{ Tag::link($exchange_info->url, '戻る') }}</div></section>

@if ($exchange_info->type == App\ExchangeRequest::AMAZON_GIFT_TYPE)
        <section class="inner">
            <dl class="caution u-mt-20">

                <dd>
                    <ul>
                        <li class="text-link text--14">
                            <br/>
                            本キャンペーンはGMO NIKKO株式会社による提供です。<br />
                            本キャンペーンについてのお問い合わせはAmazonではお受けしておりません。 <br />
                            {{ Tag::link(route('inquiries.index', ['inquiry_id' => 4]), 'GMOポイ活問い合わせフォーム') }}までお願いいたします。
                        </li>
                        <li class="text--14"> Amazon、Amazon.co.jpおよびそれらのロゴはAmazon.com, Inc.またはその関連会社の商標です。 </li>
                    </ul>
                </dd>
            </dl>
        </section>
@elseif ($exchange_info->type == App\ExchangeRequest::PONTA_GIFT_TYPE)
        <section class="inner">
            <dl class="caution u-mt-20">
                <dd>
                    <ul>
                        <li class="text-link text--14">
                            <br/>
                            「Ponta」は、株式会社ロイヤリティ マーケティングの登録商標です。<br />
                            「Pontaポイント コード」は、株式会社ロイヤリティ マーケティングとの発行許諾契約により、株式会社NTTカードソリューションが発行するサービスです。<br />
                        </li>
                    </ul>
                </dd>
            </dl>
        </section>
@elseif ($exchange_info->type == App\ExchangeRequest::PSSTICKET_GIFT_TYPE)
        <section class="inner">
            <dl class="caution u-mt-20">
                <dd>
                    <ul>
                        <li class="text-link text--14">
                            <br/>
                            「PlayStation」および「プレイステーション」は、株式会社ソニー・インタラクティブエンタテインメントの登録商標または商標です。<br />
                        </li>
                    </ul>
                </dd>
            </dl>
        </section>
@endif

@endsection
