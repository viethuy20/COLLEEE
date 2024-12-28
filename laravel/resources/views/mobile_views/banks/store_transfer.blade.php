@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', '金融機関振込｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金に交換されて指定の銀行口座に振り込まれます。')
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
            金融機関振込
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
<h1 class="ttl_exchange">交換申請完了</h1>

<section class="selectedbank">
    <div class="contents__center__box">
        <div class="contents__center__box__main">
            <h3 >交換申請が完了しました。</h3>
            <p>受付番号：{{ $exchange_request->number }}</p>
        </div>
        <div class="contents__center__box__text">
            <p class="mb_15">登録いただいているメールアドレス宛に交換受付完了メールをお送りしました。</p>
            <p class="mb_15">受付番号は交換申請に関するお問い合わせに使用いたしますので、正常に交換が完了するまで大切に保管してください。</p>
            <p class="thisone">ポイント交換にあたって交換手数料が発生する交換先の場合、お振込み金額は手数料を差し引いた金額となりますので、ご了承ください。</p>
            <p>ポイント交換に関するご不明点は、サポートページをご参照ください。</p>
        </div>
        <div class="banks__change__btn__pink">
            {{ Tag::link('/support/', 'お客様サポートはこちら') }}
        </div>
    </div>
</section><!--/exchange_applied-->
<div class="btn_y">{{ Tag::link(route('exchanges.index'), '交換ページトップへ戻る') }}</div>
@endsection
