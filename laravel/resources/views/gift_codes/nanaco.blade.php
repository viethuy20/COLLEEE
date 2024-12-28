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
<dl class="caution">
    <dt><span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
    <dd>
        <ul class="trademark">
            <li class="text-link u-mb-10 text--14 no-before">
                お手持ちのnanacoカードやnanacoモバイルに登録することで、電子マネー「nanaco」として受け取ることができるサービスです。<br />
                オムニ７をはじめ{{ $exchange_info->label }}対応サイトでは、{{ $exchange_info->label }}を直接お支払にご利用いただく事も可能です。<br />
                ご利用にはインターネット環境が必要となります。<br />
                詳細は{{ Tag::link(config('url.ntt_nanaco'), 'ホームページ', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。
            </li>
            <li class="text-link text--14 no-before">
                {{ $exchange_info->label }}を「nanaco」にチャージするには、nanacoカードもしくはnanacoモバイルが必要です。<br />
                お持ちでない方は、{{ Tag::link(config('url.nanaco_about'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。<br />
                「nanaco」のサービス内容やご利用方法は、{{ Tag::link(config('url.nanaco_home'), 'nanacoのホームページ', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。
            </li>
            <li class="text--14">{{ $exchange_info->label }}は、セブン-イレブン等の店舗でのお支払いに直接利用することはできません。</li>
            <li class="text--14">{{ $exchange_info->label }}には有効期限があります。有効期限を経過すると使用できなくなります。</li>
            <li class="text--14">
                {{ $exchange_info->label }}IDを当日正午12時までに登録した場合は、翌日朝6時以降に「nanaco」を受け取る事が出来ます。<br />
                当日正午12時以降に登録した場合は、翌々日朝6時以降の受け取りになります。<br />
                登録完了時に表示される『受取可能日』を必ずご確認ください。
            </li>
            <li class="text--14">「nanaco」入会当日に{{ $exchange_info->label }}IDを登録した場合は『受取可能日』に関わらず、翌々日の朝6時以降に「nanaco」の受け取りが可能になります。</li>
            <li class="text--14">一度に複数件の登録をされると、登録が完了しないことがございます。</li>
            <li class="text--14">換金、返金又は取消、再発行はできません。</li>
            <li class="text--14">登録、受取りに必要な通信機器、通信費、交通費等は利用者の負担となります。</li>
            <li class="u-mb-10 text--14">{{ $exchange_info->label }}の登録サイトやご利用サイトは、日本国外からのアクセスはできません。</li>
            <li class="text--14">「nanaco(ナナコ)」と「{{ $exchange_info->label }}」は株式会社セブン・カードサービスの登録商標です。</li>
            <li class="text--14">「{{ $exchange_info->label }}」は、株式会社セブン・カードサービスとの発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトサービスです。</li>
        </ul>
    </dd>
</dl>
@endcomponent
@endsection
