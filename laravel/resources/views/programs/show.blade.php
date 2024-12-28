@php
$base_css_type = 'detail';
@endphp
@extends('layouts.default')

@section('layout.head')
{!! Tag::style('/css/common_20240613.css') !!}
<script type="text/javascript"><!--
    $(function(){
        var reviewMessage = $('#ReviewMessage');
        if (reviewMessage) {
            countReviewMessage(reviewMessage);
            reviewMessage.on('keydown keyup keypress change', function(event) {
                countReviewMessage($(this));
            });
        }
        if($('.error').length){
            var speed = 400;
            var errorPos = $('#post').offset().top;
            $('body,html').animate({scrollTop:errorPos}, speed, 'swing');
        }
    });

    var countReviewMessage = function(reviewMessage){
        var textLength = reviewMessage.val() ? reviewMessage.val().replace(/[\s\u3000]/g, '').length : 0;

        $('#ReviewMessageLength').text(textLength);
        if (textLength < 100 || textLength > 1000) {
            reviewMessage.css({'backgroundColor':'#ffcccc'});
        } else {
            reviewMessage.css({'backgroundColor':'#f0f0f0'});
        }
    }
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
                $ele.find('.countDownDay').text(dd);
                $ele.find('.countDownTime').text(h0 + ':' + m0 + ':' + s0);
                $ele.find('.countDownDayJpStr').text(dd + '日 ');
                $ele.find('.countDownTimeJpStr').text(h0 + '時間 ' + m0 + '分 ' + s0 + '秒 ');
            });
        }, 1000);

        //スムーススクロール
        $('a[href^="#"]').click(function(){
            var adjust = 0;
            var speed = 400;
            var href= $(this).attr("href");
            var target = $(href == "#" || href == "" ? 'html' : href);
            var position = target.offset().top + adjust;
            $('body,html').animate({scrollTop:position}, speed, 'swing');
            return false;
        });
        if( $('.programs_detail__review').length) {
            $('.programs_detail__review').first().attr('id', 'js-review');
        }
    });

    //number of text exclude space. \n
    document.addEventListener('DOMContentLoaded', function() {
        var messageTextArea = document.getElementById('ReviewMessage');
        if (messageTextArea) {
            messageTextArea.addEventListener('input', function() {
            var textLengthWithoutSpaces = messageTextArea.value.replace(/[\s\u3000]/g, '').length;
            if (textLengthWithoutSpaces < 100) {
                messageTextArea.setCustomValidity('このテキストは 100 文字以上で指定してください（現在は ' + textLengthWithoutSpaces + ' 文字です）。');
            } else {
                messageTextArea.setCustomValidity('');
            }
            });
        }
      });
    document.addEventListener('DOMContentLoaded', function() {
    window.addEventListener("load",function(){
        if(document.getElementsByClassName('tooltip').length){
            const tooltip = Array.from(document.getElementsByClassName('tooltip'));
            const tooltipOpen =  Array.from(document.getElementsByClassName('js-tooltip-open'));
            const tooltipClose = Array.from(document.getElementsByClassName('js-tooltip-close'));
            function closeTooltip(){
                function closeTooltipInner(target){
                    target.addEventListener('click', function() {
                        if(target.classList.contains('js-tooltip-close')) {
                            target.closest('.tooltip').classList.add('is-close');
                            target.closest('.tooltip').classList.remove('is-open');
                            setTimeout(function(){
                                target.closest('.tooltip').style.display = 'none';
                            }, 301);
                            return;
                        }
                    });
                }
                tooltipClose.forEach(function(target) {
                    closeTooltipInner(target);
                });
            }
            function openTooltip() {
                tooltipOpen.forEach(function(target) {
                    target.addEventListener('click', function() {
                        const dataTooltipOpen = target.dataset.tooltipOpen;
                        target.classList.add('js-tooltip-close');
                        tooltip.forEach(function(e) {
                            if(e.dataset.tooltip === dataTooltipOpen){
                                e.classList.add('is-open');
                                e.classList.remove('is-close');
                                e.style.display = 'block';
                            }
                        });
                        return;
                    });
                });
            }
            closeTooltip();
            openTooltip();
        }
    });
    },false);
//-->
</script>
@endsection

@php
// アフィリエイト
$affiriate = $program->affiriate;
// ポイント
$point = $program->point;
// タイムセール
$is_time_sale = $point->time_sale;
$review_total = \App\Review::ofProgram($program->id)->count();
$user = Auth::user();
$share_url = isset($user->id) ? '?'.http_build_query([config('share.friend_key') => $user->friend_code, config('share.promotion_key') => 3]) : null;
@endphp



@section('layout.title', $program->title.' | ポイントサイトのGMOポイ活なら'. $program->point->fee_label_s.'P還元')
@section('layout.keywords', implode(',', $program->tag_list))
@if ($point->fee_type == 2)
{{-- fix % --}}
@section('layout.description', "GMOポイ活経由で" . $program->title.'案件に参加すると、購入金額の' . $point->fee_label_s . '相当のポイントをプレゼント！1P=1円相当のポイントが貯まってお得！貯めたポイントは現金やギフト券に交換できます。')
@else
{{-- fix fee --}}
@section('layout.description', "GMOポイ活経由で" . $program->title.'案件に参加すると、' . $point->fee_label_s . 'ポイントをプレゼント！1P=1円相当のポイントが貯まってお得！貯めたポイントは現金やギフト券に交換できます。')
@endif

@section('url', route('programs.show', ['program' => $program]))

@section('layout.structure_data_review')
    @if (WrapPhp::count($program->reviewsAccepted) > 0)
        <script type="application/ld+json">
            {!! $data !!}
        </script>
    @endif
@endsection

@section('layout.breadcrumbs')
    @if(WrapPhp::count($arr_breadcrumbs) > 0)
        <section class="header__breadcrumb">
            <ol>
                @foreach($arr_breadcrumbs as $item)
                    <li>
                        <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                    </li>
                @endforeach
                <li>{{$program->title}}</li>
            </ol>
        </section>
    @endif
@endsection

@section('layout.content')
@if (!Auth::check())
    @include('fixed_btn')
@endif

@if (Auth::check() && $error_type == 0)
    @include('fixed_btn_ad')
@endif

<div class="contents">

        @switch ($error_type)
        @case (1)
        <div class="program__detail">
            <div class="program__detail__inner">
                <div class="program__detail__content">
                    <p class="programs_error__txt">
                        <span style="color: #000;">
                            {{ $program->title }}<br />
                            <br />
                        </span>
                        <span>
                            アクセスありがとうございます。<br />
                            この広告は終了させていただきました。<br />
                            ご了承ください。<br />
                        </span>
                    </p>
                </div>
                <div class="programs_detail__btn__pink">
                    {{ Tag::link(route('website.index'), 'TOPへ', null, null, false) }}
                </div>
            </div>
        </div>
        @break

        @default
        <!-- 広告概要 -->
        <div class="program__detail">
            @if ($is_time_sale)
            <div class="program__timesale counter" timestamp="{{ $point->stop_at->timestamp }}">
                <p>今だけ高還元タイムセール中！</p>
                <p> <span class="countDownDayJpStr"></span> <span class="countDownTimeJpStr"></span></p>
            </div>
            @endif
            <div class="program__detail__inner">
                @if ($campaigns->count() > 0)
                    @foreach ($campaigns as $cmp)
                    <p class="program__detail__cmp"><span>{{ $cmp['title'] }}</span>
                        @if(isset($cmp['url']))
                            <a target="_blank" href="{{ $cmp['url'] }}">{{ $cmp['campaign'] }}</a>
                        @else
                            {{ $cmp['campaign'] }}
                        @endif
                    </p>
                    @endforeach
                @endif
                <div class="program__detail__content">
                    <div class="program__detail__img">
                        {{ Tag::image($affiriate->img_url, $program->title) }}
                    </div>
                    <div class="program__detail__txt">
                        <div class="program__detail__txt__head">
                            <div>
                                <ul class="program__detail__tags">
                                @if ($program->multi_join == 1)
                                <li>何度でもOK</li>
                                @elseif ($program->join_status == 1)
                                <li>獲得済</li>
                                @elseif ($program->join_status == 2)
                                <li>獲得予定</li>
                                @elseif ($program->join_status == 0)
                                <li>未参加</li>
                                @endif

                                @php
                                    // $user_rank_label = 'ランク特典非対象';
                                    $user_rank_point_up = 0;
                                @endphp
                                @if ($point->bonus == 1)
                                @php
                                    $rank_label = 'ランク特典対象';
                                    $user_rank_map = [
                                        2 => ['class' => 'silver', 'label' => 'ランク特典+3%', 'rate' => 0.03],
                                        3 => ['class' => 'gold', 'label' => 'ランク特典+10%', 'rate' => 0.10],
                                    ];
                                    $user_rank = Auth::check() ? Auth::user()->rank : 0;
                                    if (isset($user_rank_map[$user_rank])) {
                                        // $user_rank_label = $user_rank_map[$user_rank]['label'];
                                        if ($point->fee_type == 2) {
                                            $user_rank_point_up = str_replace('ランク特典+', '', $user_rank_map[$user_rank]['label']);
                                        } else {
                                            $user_rank_point_up = $point->point * $user_rank_map[$user_rank]['rate'];
                                            $user_rank_point_up = floor($user_rank_point_up);
                                        }
                                    }
                                @endphp
                                <li>
                                    <a class="js-tooltip-open" data-tooltip-open="member-rank">{{ $rank_label }}</a>
                                    <div class="tooltip" data-tooltip="member-rank">
                                        <p class="tooltip__ttl">ランク特典とは？</p>
                                        <p>サービス利用で獲得したポイントに、さらに会員ランクに応じた特典をプレゼントいたします。<br>このタグがついた広告がボーナス対象です。</p>
                                        <dl>
                                            <dt>1,000P獲得、ランク特典10.0%の場合</dt>
                                            <dd>
                                                獲得ポイント + 獲得ポイント × ランク特典なので<br>
                                                <span>1,000P + 1,000P × 10.0% = 1,100P獲得</span><br>
                                                となり、合計1,100ポイント獲得となります。<br>
                                            </dd>
                                        </dl>
                                        {{ Tag::link(route('abouts.member_rank'), '会員ランクについて') }}
                                        <a class="js-tooltip-close tooltip__close"></a>
                                    </div>
                                </li>
                                @endif
                                    
                                    <li>お友達紹介対象</li>
                                </ul>
                                <h1 class="program__detail__ttl">{{ $program->title }}</h1>
                            </div>
                            <ul class="program__detail__icons">
                                @if ($has_user_program)
                                <li class="fav js-fav is-active">{{ Tag::link(route('users.remove_program', ['program' => $program]), '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z"/></svg>', ['class' => 'save_scroll'], null, false) }}</li>
                                @else
                                <li class="fav js-fav">{{ Tag::link(route('users.add_program', ['program' => $program]), '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z"/></svg>', ['class' => 'save_scroll'], null, false) }}</li>
                                @endif
                                @php
                                $line_post_url = urlencode(route('programs.show', ['program'=> $program]));
                                @endphp
                                <li class="line"><a href="https://social-plugins.line.me/lineit/share?url={{ $line_post_url }}"><img src="/images/common/ico_line.png"></a></li>
                                @php
                                $x_post_text = urlencode($program->title . ' | GMOポイ活のオススメ広告');
                                $x_post_url = urlencode(route('programs.show', ['program'=> $program]));
                                $x_post_hashtags = urlencode('ポイ活,ポイントサイト,お得,GMOポイ活');
                                @endphp
                                <li class="x"><a href="https://twitter.com/intent/tweet?url={{ $x_post_url }}&text={{ $x_post_text }}&hashtags={{ $x_post_hashtags }}"><img src="/images/common/ico_tw.png"></a></li>
                            </ul>
                        </div>
                        <div class="program__detail__txt__foot">
                            <div class="program__detail__point">
                                @if ($is_time_sale)
                                <p class="usually"><span>通常ポイント</span><span>{{ $point->previous_point->fee_label_s }}P</span></p>
                                @endif
                                <p><span>
                                @if ($point->fee_type == 2)
                                購入額の
                                </span>{{ str_replace('%', '', $point->fee_label_s) }}<span>
                                @else
                                </span>{{ $point->fee_label_s }}<span>
                                @endif
                                @if ($point->fee_type == 2)
                                %P
                                @else
                                P
                                @endif
                                </span></p>
                                @if ($user_rank_point_up)
                                <p class="bonus"><span>ランク特典</span><span>
                                @if ($point->fee_type == 2)
                                <span>獲得Pの</span>
                                @endif
                                {{ $user_rank_point_up }}P</span></p>
                                @endif
                            </div>
                            <div class="program__evaluation">
                                <ul class="star js-star">
                                    @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($program->review_avg))
                                    <li class="color"></li>
                                    @else
                                    <li></li>
                                    @endif
                                    @endfor
                                </ul>
                                <p class="star__count js-star-count">
                                @if ($program->review_avg > 0)
                                {{ $program->review_avg }}
                                @else
                                -
                                @endif
                                </p>
                                <p>(
                                @if ($program->review_total > 0)
                                <a href="javascript:void(0);" class="textlink js-scroll" data-scroll="review">{{ number_format($program->review_total) }}件</a>
                                @else
                                0件
                                @endif
                                )</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="program__detail__table stock">
                    <dl>
                        <dt>ポイント<br>獲得条件</dt>
                        <dd>{{ $program->fee_condition }}<br><a href="javascript:void(0);" class="textlink js-scroll" data-scroll="condition">さらに詳細を確認する</a></dd>
                    </dl>
                    @if ($program->programStock)
                    @if ($program->programStock->stock_cv && $program->programStock->stock_cv > 0)
                    <dl>
                        <dt>参加可能人数</dt>
                        <dd class="strong">残り<span>{{ $program->programStock->stock_cv ?? 0 }}</span>人</dd>
                    </dl>
                    @endif
                    @endif
                    <dl>
                        <dt>予定反映目安</dt>
                        <dd>
                        @if (!isset($affiriate->give_days))
                        予定への反映なし
                        @elseif ($affiriate->give_days == 0)
                        即時
                        @else
                        {{ $affiriate->give_days }}日
                        @endif
                        </dd>
                    </dl>
                    <dl>
                        <dt>獲得予定目安</dt>
                        <dd>{{ config('map.accept_days')[$affiriate->accept_days] }}</dd>
                    </dl>
                </div>
                @if (!Auth::check())
                @include('inc.beginner-guide')
                @endif

                @switch ($error_type)
                @case (2)
                @php
                switch ($program->devices) {
                    case 1:
                        $device_str = 'PC';
                        break;
                    case 2:
                        $device_str = 'iOS';
                        break;
                    case 4:
                        $device_str = 'Android';
                        break;
                    case 6:
                        $device_str = 'iOS・Android';
                        break;
                    default:
                        $device_str = '';
                        break;
                }
                @endphp
                <div class="program__detail__cv">
                    <div class="programs_error">
                        <div class="programs_error__txtarea">
                            <p class="programs_error__headline"><span>{{ $device_str }}専用の広告です</span>QRコードからポイント獲得ページを開く</p>
                            <p class="programs_error__txt__slim">QRコードの商標はデンソーウェーブの登録商標です。</p>
                        </div>
                        <div class="programs_error__qrcode">
                            {{ Tag::image(route('qr.image') . '?' . http_build_query(['d' => route('programs.show', ['program' => $program, 'rid' => 32]), 's' => 164]), '') }}
                        </div>
                    </div>
                </div>
                @break

                @case (0)
                @if (Auth::check())
                <div class="program__detail__cv">
                    <div class="program__cv" id="js-fixed-dup">
                        {{ Tag::link(route('programs.click', ['program' => $program, 'rid' => $rid]), '広告利用でポイントGET！',['target' => '_blank'], null, false) }}
                    </div>
                    <p>{{ Tag::link('/support/?p=916', 'ポイントに関する利用環境のご確認', ['class' => 'textlink', 'target' => '_blank']) }}</p>
                </div>
                @else
                <div class="contents__box__cv">
					@php
                    $cv = 'deco min';
                    @endphp
                    @include('inc.cv-btn',['cv' => $cv])
                    <p>すでに会員の方は<a class="textlink" href="{{ route('login', ['back' => 0]) }}">こちらからログイン</a></p>
                </div>
                @endif
                @break
                @endswitch

            </div>
        </div>
        @break
        @endswitch
        <div class="program__contents" id="js-fixed-point">
			<section class="program__contents__main">
                <div class="program__tab">
                    <div class="program__tab__heading js-tabs">
                        <input id="condition" type="radio" name="programTab" checked>
                        <label for="condition" class="js-scroll-target" data-scroll="condition"><h2>ポイント獲得条件</h2></label>
                        <input id="questions" type="radio" name="programTab">
                        <label for="questions"><h2>よくある質問</h2></label>
                        <input id="review" type="radio" name="programTab">
                        <label for="review" class="js-scroll-target" data-scroll="review"><h2>クチコミ({{ $review_total }}件)</h2></label>
                    </div>
                    <section class="program__tab__item js-tabs-item condition">
                        <div class="program__tab__item__inner">
                            <section>
                                <div class="program__tab__item__ttl">ポイント獲得条件</div>
                                {!! $program->reward_condition !!}<br>
                                @php
                                    $report_expire_days = [2 => 150, 10 => 50];
                                @endphp
                            </section>
                        </div>
                    </section>
                    <section class="program__tab__item js-tabs-item questions">
                        <div class="program__tab__item__inner">
                        @if($questions->count() > 0)
                        @php
                        $questions = $questions->sortBy('disp_order');
                        @endphp
                            <section>
                                <div class="program__tab__item__ttl">よくある質問</div>
                                <dl class="question">
                                    @foreach ($questions as $key => $value)
                                    <dt>{{ $value['question'] }}</dt>
                                    <dd class="js-txt-limit">{!! $value['answer'] !!}</dd>
                                    @endforeach
                                </dl>
                            </section>
                            @endif
                            <section>
                                <div class="program__tab__item__ttl">注意事項</div>
                                <ul class="list">
                                    <li>ポイントの獲得は、上記「獲得目安」から若干前後することがあります。</li>
                                    <li>特段の記載がある場合を除き、各案件の「獲得予定ポイント」は商品やサービスのお申込み完了日時を基準といたします。GMOポイ活サイト内の「ポイントを獲得」ボタンを押した時点の判定ではございませんので、タイムセールの終了間近などは特にご注意ください。</li>
                                    <li>ポイントが獲得できない等のお問い合わせについては、当社が特に定めた場合を除いてすべて当サイトサポートにて受け付けております。直接広告元にお問い合わせされないようお願いいたします。</li>
                                    <li>一部、ポイント獲得に関する調査をお受けできない広告もございますので、あらかじめご了承ください。</li>
                                    <li>ポイントが獲得できない等のお問い合わせについて、ご購入、ご登録などが{{ $report_expire_days[$affiriate->asp_id] ?? 180 }}日以前である場合は調査等の対応ができかねますのでご了承ください。（※関係各所の参加確認データ保存期間を過ぎているため。）</li>
                                    <li>広告サイトより送付される登録完了・購入確認メール等は、会員様が広告に参加されたという重要な証拠書類となります。ポイント獲得完了まで大切に保管いただきますようお願いいたします。</li>
                                    <li>ポイント獲得できない等のお問い合わせの際、登録完了・購入確認メールを提示いただけない場合は、調査等の対応ができかねますので、ご注意ください。</li>
                                </ul>
                                <div class="btn__wrap">
                                    <a href="/support/?p=86" class="btn red solid" target="_blank"><span>必読</span>ポイント獲得に失敗しないために</a>
                                </div>
                            </section>
                        </div>
                    </section>
                    @if ($review_total < 1)
                    <section class="program__tab__item js-tabs-item review review__null">
                        <div class="program__tab__item__inner">
                            <section>
                                <div>
                                    <div class="program__tab__item__ttl review__null__ttl">{{ $program->title }}に関する口コミはまだありません。</div>
                                    <p>サービスに参加してクチコミを書いてみませんか？<br>クチコミ掲載で、初回<strong class="strong"> {{ $review_point_management ? $review_point_management->point : 7}}ポイント </strong>をプレゼント！</p>
                                </div>
                                <div class="img">
                                    {{ Tag::image('/images/programs/img_noreviews.png') }}
                                </div>
                            </section>
                        </div>
                    </section>
                    @else
                    <section class="program__tab__item js-tabs-item review">
                        <div class="program__tab__item__inner">
                            <section>
                                @include('elements.program_review_list', ['condition'=> (object) ['program_id' => $program->id, 'all' => ($review_total > 5 ? 0 : 1), 'sort' => 0, 'limit'=> 5]])
                            </section>
                        </div>
                    </section>
                    @endif
                </div>
                @php
                if (Auth::check() && $program->reviewsAccepted) {
                    $userReviewAccepted = $program->reviewsAccepted->where('user_id', Auth::user()->id);
                } else {
                    $userReviewAccepted = [];
                }
                @endphp
                <!-- form -->
                @dump($userReviewAccepted,$program->join_status);
                @if ($program->join_status != 4 && $program->join_status > 0 && isset($userReviewAccepted) && WrapPhp::count($userReviewAccepted) == 0)
                <section id="post" class="program__elem">
                    <h2 class="program__elem__ttl">クチコミ投稿</h2>
                    <div class="program__elem__cont">
                        <div class="program__elem__cont__inner">
                            <div class="programs_post__ttl">クチコミ投稿で<span class="large">{{ $review_point_management ? $review_point_management->point : 7}}</span><span>ポイント</span>ゲット！</div>
                            <p class="text--18 u-mt-20">評価</p>
                            {{ Tag::formOpen(['route' => 'reviews.confirm', 'method' => 'post', 'name' => 'review_form', 'id' => 'review_form', 'class' => 'programs_post__form']) }}
                            @csrf    
                            {{ Tag::formHidden('program_id', $program->id) }}
                                <div class="programs_post__form__select">
                                    @php
                                        $assessment_map = [5 => '★★★★★（とても良い）', 4 => '★★★★☆（良い）',
                                                    3 => '★★★☆☆（普通）', 2 => '★★☆☆☆（まあまあ）', 1 => '★☆☆☆☆（良くない）'];
                                    @endphp
                                    {{ Tag::formSelect('assessment', $assessment_map, old('assessment', 5), ['class' => 'blackstar', 'id' => 'review_stars']) }}
                                </div>

                                <div class="programs_post__form__textarea">
                                    {{ Tag::formTextarea('message', old('message', ''), ['name' => 'message', 'required' => 'required', 'cols' => '50', 'rows' => '8', 'minlength' => 100, 'maxlength' => 1000, 'id' => 'ReviewMessage', 'placeholder' => 'コメントを記入']) }}
                                    <div class="counter">
                                        <span class="show-count" id="ReviewMessageLength">0</span>文字
                                    </div>
                                </div>
                                <div class="programs_post__form__btn">
                                    {{ Tag::formSubmit('クチコミ投稿確認', ['class' => 'p_send btn_more btn orange--full', 'id' => 'post_review', 'value' => 'クチコミ投稿確認']) }}
                                </div>
                            {{ Tag::formClose() }}
                            @if ($errors->has('program_id'))
                            <p class="error"><span class="icon-attention"></span>{{ $errors->first('program_id') }}</p>
                            @endif
                            @if ($errors->has('assessment'))
                            <p class="error"><span class="icon-attention"></span>{{ $errors->first('assessment') }}</p>
                            @endif
                            @if ($errors->has('message'))
                            <p class="error"><span class="icon-attention"></span>{{ $errors->first('message') }}</p>
                            @endif
                            <p class="programs_post__caution text--15"><img src="{{ asset('/images/questions/ico_caution.svg')}}">クチコミ投稿の注意事項</p>
                            <p class="text--15">・クチコミは<b>100字以上</b>1,000文字以下で投稿できます。<br>
                            ・クチコミ投稿後、掲載された時点で、{{ $review_point_management ? $review_point_management->point : 7 }}ポイントをプレゼントいたします。<br>
                            ・同じサービスでクチコミをした場合、初回投稿分のみポイントが付与されます。<br>
							・<b>投稿いただいたクチコミは事前予告なく、本サービスの提供や広告、宣伝を目的として利用する可能性がございます。</b><br>
							・一度投稿したクチコミの編集、削除はできません。<br>
							・クチコミの掲載までには5営業日程お時間をいただく場合がありますので、ご了承ください。<br>
							・サービスが終了している場合、クチコミの投稿は出来ません。<br>
							・<b>以下のような内容のクチコミ投稿はご遠慮ください。</b><br>
							　<b>※ご自身の実体験をもとに投稿していただいたクチコミではないもの<br>
							　※事実と異なるもの、参考にならないと判断されるもの<br>
							　※公序良俗に反する内容や誹謗中傷・批判など不快な表現が含まれているもの<br>
							　※コピペなど転用<br>
							　※虚偽、重複、不備、いたずら</b></p>

                        </div>
                    </div>
                </section>
                @endif
                @if (Auth::check())
                <section class="program__elem share">
                    <div class="program__elem__head">
                        <h2 class="program__elem__ttl">{{ $program->title }}の広告をお友達にシェア！</h2>
                        <a href="/friends" class="textlink">お友達紹介について</a>
                    </div>
                    <div class="program__elem__cont">
                        <div class="bubble">シェアしたお友達がGMOポイ活に登録すると、あなたに <strong class="strong">500P</strong> プレゼント！</div>
                        <div class="program__elem__cont__inner">
                            <div class="share__cont js-share" data-url="{{ $share_url }}">
                                <ul class="share__btns icons">
                                    <li class="url">
                                        <input type="url" readonly >
                                    </li>
                                    <li class="copy">
                                        <a  href="javascript:void(0);"><i></i>リンクをコピー</a>
                                        <p class="success">クリップボードにコピーしました</p>
                                    </li>
                                    @if ($error_type != 1)
                                    <li class="line"><a href="https://social-plugins.line.me/lineit/share?url={{ $line_post_url . urlencode($share_url) }}"><img src="/images/common/ico_line.png"></a></li>
                                    <li class="x"><a href="https://twitter.com/intent/tweet?url={{ $x_post_url . urlencode($share_url) }}&text={{ $x_post_text }}&hashtags={{ $x_post_hashtags }}"><img src="/images/common/ico_tw.png"></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
                @endif
            </section>
            <aside class="program__contents__sub">
                @if ($program->multi_course == 1)
                    <section class="program__elem stepup">
                        <h2 class="program__elem__ttl">StepUpミッション</h2>
                        <div class="program__elem__cont">
                            <div class="program__elem__cont__inner">
                                <dl class="stepup__list">
                                    <dt class="stepup__list__head">
                                        <p>達成条件</p>
                                        <p>獲得P</p>
                                    </dt>
                                    @foreach ($program->point->point_list as $course_no => $point)
                                    @php
                                    $course = $point->course;
                                    @endphp
                                    <dd>
                                        <p>
                                            {!! '&#' . (9312 + $course_no) !!}
                                            {{ $course->course_name }}
                                        </p>
                                        @if ($point->fee_type == 2)
                                            購入額の
                                            @endif
                                        <p class="point">
                                            {{ $point->fee_label_s }}P
                                        </p>
                                    </dd>
                                    @endforeach
                                    <dd class="stepup__list__foot">
                                        <p></p>
                                        @if ($point->fee_type == 2)
                                            購入額の
                                        @endif
                                        <p class="point"><span>累計</span>
                                            {{ $program->point->fee_label_s }}P
                                        </p>
                                    </dd>
                                </dl>
                            </div>
                        </div>

                    </section>
                @endif
                @if(!empty($credit_card))
				<section class="program__elem">
					<h2 class="program__elem__ttl">カード情報</h2>
					<div class="program__elem__cont">
						<div class="program__elem__cont__inner">
							<ol class="program__elem__list">
								<li>
									<dl>
										<dt>国際ブランド</dt>
                                        @php
                                            $brand = $credit_card->brand_ids;
                                            $brand_map = config('map.credit_card_brand');
                                            $img = [
                                                1=>'logo_visa',
                                                2=>'logo_mastercard',
                                                3=>'logo_jcb',
                                                4=>'logo_amex',
                                                5=>'logo_discover',
                                                6=>'logo_diners'
                                            ];
                                        @endphp
                                        <dd>
                                            @foreach($brand_map as $key => $label)
                                                @if(in_array($key, $brand))
                                                <div class="brand"><img src="/images/programs/{{ $img[$key] }}.png" alt={{ $label }}></div>
                                                @endif
                                            @endforeach
                                        </dd>
									</dl>
								</li>
								<li>
									<dl>
										<dt>年会費</dt>
										<dd>
                                            @if ($credit_card->annual_free == 1)
                                            永年無料
                                            @else
                                            {{ $credit_card->annual_detail }}
                                            @endif
                                        </dd>
									</dl>
								</li>
								<li>
									<dl>
										<dt>ポイント還元率</dt>
										<dd>{{ $credit_card->back }}%</dd>
									</dl>
								</li>
								<li>
									<dl>
										<dt>電子マネー</dt>
                                        @php
                                        $emoney = $credit_card->emoney_ids;
                                        $emoney_map = config('map.credit_card_emoney');
                                        @endphp
                                        <dd>
                                        {{
                                            implode(', ', array_filter($emoney_map, function ($key) use ($emoney)
                                            {
                                                return in_array($key, $emoney);
                                            }, ARRAY_FILTER_USE_KEY))
                                        }}
                                        </dd>
									</dl>
								</li>
								<li>
									<dl>
										<dt>ETCカード</dt>
										<dd>{{ $credit_card->etc_detail }}</dd>
									</dl>
								</li>
								<li>
									<dl>
										<dt>ApplePay</dt>
                                        @php
                                        $apple_pay = $credit_card->apple_pay;
                                        $apple_map = config('map.credit_card_apple_pay');
                                        @endphp
                                        <dd>
                                            {{ $apple_map[$apple_pay] }}
                                        </dd>
									</dl>
								</li>
								<li>
									<dl>
										<dt>付帯保険</dt>
                                        @php
                                        $insurance = $credit_card->insurance_ids;
                                        $insurance_map = config('map.credit_card_insurance');
                                        @endphp
                                        <dd>
                                        {{
                                            implode(', ', array_filter($insurance_map, function ($key) use ($insurance)
                                            {
                                                return in_array($key, $insurance);
                                            }, ARRAY_FILTER_USE_KEY))
                                        }}
                                        </dd>
									</dl>
								</li>
							</ol>
                            <ul class="notes">
								<li>カード情報は変更される場合がございます。詳細は公式サイトをご確認ください。</li>
							</ul>
						</div>
					</div>
				</section>
                @endif
                @if (!empty($program->description) || !empty($program->detail) )
				<section class="program__elem desc">
					<h2 class="program__elem__ttl">{{ $program->title }}の広告概要</h2>
					<div class="program__elem__cont">
						<div class="program__elem__cont__inner">
							<div class="js-txt-limit">
                                <p>{{ $program->description }}</p>
                                <p>{!! $program->detail !!}</p>
								<a href="javascript:void(0);" class="js-more-btn">すべて見る</a>
							</div>
						</div>
					</div>
				</section>
                @endif
                @if (!empty($program->ad_title) || !empty($program->ad_detail) )
				<section class="program__elem desc">
					<h2 class="program__elem__ttl">広告主の概要</h2>
					<div class="program__elem__cont">
						<div class="program__elem__cont__inner">
							<div class="js-txt-limit">
								<h3 class="h--1">{{ $program->ad_title }}</h3>
								<p>{!! $program->ad_detail !!}</p>
								<a href="javascript:void(0);" class="js-more-btn">すべて見る</a>
							</div>
						</div>
					</div>
				</section>
                @endif
				<section class="program__elem">
					<h2 class="program__elem__ttl">この広告のカテゴリー</h2>
					<div class="program__elem__cont program__category">
                        <ul class="program__category__list">
                            @foreach ($program->tag_list as $tag)
                            <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object)['keyword_list' => [$tag]]), $tag) }}</li>
                            @endforeach
                        </ul>
					</div>
				</section>
			</aside>
		</div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/programs_detail.js') }}"></script>

</div><!--contents -->
@endsection

@section('layout.footer_notes')
@php
    $footNotes = 'guide';
@endphp
@include('inc.foot-notes', ['footNotes' => $footNotes])
@endsection