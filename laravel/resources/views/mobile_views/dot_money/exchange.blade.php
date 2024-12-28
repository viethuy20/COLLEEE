@php
$base_css_type = 'exchange';
@endphp
@extends('layouts.default')

@section('layout.title', 'ドットマネーへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、ドットマネーに交換することができます。')
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

@include('elements.dot_money_individual')

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
    {{ Tag::formOpen(['url' => route('dot_money.confirm'), 'class' => 'banks__form']) }}
    @csrf    
    {{ Tag::formHidden('number', $exchange['number']) }}

        <table>
            <tr>
                <th><span>{{ $exchange_info->label }}口座番号</span></th>
                <td>
                    <p>{{ $exchange['number'] }}</p>
                    @if ($errors->has('number'))
                    <p class="error_message"><span class="icon-attention"></span>{{ $errors->first('number') }}</p>
                    @endif
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
        <!-- {{ Tag::formSubmit('入力情報の確認', ['class' => 'send']) }} -->
        <div class="banks__change__btn__pink">
            <button type="submit">入力情報の確認</button>
        </div>
    {{ Tag::formClose() }}
    @endif
</section><!--/detailinput-->

<section><div class="btn_y">{{ Tag::link(route('exchanges.index'), '戻る') }}</div></section>
@endsection
