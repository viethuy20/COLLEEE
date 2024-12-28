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
<h1 class="ttl_exchange">交換先確認</h1>

@php
$user = Auth::user();
$bank_account = $user->bank_account;
$charge = $user->has_ticket ? 0 : $bank_account->bank->getCharge($user);
@endphp

<section class="selectedbank">
    <div class="banks__form">
        <table>
            <tr>
                <th><span>銀行</span></th>
                <td><p class="putted">{{ $bank_account->bank->name }}</p></td>
            </tr>
            <tr>
                <th><span>支店名</span></th>
                <td><p class="putted">{{ $bank_account->bank_branch->name.($bank_account->bank_branch->name == '本店' ? '' : '支店') }}</p></td>
            </tr>
            <tr>
                <th><span>口座番号</span></th>
                <td><p class="putted">{{ str_repeat('*', strlen($bank_account->number) - 1).substr($bank_account->number, -1) }}</p></td>
            </tr>
            <tr>
                <th><span>氏名</span></th>
                <td><p class="putted">{{ $bank_account->last_name }}&nbsp;{{ $bank_account->first_name }}</p></td>
            </tr>
            <tr>
                <th><span>氏名カナ（口座名義）</span></th>
                <td><p class="putted">{{ $bank_account->last_name_kana }}&nbsp;{{ $bank_account->first_name_kana }}</p></td>
            </tr>
        </table>
    </div>
</section><!--/detailconf-->

<section class="selectedbank">
    {{ Tag::formOpen(['url' => route('banks.confirm_transfer'), 'class' => 'banks__form']) }}
    @csrf
        {{ Tag::formHidden('account_id', $bank_account->id) }}
        @include('elements.transfer_point', ['exchange_info' => $exchange_info, 'transfer' => $transfer, 'charge' => $charge])
    {{ Tag::formClose() }}
    <p class="text-link u-mt-small">{{ Tag::link(route('banks.bank_list'), '別の口座に振り込む場合はこちら', ['class' => 'icon-arrowr']) }}</p>
</section><!--/detailinput-->
@endsection
