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

@component('elements.gift_code_form', ['exchange_info' => $exchange_info, 'exchange' => $exchange])
<p class="mb_15 text-link">
プレイステーション ストアチケット（以下、PS Storeチケット）は、コード番号を入力することでPlayStation&trade;Network（以下、PSN）の アカウントのウォレットにチャージ（入金）できるチケットです。ウォレットはPSNの有料コンテンツやサービスの購入にご利用いただけます。ただし、一部のコンテンツ（CERO Z タイトル、CERO審査予定のプレオーダータイトルなど）の購入にはウォレットをご利用いただけません。使い方および詳細は、<a href="https://www.playstation.com/redeem" class="external" target="_blank">www.playstation.com/redeem</a> をご覧ください。<br>
<br>
■プレイステーション ストアチケット（以下、PS Storeチケット）とは（発行元：株式会社ソニー・インタラクティブエンタテインメント（以下、SIE））<br>
PS Storeチケットは、コード番号を入力することで<br>
PlayStation&trade;Network（以下、PSN）の アカウントのウォレットにチャージ（入金）できるチケットです。<br>
ウォレットはPSNの有料コンテンツやサービスの購入にご利用いただけます。<br>
ただし、一部のコンテンツ（CERO Z タイトル、CERO審査予定のプレオーダータイトルなど）の購入にはウォレットをご利用いただけません。<br>
使い方および詳細は、<a href="https://www.playstation.com/redeem" class="external" target="_blank">www.playstation.com/redeem</a> をご覧ください。<br>
<br>
■PS Storeチケットの注意事項<br>
・日本国内向けアカウントのウォレットへのチャージ（入金）にのみ、ご利用いただけます。<br>
・PS StoreチケットおよびPSNのご利用には、<a href="https://www.playstation.com/legal/psn-terms-of-service/" class="external" target="_blank">PSN利用規約</a> の遵守が必要です。<br>
・アカウントに登録される個人情報は、SIEの<a href="https://www.playstation.com/legal/privacy-policy/" class="external" target="_blank">プライバシーポリシー</a> に従い取り扱います。<br>
・PS Storeチケットのご利用にあたっては、ファミリー管理者アカウント、ファミリーメンバー（大人）のアカウントまたはマスターアカウントを登録する必要があります。<br>
　これらのアカウントは、18歳以上の方のみ作成することができます。<br>
・PS Storeチケットは、返品・換金・再発行できません。<br>
　また、紛失、盗難、破損、またはコード番号の漏洩等によりお客様が被った損害に対して、SIEは責任を負いかねます。<br>
・チャージを行う前に、必ずチャージをするアカウントにお間違いがないかご確認ください。<br>
　一度チャージされた金額は他のアカウントへの移行や返金ができません。<br>
「PlayStation」および「プレイステーション」は、株式会社ソニー・インタラクティブエンタテインメントの登録商標または商標です。<br>
</p>
@endcomponent
@endsection
