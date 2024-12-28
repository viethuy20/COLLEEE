@extends('layouts.exchange')

@section('layout.title', $exchange_info->label.'へのポイント交換 | ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、' . $exchange_info->label . 'に交換することができます。')
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

<section class="contents">
    <h1 class="ttl_exchange">交換申請完了</h1>

    <section class="contents__box">
        <div class="contents__center__box">
            <div class="contents__center__box__text">
                <p class="done">交換申請が完了しました。</p>
                <p class="mb_15 takecare">※ギフトカードおよびギフトコードへの交換申請の場合、メールでお送りさせていただくコードをチャージ・登録することで、ポイント交換が完了します。</p>
                <p class="mb_15">登録いただいているメールアドレス宛に交換受付完了メールをお送りしました。</p>
                <p class="mb_15">受付番号は、交換申請に関するお問い合わせの際GMOポイ活のサポートにて使用いたしますので、正常に交換が完了するまで大切に保管してください。</p>
            </div>
        </div>

        <section class="contents__center__box">
            <div class="contents__center__box__text">
                <p>ギフトカードおよびギフトコードは【info@colleee.net】よりお送りさせて頂きます。メールが届かない場合には、拒否設定などをご確認下さい。念のため迷惑メールフォルダ内のチェックもお願いいたします。</p>
                <p class="thisone">ポイント交換にあたって交換手数料が発生する交換先の場合、お振込み金額は手数料を差し引いた金額となりますので、ご了承ください。</p>
                <p>ポイント交換に関するご不明点は、サポートページをご参照ください。</p>
                <p>※ギフトコードが届くまで、少々お時間をいただく場合がございます。</p>
            </div>
        </section><!--/exchange_note-->

        <!--ギフトコード-->
        <section class="contents__center__box u-mt-20">
            <div class="contents__center__box__main">
                @php
                $yen_map = $exchange_info->getYenLabelMap();
                @endphp
                @foreach ($exchange_request_list as $exchange_request)
                    @if ($exchange_info->type == App\ExchangeRequest::PEX_GIFT_TYPE)
                        @php
                            $pex_rate = config('exchange.point.'. $exchange_info->type. '.yen.rate') / config('exchange.yen_rate');
                        @endphp
                    <h3>{{ $yen_map[($exchange_request->yen * $pex_rate)].$exchange_info->unit }}ギフトコード</h3>
                    @else
                    <h3>{{ $yen_map[$exchange_request->yen].$exchange_info->unit }}ギフトコード</h3>
                    @endif
                    <p>受付番号:{{ $exchange_request->number }}</p>
                @endforeach
            </div>
        </section><!--/exchange_applied-->
        <p>{{ Tag::link('/support/', 'お客様サポートはこちら', ['class' => 'banks__auth__btn']) }}</p>
    </section><!--/exchange_applied_area-->
    @if ($exchange_info->type == App\ExchangeRequest::GOOGLE_PLAY_GIFT_TYPE)
        <div>Google Play は Google LLC の商標です。</div>
    @endif
    <div class="btn_y for_top">{{ Tag::link(route('exchanges.index'), '交換ページトップへ戻る') }}</div>
    @if ($exchange_info->type == App\ExchangeRequest::ITUNES_GIFT_TYPE)
    <div></br></br> &copy; {{ \Carbon\Carbon::now()->year }} iTunes K.K. All rights reserved.</div>
    @endif
    @if ($exchange_info->type == App\ExchangeRequest::AMAZON_GIFT_TYPE)
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
    @elseif ($exchange_info->type == App\ExchangeRequest::PSSTICKET_GIFT_TYPE)
    <dl class="caution u-mt-20">
        <dd>
            <ul>
                <li class="text-link text--14">
                「PlayStation」および「プレイステーション」は、株式会社ソニー・インタラクティブエンタテインメントの登録商標または商標です。<br />
                </li>
            </ul>
        </dd>
    </dl>
    @endif

</section><!--/contents-->
@endsection
