@extends('layouts.exchange')

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
    <section class="contents">
        <h1 class="ttl_exchange">{{ $exchange_info->label }}へのポイント交換</h1>

        @include('elements.line_pay_individual')

        <h1 class="ttl_exchange u-mt-20 u-mb-10">{{ $exchange_info->label }}への交換申請</h1>
        <section class="contents__box u-mt-remove">
            @php
                $user = Auth::user();
                $max_yen = $exchange_info->chargeYen($user->max_exchange_point);
                $yen_map = $exchange_info->getYenPointLabelMap($max_yen);
            @endphp
            @if (empty($yen_map))
                <div class=""><p class="error_message"><span class="icon-attention"></span>交換に必要なポイントが不足しています</p>
                </div>
            @else
                {{ Tag::formOpen(['url' => route('line_pay.confirm'), 'class' => '']) }}
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
                            <div class="putted_area">
                                <div class="banks__form__select">
                                    <div class="banks__form__select__inner">
                                        {{ Tag::formSelect('yen', [0 => '選択してください'] + $yen_map, old('yen', $exchange['yen'] ?? ''), ['class' => 'muchpoint']) }}
                                    </div>
                                </div>
                                @if ($errors->has('yen'))
                                    <p class="error_message"><span
                                            class="icon-attention"></span>{{ $errors->first('yen') }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="line_pay__privacy">
                    <p class="u-text-ac">
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
                {{ Tag::formSubmit('入力情報の確認', ['class' => 'banks__auth__btn']) }}
                {{ Tag::formClose() }}
            @endif
        </section><!--/detail input-->

        <section>
            <div class="btn_y">{{ Tag::link(route('line_pay.index'), '戻る') }}</div>
        </section>
    </section><!--/contents-->
@endsection
