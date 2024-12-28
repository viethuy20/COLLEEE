@extends('layouts.exchange')

@section('layout.title', 'dポイントへのポイント交換｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、dポイントに交換することができます。')
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

        @include('elements.d_point_individual')

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
                {{ Tag::formOpen(['url' => route('d_point.confirm'), 'class' => '']) }}
                @csrf
                {{ Tag::formHidden('number', $exchange['number']) }}
                <table>
                    <tr>
                        <th><span>{{ $exchange_info->label }}クラブ会員番号</span></th>
                        <td>
                            <div class="putted_area">
                                <p>********{{ substr($exchange['number'], -4) }}</p>
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
                {{ Tag::formSubmit('入力情報の確認', ['class' => 'banks__auth__btn']) }}
                {{ Tag::formClose() }}
            @endif
        </section><!--/detail input-->

        <section>
            <div class="btn_y">{{ Tag::link(route('d_point.index'), '戻る') }}</div>
        </section>
    </section><!--/contents-->
@endsection
