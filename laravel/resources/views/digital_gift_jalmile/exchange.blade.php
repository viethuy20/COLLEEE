@extends('layouts.exchange')

@section('layout.title', 'JALマイレージバンクへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、JALマイレージバンクのポイントに交換することができます。')
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
@component('elements.digital_gift_jalmile_form', ['exchange_info' => $exchange_info, 'exchange' => $exchange])
<p>JALマイレージバンク(JMB)は、ご搭乗・ご宿泊・ご飲食・ショッピングなどでためたマイルを、国内線・国際線航空券をはじめとする豊富な特典と交換いただけるJALのマイレージプログラムです。交換は2円あたり1マイルの交換になります。</p>
<dl class="caution">
    <dt><span class="icon-attention"></span>&nbsp;{{ $exchange_info->label }}へのポイント交換にあたっての注意事項</dt>
    <dd><ul class="note">
        <li>JALマイレージバンクへの交換は、会員登録不要のかんたんポイント交換サービス「デジタルギフト®」にて行います。</li>
        <li>GMOポイ活で交換の申請をすると、ご登録のメールアドレス宛てにメールが届きます。メールの記載のURLより「デジタルギフト®」に遷移し、交換手続きを行ってください。</li>
        <li>JALマイレージバンクに交換の際は、JALマイレージバンクアカウントの連携が必要となります。JALマイレージバンクに登録してある「JMBお得意様番号」と「パスワード」をご準備してください。</li>
        <li>交換後の反映予定は翌月中旬頃となります。あらかじめご了承ください。</li>
        <li>その他JALマイレージバンクについての詳細は、「デジタルギフト®」のJALマイレージバンク交換ページをご参照ください。</li>
    </ul></dd>
    @if (Session::has('message'))
    <p class="error_message"><span class="icon-attention"></span>{{ Session::get('message') }}</p>
    @endif
</dl>
@endcomponent
@endsection


