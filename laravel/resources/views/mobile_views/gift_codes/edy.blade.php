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
    <dt><span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
    <dd><ul class="trademark">
        <li class="text-link u-mb-10 no-before text--14">
            お手持ちのEdyカードやおサイフケータイ（楽天Edyアプリ）に登録することで、電子マネー「楽天Edy」として受け取ることができるサービスです。<br />
            ご利用にはインターネット環境が必要となります。<br />
            詳細は{{ Tag::link(config('url.ntt_edy'), 'ホームページ', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。
        </li>
        <li class="text-link no-before text--14">
            {{ $exchange_info->label }}を電子マネー「楽天Edy」として受け取るには、Edyカードもしくはおサイフケータイ（楽天Edyアプリ）が必要です。<br />
            お持ちでない方は、{{ Tag::link(config('url.edy_help5'), 'こちら', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。<br />
            電子マネー「楽天Edy」のサービス内容やご利用方法は、{{ Tag::link(config('url.edy_help5'), '楽天Edyのホームページ', ['target' => '_blank', 'class' => 'external']) }}をご覧ください。
        </li>
        <li class="text--14">{{ $exchange_info->label }}は、店舗等でのお支払いに直接利用することはできません。</li>
        <li class="text--14">{{ $exchange_info->label }}には有効期限があります。有効期限を経過すると使用できなくなります。 </li>
        <li class="text--14">{{ $exchange_info->label }}の登録完了後、60日以内にEdyをお受け取りください。期限が過ぎると受け取りできません。</li>
        <li class="text--14">換金、返金又は取消、再発行はできません。 </li>
        <li class="text--14">登録、受取りに必要な通信機器、通信費、交通費等は利用者の負担となります。</li>
        <li class="u-mb-10 text--14">{{ $exchange_info->label }}の登録サイトは、日本国外からのアクセスはできません。</li>
        <li class="text--14">「{{ $exchange_info->label }}」は、楽天Edy株式会社との発行許諾契約により、株式会社NTTカードソリューションが発行する電子マネーギフトサービスです。</li>
        <li class="text--14">「楽天Edy（ラクテンエディ）」は、楽天グループのプリペイド型電子マネーです。</li>
        <li class="text--14">「おサイフケータイ」は株式会社NTTドコモの登録商標です。</li>
        <li class="text--14">「{{ $exchange_info->label }}」を登録することで、楽天Edyとして受け取ることができます。</li>
    </ul></dd>
</dl>
@endcomponent
@endsection
