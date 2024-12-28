@extends('layouts.exchange')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    // 支店名
    var bankBranchName = $('#BankBranchName');
    bankBranchName.on('keyup', function(event) {
        var bankBranch = convertToHurigana($(this).val());
        var bankBranchList = $('#BankBranchList');
        if (bankBranch == '') {
            bankBranchList.hide();
            return;
        }
        var bankBranchElements = bankBranchList.children();
        for( var i = 0; i < bankBranchElements.length; i++){
            var bankBranchElement = bankBranchElements.eq(i)
            var hurigana = convertToHurigana(bankBranchElement.attr('forHurigana'));
            if(hurigana.indexOf(bankBranch) === 0){
                // 前方一致のときの処理
                bankBranchElement.show();
                continue;
            }
            bankBranchElement.hide();
        }

        bankBranchList.show();
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
<section class="contents">
    <h1 class="ttl_exchange">支店名検索</h1>
    <section class="selectedbank">
        <h2>銀行</h2>
        <div class="bankname">{{ $bank->name }}銀行</div>
    </section><!--/selectedbank-->

    <section class="search_bank">
        <h2>支店名を検索</h2>
        <div class="contentsbox">
            <p>支店名をひらがなで入力してください</p>
            {!! Tag::formText('bank_branch_name', '', ['class' => 'tosearch_bank', 'placeholder' => '', 'id' => 'BankBranchName']) !!}
        </div><!--/contentsbox-->
    </section><!--/search_bank-->

    <section class="list_bank">
        <h2>支店名</h2>
        <div class="contentsbox">
        @if ($bank_branch_list->isEmpty())

        <p class="done">支店情報は見つかりませんでした。</p>
        @else
        <ul id="BankBranchList" style="display:none">
            @foreach ($bank_branch_list as $bank_branch)
            <li forHurigana="{{ $bank_branch->hurigana }}">{!! Tag::link(route('banks.create_account', ['bank' => $bank->code, 'bank_branch' => $bank_branch->code]), $bank_branch->name.($bank_branch->name == '本店' ? '' : '支店')) !!}</li>
            @endforeach
        </ul>
        @endif
        </div><!--/contentsbox-->
    </section><!--/list_bank-->
    <div class="btn_y">{!! Tag::link(route('banks.bank_list'), '戻る') !!}</div>
</section><!--/contents-->
@endsection
