<?php $base_css_type = 'exchange'; ?>
@extends('layouts.default')

@section('layout.title', 'KDOLハートへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、KDOLハートに交換することができます。')
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
            {{ $exchange_request->label }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
    <h1 class="ttl_exchange">交換申請完了</h1>

    <section class="selectedbank">
        <div class="contents__center__box">
            <div class="contents__center__box__main">
                <h3>交換申請が完了しました。</h3>
                <p>受付番号：{{ $exchange_request->number }}</p>
                <p>登録いただいているメールアドレス宛に交換受付完了メールをお送りしました。</p>
                <p>受付番号は交換申請に関するお問い合わせに使用いたしますので、正常に交換が完了するまで大切に保管してください。</p>
            </div>
        </div>

        <div class="contents__center__box__text u-mt-20">
            <p>
                平日9:00～14:00までのお申込みであれば、当日振込処理をさせていただきます。それ以外の時間帯のお申込みに関しては、翌銀行営業日の振込みになりますので、今しばらくお待ち下さい。</p>
            <p class="u-mt-small">キャッシュへの変換は、1日1回となります。1日に複数回のキャッシュへの変換は出来ませんのでご注意ください。</p>
            <p class="u-mt-small">お振込み金額は、手数料を差し引いた金額となりますので、ご了承ください。</p>
            <p class="u-mt-small">
                口座番号の記入間違いや口座解約等の理由により、現金に交換できない場合、交換依頼されたポイントはお返しできませんのであらかじめ御了承ください。（会員様ご本人による組み戻し処理が出来ないため。）お申込みの際の口座情報の入力には十分ご注意いただきますようお願いいたします。</p>
            <p class="u-mt-small">複数のIDから同一口座へのキャッシュへの変換は出来ません。（本人以外への利用は不正利用と判断します。）</p>
        </div>
        <div class="btn_y">{!! Tag::link(route('exchanges.index'), '交換ページトップへ戻る') !!}</div>
    </section>
@endsection
