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
<ul class="u-mb-15">
    <li class="u-mb-10 text--14">・Appleのすべてをこの一枚で。</li>
    <li class="u-mb-10 text--14">・iPad、AirPods、Apple Watch、iPhone、MacBook、iCloud、アクセサリなどの購入に。</li>
    <li class="u-mb-10 text--14">・ギフトカードはEメールで届きます。</li>
    @php
    $yen_map = $exchange_info->getYenLabelMap();
    @endphp
    <li class="u-mb-10 text--14">・{{ implode('円、', $yen_map).'円' }}の金額から選べます。</li>
    <li class="u-mb-10 text--14">
        <strong>ギフトコードが届かない場合について</strong>
        <ol>
            <li class="u-mb-10 text--14">
                1.迷惑メールフォルダ、ゴミ箱フォルダに入ってしまう場合があります。
                メールフィルタなどが作用して迷惑メールフォルダ、ゴミ箱フォルダに入ってしまう場合があります。ギフトコードにつきましては info@colleee.net から配信させていただいております(GMOポイ活にご登録のメールアドレスへ送信されます)。 受信フォルダ以外に info@colleee.net からのメールが届いていないかどうかのご確認をお願いいたします。
            </li>
            <li class=" text-link u-mb-10 text--14">
                2.受信拒否設定が作用している場合があります。
                受信拒否設定を特に行っていない場合も、通信会社の迷惑メールフィルタなどが作用してメールが届かない場合がございます。 info@colleee.net をドメイン・アドレス指定での受信設定にしていただくと受信可能となります。 ギフトコード記載のメールを再送ご希望の場合は、
                {{ Tag::link(route('inquiries.index', ['inquiry_id' => 4]), 'お問い合わせフォーム') }}よりGMOポイ活サポートへお問い合わせください。
            </li>
        </ol>
    </li>
    <li class="u-mb-20 text--14">
        <strong>ギフトコードが届かない場合の注意事項</strong><br />
        ギフトコード記載メールの再送可能期間について<br />
        初回のギフトコード送信から60日以内<br />
        60日を超過した場合、ギフトコード記載メールの再送はできません。<br />
        交換手続き後にメールが届かない場合は、お早めにご対応ください。<br />
        ※ギフトコードの有効期限は、上記の「再送可能期間」とは異なります。<br />
        各ギフトコードに設定されている有効期限をご確認ください。
    </li>
    <li class="text--14">&copy; {{ \Carbon\Carbon::now()->year }} iTunes K.K. All rights reserved.</li>
</ul>
@endcomponent
@endsection
