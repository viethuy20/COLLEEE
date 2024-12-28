<?php $base_css_type = 'exchange'; ?>
@extends('layouts.default')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    // 銀行名
    var bankName = $('#BankName');
    bankName.on('keyup', function(event) {
        var bank = convertToHurigana($(this).val());
        var bankList = $('#BankList');
        if (bank == '') {
            bankList.hide();
            return;
        }
        var bankElements = bankList.children();
        for( var i = 0; i < bankElements.length; i++){
            var bankElement = bankElements.eq(i)
            var hurigana = convertToHurigana(bankElement.attr('forHurigana'));
            if(hurigana.indexOf(bank) === 0){
                // 前方一致のときの処理
                bankElement.show();
                continue;
            }
            bankElement.hide();
        }

        bankList.show();
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
            金融機関振込
        </li>
    </ol>
</section>
@endsection

@section('layout.title', '金融機関振込｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金に交換されて指定の銀行口座に振り込まれます。')

@section('layout.content')
<h1 class="ttl_exchange">銀行選択</h1>

<section class="major_bank">
    <div class="contentsbox">
        <h2>主要銀行</h2>
        <ul>
            <?php
            $top_bank_code_list = ['0036', '0001', '0005', '0009', '9900'];
            $top_bank_list = $bank_list->whereIn('code', $top_bank_code_list)
                    ->sortBy(function($bank, $key) use($top_bank_code_list) {
                        return array_search($bank->code, $top_bank_code_list);
                    });
            ?>
            @foreach ($top_bank_list as $bank)
            <li>{!! Tag::link(route('banks.branch_list', ['bank' => $bank->code]), $bank->name.'銀行') !!}</li>
            @endforeach
        </ul>
    </div>
</section><!--/major_bank-->

<section class="search_bank">
    <h2>その他の銀行</h2>
    <p>銀行名をひらがなで入力してください</p>
    {!! Tag::formText('bank_name', '', ['class' => 'tosearch_bank', 'placeholder' => '', 'id' => 'BankName']) !!}
</section><!--/search_bank-->

<section class="list_bank">
    <h2>銀行</h2>
    @if ($bank_list->isEmpty())
    <p class="done">銀行情報は見つかりませんでした。</p>
    @else
    <ul id="BankList" style="display:none">
        @foreach ($bank_list as $bank)
        <li forHurigana="{{ $bank->hurigana }}">{!! Tag::link(route('banks.branch_list', ['bank' => $bank->code]), $bank->name.'銀行') !!}</li>
        @endforeach
    </ul>
    @endif
</section><!--/list_bank-->
<div class="btn_y">{!! Tag::link(route('exchanges.index'), '戻る') !!}</div>
@endsection
