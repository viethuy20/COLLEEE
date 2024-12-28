@extends('layouts.exchange')

@section('layout.title', 'ドットマネーへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、ドットマネーに交換することができます。')

@section('layout.head')
    <script type="text/javascript"><!--
        $(function () {
            lockForm('ExchangeForm');
        });
        //-->
    </script>
@endsection
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
        <h1 class="ttl_exchange">交換内容確認</h1>

        <section class="contents__box">
            {{ Tag::formOpen(['url' => route('dot_money.store'), 'id' => 'ExchangeForm', 'class' => 'banks__form']) }}
            @csrf
            <table>
                <tr>
                    <th><span>{{ $exchange_info->label }}口座番号</span></th>
                    <td>
                        <p>{{ $exchange['number'] }}</p>
                    </td>
                </tr>
                <tr>
                    <th><span>交換ポイント</span></th>
                    <td>
                        <p>{{ number_format($exchange['point']) }}&nbsp;ポイント</p>
                    </td>
                </tr>
            </table>
            {{ Tag::formSubmit(\App\Http\Middleware\Phone::authenticate() ? '次へ' : '認証', ['class' => 'banks__auth__btn']) }}
            {{ Tag::formClose() }}
        </section><!--/detailinput-->
        <div class="btn_y">{{ Tag::link(route('dot_money.exchange', ['number' => $exchange['number']]), '戻る') }}</div>
    </section><!--/contents-->
@endsection
