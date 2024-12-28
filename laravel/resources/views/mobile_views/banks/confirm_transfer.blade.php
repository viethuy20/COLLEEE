@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', '金融機関振込｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金に交換されて指定の銀行口座に振り込まれます。')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
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
            金融機関振込
        </li>
    </ol>
</section>
@endsection

@section('layout.content')
<h1 class="ttl_exchange">交換内容確認</h1>

<section class="selectedbank">
    <div class="banks__form">
        <table>
            <tr>
                <th><span>銀行</span></th>
                <td><p class="putted">{{ $bank->name }}</p></td>
            </tr>
            <tr>
                <th><span>支店名</span></th>
                <td><p class="putted">{{ $bank_branch->name.($bank_branch->name == '本店' ? '' : '支店') }}</p></td>
            </tr>
            <tr>
                <th><span>口座番号</span></th>
                <td><p class="putted">{{ $transfer['number'] }}</p></td>
            </tr>
            <tr>
                <th><span>氏名</span></th>
                <td><p class="putted">{{ $transfer['last_name'] }}&nbsp;{{ $transfer['first_name'] }}</p></td>
            </tr>
            <tr>
                <th><span>氏名カナ（口座名義）</span></th>
                <td><p class="putted">{{ $transfer['last_name_kana'] }}&nbsp;{{ $transfer['first_name_kana'] }}</p></td>
            </tr>
            <tr>
                <th><span>交換ポイント</span></th>
                <td><p class="putted">{{ number_format($transfer['point']) }}&nbsp;ポイント</p></td>
            </tr>
            <tr>
                <th><span>お振込金額</span></th>
                <td><p class="putted">{{ number_format($transfer['yen2']) }}&nbsp;円</p></td>
            </tr>
        </table>
    </div>

    {{ Tag::formOpen(['url' => route('banks.store_transfer'), 'id' => 'ExchangeForm']) }}
    @csrf    
    <div class="banks__change__btn__pink">
            <button type="submit">{{ \App\Http\Middleware\Phone::authenticate() ? '次へ' : '認証' }}</button>
        </div>
    {{ Tag::formClose() }}
</section><!--/detailinput-->

<section><div class="btn_y">{{ Tag::link(isset($transfer['account_id']) ? route('banks.select_account') : route('banks.create_account'), '戻る') }}</div></section>
@endsection
