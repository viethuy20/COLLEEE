@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

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
<dl class="caution">
    <dt>●{{ $exchange_info->label }}とは</dt>
    <dd class="u-mb-10"><ul class="trademark">
        <li class="no-before text--14">受け取った{{ $exchange_info->label }}を、WAONポイントへ交換（登録）することができる電子ギフトです。</li>
        <li class="no-before text--14">交換サイトにてWAONポイントへ交換（登録）後、ダウンロード（受取り）期限内に専用端末等でダウンロード（受取り）を行うことで、WAONカードまたはモバイルWAONで受け取りできます。</li>
        <li class="no-before text--14">ダウンロード（受取り）後のWAONポイントは、WAON（電子マネー）に交換（「ポイントチャージ」）できます。</li>
        <li class="text-link no-before text--14">{{ $exchange_info->label }}の詳細は{{ Tag::link(config('url.ntt_waon'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}こちら</a>をご覧ください。</li>
        <li class="no-before text--14">※WAONポイントを受け取るには、ご自身で事前にWAONカードまたはモバイルWAONをご用意いただく必要がございます。</li>
        <li class="text-link no-before text--14">お持ちでない方は{{ Tag::link(config('url.waon_help1'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。</li>
    </ul></dd>
    <dt>●WAONポイントとは</dt>
    <dd class="u-mb-10"><ul class="trademark">
        <li class="no-before text--14">WAON（電子マネー）に交換（「ポイントチャージ」）することで、お買物などに利用できるポイントサービスです。</li>
        <li class="no-before text--14">WAONポイントには有効期限があります。</li>
        <li class="text-link no-before text--14">WAONポイント詳細は{{ Tag::link(config('url.waon_help2'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。</li>
    </ul></dd>
    <dt>●WAON（電子マネー）とは</dt>
    <dd class="u-mb-10"><ul class="trademark">
        <li class="no-before text--14">WAON加盟店、ネットショッピング、配送ドライバー端末等でのお支払いにご利用いただける電子マネーです。</li>
        <li class="text-link no-before text--14">WAON（電子マネー）詳細は{{ Tag::link(config('url.waon_help3'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。</li>
        <li class="text-link no-before text--14">WAON（電子マネー）が使えるお店は{{ Tag::link(config('url.waon_help4'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。</li>
    </ul></dd>
    <dt>●注意事項</dt>
    <dd class="u-mb-10"><ul class="note trademark">
        <li class="text--14">{{ $exchange_info->label }}には登録有効期限があります。登録有効期限内にWAONポイントへの交換（登録）が完了しないと、IDが無効になります。</li>
        <li class="text--14">登録後、専用端末でのダウンロード（受取り）操作が必要となります。</li>
        <li class="text--14">登録後、ダウンロード（受取り）期限内にダウンロード（受取り）が完了しないと、WAONポイントの受け取りはできなくなります。</li>
        <li class="text--14">カード番号頭4桁「1000」の「WAON POINT」カードへの交換はできません。</li>
        <li class="text--14">{{ $exchange_info->label }}は、イオン加盟店の店舗等での支払いには直接ご利用できません。</li>
        <li class="text--14">一度もご利用されていないWAONカードには交換できません。一度ご入金（チャージ）を行ってから交換してください。</li>
    </ul></dd>
    <dt>●その他</dt>
    <dd class="u-mb-10"><ul class="trademark">
        <li class="no-before text--14">「{{ $exchange_info->label }}」は、イオンリテール株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトです。</li>
        <li class="no-before text--14">「WAON（ワオン）」は、イオン株式会社の登録商標です。</li>
    </ul></dd>
</dl>
@endcomponent
@endsection
