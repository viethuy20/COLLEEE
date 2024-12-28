@extends('layouts.mypage')

@section('layout.content')

@section('layout.title', '交換履歴｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はPC・スマートフォンでお小遣いが貯められる安心・安全なポイントサービスです。貯めたポイントは、現金やギフト券に交換することができます。')

<div class="contents">

    <h2 class="contents__ttl">交換履歴</h2>

    @if($paginator->total() < 1)
    <div class="exchange u-mt-20 u-text-ac">
        <p class="u-font-bold text--18 red">交換履歴はありません</p>
    </div>
    @else
    <ul class="exchange_list__contents">
        @foreach($paginator as $exchange_request)
        <li>
            <p class="text--15">交換日：{{ $exchange_request->created_at->format('Y-m-d') }}<br>受付番号：{{ $exchange_request->number }}</p>
            <p class="exchange_list__contents__ttl">{{ $exchange_request->label }}</p>
            <div class="exchange_list__contents__flex">
                <div class="exchange_list__contents__flex__l">
                    <p class="text--15">ステータス：{{ $exchange_request->status_message }}</p>
                    @if (isset($exchange_request->res_message))
                    <div class="exchange_list__contents__error">
                        <div class="exchange_list__contents__error__label">エラー詳細</div>
                        <div class="exchange_list__contents__error__inner">
                            <p class="exchange_list__contents__error__txt">{{ $exchange_request->res_message }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="exchange_list__contents__flex__r">
                    <p class="exchange_list__contents__point"><span class="red">{{ number_format($exchange_request->point) }}</span>ポイント</p>
                </div>
            </div>
        </li>
        @endforeach
    </ul>

    {{ $paginator->render('elements.pager', ['pageUrl' => function($page) { return route('users.exchange_list', ['page' => $page]); }]) }}
    @endif
</div>

<script type="text/javascript">
    $(function(){
        $(".exchange_list__contents__error").click(function(){
            if($(this).hasClass('open')){
                $(this).removeClass('open');
            }else{
                $(this).addClass('open');
            }
        });
    });
</script>
@endsection
