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
            {{ $exchange_request->label }}
        </li>
    </ol>
</section>
@endsection
@section('layout.content')

<section class="contents">
    <h1 class="ttl_exchange">交換申請完了</h1>

    <section class="contents__box">
        <div class="contents__center__box">
            <div class="contents__center__box__main">
                <h3>交換申請が完了しました。</h3>
                <p>受付番号：{{ $exchange_request->number }}</p>
                <p>登録いただいているメールアドレス宛に交換受付完了メールをお送りしました。</p>
                <p>受付番号は交換申請に関するお問い合わせに使用いたしますので、正常に交換が完了するまで大切に保管してください。</p>
            </div>
        </div>

        <section class="contents__center__box__text">
            <p class="thisone">ポイント交換にあたって交換手数料が発生する交換先の場合、お振込み金額は手数料を差し引いた金額となりますので、ご了承ください。</p><p>ポイント交換に関するご不明点は、サポートページをご参照ください。</p>
        </section>
        <p>{!! Tag::link('/support/', 'お客様サポートはこちら', ['class' => 'banks__auth__btn']) !!}</p>

    </section>
    <div class="btn_y">{!! Tag::link(route('exchanges.index'), '交換ページトップへ戻る') !!}</div>
</section>
@endsection
