<?php
    $base_css_type = 'entries';
    $hidden_header = true;
?>
@extends('layouts.plane')

@section('layout.title', 'GMOポイ活新規会員登録｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。無料会員登録して、ポイントを貯めて現金やギフトカードに交換しよう♪')
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
            新規会員登録
        </li>
    </ol>
</section>
@endsection
@section('layout.content')
    <div class="contents">
        <h2 class="contents__ttl">電話による発信認証</h2>
        <div class="contents__box">
            @if (Session::has('message'))
            <p class="done">{!! nl2br(Session::get('message')) !!}</p>
            @endif
            <div class="contents__box__inner">
                <ol class="entries__flow">
                    <li class="prev"><i><img src="/images/entries/ico_flow_input.svg"></i><p>入力</p></li>
                    <li class="prev"><i><img src="/images/entries/ico_flow_confirm.svg"></i><p>確認</p></li>
                    <li class="current"><i><img src="/images/entries/ico_flow_call.svg"></i><p>電話認証</p></li>
                    <li class=""><i><img src="/images/entries/ico_flow_success.svg"></i><p>完了</p></li>
                </ol>
                <div class="contents__box__txt">
                    <p>ご登録の電話番号から<strong class="strong">【2分以内】</strong>に「発信認証電話番号」へ発信してください。<br>呼び出し音の後自動的に通話が終了し、認証が完了します。（音声アナウンス等は流れず、発話等の必要はございません）</p>
                </div>

                <div class="entries__create">
                    <div class="entries__form">
                        <div class="entries__form__inner">
                        @php
                            $newStartJson = $ost_token->start_body;
                            $newBody = json_decode($newStartJson);
                            if ($newBody && !empty($newBody->result) && !empty($newBody->result->waiting_until)) {
                                    $expiresAt = $newBody->result->waiting_until;
                            }
                        @endphp
                        <div class="entries__form__table__head js-limit counter" timestamp="{{ isset($expiresAt) ? Carbon\Carbon::parse($expiresAt)->timestamp : '' }}">残り時間<span class="countDownMin"></span></div>
                            <dl class="entries__form__table confirm">
                                <dt><label for="email">発信認証電話番号<br><p class="attention"><strong class="strong">電話番号のおかけ間違いに<br>ご注意ください</strong></p></label></dt>
                                <dd class="flex">
                                    <div>
                                        <p class="telnum js-telnum"><i><img src="/images/common/ico_phone.svg"></i>{{ $ost_token->authentic_number }}</p>
                                        <p class="attention">スマートフォンの場合、右のQRコードから電話をおかけいただけます。</p>
                                        <ul class="notes">
                                            <li>通話料金は無料です</li>
                                        </ul>
                                    </div>
                                    <div class="telnum__qr js-telnum-qr"></div>
                                </dd>
                                <dt><label for="email">ご登録の電話番号</label></dt>
                                <dd>
                                    <p>{{ $ost_token->tel }}</p>
                                </dd>
                            </dl>
                            <div class="contents__btn__wrap">
                                <div class="contents__btn">
                                    <input type="hidden" id="ost-token" value="{{ $ost_token->id }}">
                                    <input type="hidden" id="ost-token-tel" value="{{ $ost_token->tel }}">
                                    {{ Tag::formButton('画面が切り替わらない場合はこちら', ['id'=>'entries-auth-tel','onclick' => "location.href='".url(route('entries.auth_tel'))."'"]) }}
                                </div>
                            </div>
                        </div>
                        <div class="contents__textlink">ご登録の電話番号を変更する場合は<a href="{{ session('previous_page_url') }}?r=entry-tel" class="textlink" >こちら</a></div>
                    </div>
                </div>

                <dl class="contents__notes">
                    <dt class="contents__notes__ttl">注意事項</dt>
                    <dd  class="contents__notes__box">
                        <ul class="contents__notes__list">
                            <li>入力電話番号と、発信先となる認証用電話番号に誤りがないかご確認ください。</li>
                            <li>端末の設定により、番号をクリックしても発信できない場合がございます。</li>
                            <li>非通知設定に設定されている場合は、非通知設定の解除を行なってください。</li>
                            <li>上記をご確認いただいても正常に発信認証が完了されない場合は、お手数ですが「<a href="{{ route('inquiries.index', ['inquiry_id' => 10]) }}" target="_blank" class="textlink blank">お問い合わせフォーム</a>」よりご連絡下さい。</li>
                        </ul>
                    </dd>
                </dl>

            </div>
        </div>

    </div>
    @if(session()->has('error_phone'))
    @include('inc.started-modal')
    @endif
    <script>

        // ランキング
    $(function(){
        // タイムセール
        var diffTimestamp = 0;
        $(function() {
            var serverTimestamp = "{{ \Carbon\Carbon::now()->timestamp }}";

            var clientDate = new Date();
            var clientTimestamp = Math.floor(clientDate.getTime() / 1000);

            // サーバーとクライアントの時間差
            diffTimestamp = serverTimestamp - clientTimestamp;

            setInterval(function() {
                $('.counter').each(function() {
                    var $ele = $(this);

                    // セール終了時間
                    var stopAtTimestamp = $ele.attr('timestamp');

                    // サーバーとクライアントの時間差を加味した現在の時間
                    var nowDate = new Date();
                    var nowTimestamp = Math.floor(nowDate.getTime() / 1000);
                    nowTimestamp += diffTimestamp;

                    // セール終了までの残り時間
                    var countDownTimestamp = Math.max(stopAtTimestamp - nowTimestamp, 0);

                    var second = 60 * 60 * 24;
                    var dd = Math.floor(countDownTimestamp / second);
                    var hh = Math.floor((countDownTimestamp % second) / (60 * 60));
                    var mm = Math.floor((countDownTimestamp % second) / 60) % 60;
                    var ss = Math.floor(countDownTimestamp % second) % 60 % 60;

                    var h0 = ('00' + hh).slice(-2);
                    var m0 = ('00' + mm).slice(-2);
                    var s0 = ('00' + ss).slice(-2);

                    // htmlに残り時間を反映
                    $ele.find('.countDownMin').text(m0 + ':' + s0);

                    if(m0 == '00' && s0 == '00'){
                        document.getElementById("entries-auth-tel").click();
                    }
                });
            }, 1000);
        });
    });
    </script>

    {!! Tag::script('/js/modal.js', ['type' => 'text/javascript']) !!}
    {!! Tag::script('/js/plugin/qrcode.min.js', ['type' => 'text/javascript']) !!}
    {!! Tag::script('/js/entries.js', ['type' => 'text/javascript']) !!}
@endsection
