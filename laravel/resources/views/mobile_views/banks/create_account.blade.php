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
<h1 class="ttl_exchange">交換内容入力</h1>

@php
$user = Auth::user();
$bank_account = $user->bank_account;
$charge = $user->has_ticket ? 0 : $bank->getCharge($user);
@endphp

<section class="selectedbank">
    <h2>銀行</h2>
    <div class="mb_20">
        <div class="bankname">{{ $bank->name }}銀行</div>
        <h2>支店名</h2>
        <div class="bankname">{{ $bank_branch->name.($bank_branch->name == '本店' ? '' : '支店') }}</div>
        <p class="fee">{{ number_format($charge) }}円（{{ number_format($charge * 10) }}ポイント）</p>
        <p class="text-link">{{ Tag::link(route('banks.bank_list'), '銀行・支店を選び直す', ['class' => 'icon-arrowr']) }}</p>
    </div>
</section><!--/selectedbank-->

<section class="selectedbank">
    {{ Tag::formOpen(['url' => route('banks.confirm_transfer'), 'class' => 'banks__form']) }}
    @csrf    
    {{ Tag::formHidden('bank_code', $bank->code) }}
        {{ Tag::formHidden('branch_code', $bank_branch->code) }}
        <table>
            <tr>
                <th><span>口座番号</span></th>
                <td>
                    {{ Tag::formText('number', $transfer['number'] ?? '', ['required' => 'required', 'size' => '7', 'class' => 'form01']) }}
                    @if ($errors->has('number'))
                    <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('number') }}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th><span>氏名</span></th>
                <td>
                    <div>
                        <div class="putted_area">
                            <p class="text--15">姓</p>
                            @if (isset($bank_account->id))
                            {{ $bank_account->last_name }}<br />
                            {{ Tag::formHidden('last_name', $bank_account->last_name) }}
                            @else
                            {{ Tag::formText('last_name', $transfer['last_name'] ?? '', ['required' => 'required', 'class' => 'form01']) }}
                            @endif
                            @if ($errors->has('last_name'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('last_name') }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text--15">名</p>
                            @if (isset($bank_account->id))
                            {{ $bank_account->first_name }}<br />
                            {{ Tag::formHidden('first_name', $bank_account->first_name) }}
                            @else
                            {{ Tag::formText('first_name', $transfer['first_name'] ?? '', ['required' => 'required', 'class' => 'form01']) }}
                            @endif
                            @if ($errors->has('first_name'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('first_name') }}</p>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th><span>氏名カナ（口座名義）</span></th>
                <td>
                    <div class="putted_area">
                        <div>
                            <p class="text--15">セイ</p>
                            @if (isset($bank_account->id))
                            {{ $bank_account->last_name_kana }}<br />
                            {{ Tag::formHidden('last_name_kana', $bank_account->last_name_kana) }}
                            @else
                            {{ Tag::formText('last_name_kana', $transfer['last_name_kana'] ?? '', ['required' => 'required', 'maxlength' => 24, 'class' => 'form01']) }}
                            @endif
                            @if ($errors->has('last_name_kana'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('last_name_kana') }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text--15">メイ</p>
                            @if (isset($bank_account->id))
                            {{ $bank_account->first_name_kana }}<br />
                            {{ Tag::formHidden('first_name_kana', $bank_account->first_name_kana) }}
                            @else
                            {{ Tag::formText('first_name_kana', $transfer['first_name_kana'] ?? '', ['required' => 'required', 'maxlength' => 24, 'class' => 'form01']) }}
                            @endif
                            @if ($errors->has('first_name_kana'))
                            <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('first_name_kana') }}</p>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @include('elements.transfer_point', ['exchange_info' => $exchange_info, 'transfer' => $transfer, 'charge' => $charge])
        </table>
    {{ Tag::formClose() }}
</section><!--/detailinput-->

<section><div class="btn_y">{{ Tag::link(route('exchanges.index'), '戻る') }}</div></section>
@endsection
