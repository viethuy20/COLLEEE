@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', 'LINE Payへのポイント交換｜はじめてのポイ活はGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、LINE Payに交換することができます。')
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
<h1 class="ttl_exchange">{{ $exchange_info->label }}へのポイント交換</h1>

@include('elements.line_pay_individual')

<h1 class="ttl_exchange">{{ $exchange_info->label }}への交換申請</h1>
<section class="selectedbank">
    @php
    $user = Auth::user();
    $max_yen = $exchange_info->chargeYen($user->max_exchange_point);
    $yen_map = $exchange_info->getYenPointLabelMap($max_yen);
    @endphp
    @if (empty($yen_map))
    <p class="error_message"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p>
    @else
    {{ Tag::formOpen(['url' => route('line_pay.confirm'), 'class' => 'banks__form']) }}
    @csrf 
    {{ Tag::formHidden('line_id', $exchange['line_id']) }}

        <table>
            <tr>
                <th><span>LINE ID</span></th>
                <td>
                    <div class="putted_area">
                        <p>********{{ substr($exchange['line_id'], -4) }}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <th><span>交換ポイント</span></th>
                <td>
                    <div class="banks__form__select">
                        <div class="banks__form__select__inner">
                            {{ Tag::formSelect('yen', [0 => '選択してください'] + $yen_map, old('yen', $exchange['yen'] ?? ''), ['class' => 'muchpoint']) }}
                        </div>
                    </div>
                    @if ($errors->has('yen'))
                    <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('yen') }}</p>
                    @endif
                </td>
            </tr>
        </table>
        <div class="line_pay__privacy">
            <p class="u-text-ac text--14">
                {!! Tag::formCheckbox('consent', true, false) !!}
                LINE Payナンバーをユーザー様に代わりLINE Pay株式会社から送信主催のGMO NIKKO株式会社へ提供することに同意します。
            </p>
        </div>
        @if ($errors->has('consent'))
        <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('consent') }}</p>
        @endif
        @if ($errors->has('reference_no_error'))
        <p class="error_message"><span class="icon-attention"></span>{!! $errors->first('reference_no_error') !!}</p>
        @endif
        <!-- {{ Tag::formSubmit('入力情報の確認', ['class' => 'send']) }} -->
        <div class="banks__change__btn__pink">
            <button type="submit">入力情報の確認</button>
        </div>
    {{ Tag::formClose() }}
    @endif
</section><!--/detailinput-->

<section><div class="btn_y">{{ Tag::link(route('exchanges.index'), '戻る') }}</div></section>
@endsection
