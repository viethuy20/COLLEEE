@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', 'JALマイレージバンクへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、JALマイレージバンクのポイントに交換することができます。')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    lockForm('ExchangeForm');

    var lock=false;
    $('#digital_gift_send').on('click', function() {console.log(lock);
        if(lock==false){
            lock=true;
            $('#digital_gift_send').css("pointer-events", "none");
            $('#ExchangeForm').submit();
        }
    });
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
<h1 class="ttl_exchange">交換内容確認</h1>
<section class="selectedbank">
    {{ Tag::formOpen(['url' => route('jalmile.store'), 'id' => 'ExchangeForm', 'class' => 'banks__form']) }}
    @csrf    
    <table>
            <tr>
                <th><span>交換ポイント</span></th>
                <td>
                    <p>{{ number_format($exchange['point']) }}&nbsp;ポイント</p>
                </td>
            </tr>
        </table>
        <div class="banks__change__btn__pink">
            <button type="button" id="digital_gift_send">{{ \App\Http\Middleware\Phone::authenticate() ? '次へ' : '認証'}}</button>
        </div>
        <!-- {{ Tag::formSubmit(\App\Http\Middleware\Phone::authenticate() ? '次へ' : '認証', ['class' => 'send']) }} -->
    {{ Tag::formClose() }}
</section><!--/detailinput-->

<section><div class="btn_y">{{ Tag::link(route('jalmile.index', []), '戻る') }}</div></section>

@endsection