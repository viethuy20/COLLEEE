@php
    $base_css_type = 'detail';
@endphp
@extends('layouts.default')

@section('layout.title', $program->title . ' | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'GMOポイ活,広告')
@section('layout.description', $program->title . 'の詳細 | ここからの利用で、1P=1円相当のポイントが貯まります。')
@section('layout.structure_data_review')
    @if (WrapPhp::count($program->reviewsAccepted) > 0 && isset($data))
        <script type="application/ld+json">
            {!! $data !!}
        </script>
    @endif
@endsection
@section('layout.content')

    <section class="contents">
        <div class="contents__box">
            <h1 class="programs_error__title">{{ $program->title }}</h1>
            @switch ($error_type)
                @case (1)
                    <p class="programs_error__txt">
                        <span>アクセスありがとうございます。<br />
                            この広告は終了させていただきました。<br />
                            ご了承ください。<br />
                        </span>
                    </p>
                    <div class="programs_detail__btn__pink">{{ Tag::link(route('website.index'), 'TOPへ', null, null, false) }}</div>
                @break

                @case (2)
                    @php
                        $pc_device = 'PC専用広告です。';
                        $ios_device = 'iOS専用広告です。';
                        $android_device = 'android専用広告です。';
                        $ios_android_device = 'iOS・android専用広告です。';
                    @endphp
                    <p class="programs_error__txt">
                        <span>
                            アクセス頂いた広告は<br />
                            @switch($program->devices)
                                @case(1)
                                    {{ $pc_device }}
                                @break

                                @case(2)
                                    {{ $ios_device }}
                                @break

                                @case (4)
                                    {{ $android_device }}
                                @break

                                @case (6)
                                    {{ $ios_android_device }}
                                @break
                            @endswitch
                        </span>
                    </p>
                    @php
                        // アフィリエイト
                        $affiriate = $program->affiriate;
                        // ポイント
                        $point = $program->point;
                        // タイムセール
                        $is_time_sale = $point->time_sale;
                    @endphp

                    @if ($program->multi_course == 0)
                        @if ($program->programStock)
                            @if ($program->programStock->stock_cv && $program->programStock->stock_cv > 0)
                                <div class="program_stock_detail_content_pc">
                                    <div class="program_stock_detail_pc">
                                        参加可能人数　残り{{ $program->programStock->stock_cv ?? 0 }}人
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="programs_detail__box">
                            <div class="programs_detail__box__l">
                                <div class="programs_detail__box__thumb">{{ Tag::image($affiriate->img_url, $program->title) }}
                                </div>

                                @if ($program->multi_join == 1)
                                    <div class="programs_detail__box__caption">何度でもOK</div>
                                @elseif ($program->join_status == 1)
                                    <div class="programs_detail__box__caption">獲得済</div>
                                @elseif ($program->join_status == 2)
                                    <div class="programs_detail__box__caption">獲得予定</div>
                                @elseif ($program->join_status == 0)
                                    <div class="programs_detail__box__caption">未参加</div>
                                @endif

                                @if ($has_user_program)
                                    <div class="programs_detail__box__btn__gray">
                                        {{ Tag::link(route('users.remove_program', ['program' => $program]), 'お気に入り削除', ['class' => 'save_scroll'], null, false) }}
                                    </div>
                                @else
                                    <div class="programs_detail__box__btn">
                                        {{ Tag::link(route('users.add_program', ['program' => $program]), 'お気に入り追加', ['class' => 'save_scroll'], null, false) }}
                                    </div>
                                @endif

                                <div class="programs_detail__box__twitter">
                                    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button"
                                        data-lang="ja" data-text="{{ $program->title }} | GMOポイ活のオススメ広告"
                                        data-hashtags="ポイ活,ポイントサイト,お得,GMOポイ活" data-show-count="false" data-size="large">Tweet</a>
                                    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                </div><!-- programs_detail__box__twitter -->
                            </div><!-- programs_detail__box__l -->

                            <div class="programs_detail__box__r">
                                <p class="programs_detail__box__txt">{{ $program->fee_condition }}</p>
                                @if ($is_time_sale)
                                    <!--タイムセール処理の分岐 -->
                                    <p class="programs_detail__box__point__countdown counter"
                                        timestamp="{{ $point->stop_at->timestamp }}">
                                        残り
                                        <span class="countDownDay"></span>
                                        日
                                        <span class="countDownTime"></span>
                                    </p>
                                    <p class="programs_detail__box__point__linethrough">
                                        {{ $point->previous_point->fee_label }}P</p>
                                @endif
                                <p class="programs_detail__box__point">
                                    @if ($is_time_sale)
                                        <!--タイムセール処理の分岐 -->
                                        <img src="{{ asset('/images/common/ico_arrow.svg') }}">
                                    @endif

                                    @if ($point->fee_type == 2)
                                        購入額の
                                    @endif

                                    <span class="large">{{ $point->fee_label_s }}</span>
                                    <span>P</span>
                                </p>
                                <ul class="programs_detail__box__kuchikomi">
                                    <li>
                                        <i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg') }}"></i>
                                        総合評価：
                                        @if ($program->review_avg > 0)
                                            <a href="#reviews">{{ $program->review_avg }}</a>
                                        @else
                                            -
                                        @endif
                                    </li>
                                    <li>
                                        <i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg') }}"></i>
                                        口コミ：
                                        @if ($program->review_total > 0)
                                            <a href="#reviews">{{ number_format($program->review_total) }}件</a>
                                        @else
                                            0件
                                        @endif
                                    </li>
                                </ul>

                                <ul class="programs_detail__box__tag">
                                    @foreach ($program->tag_list as $tag)
                                        <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => [$tag]]), $tag) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div><!-- programs_detail__box__r -->
                        </div><!-- programs_detail__box -->

                        <div class="programs_error">
                            <p class="programs_error__headline">
                                <span>
                                    @switch($program->devices)
                                        @case(1)
                                            {{ $pc_device }}
                                        @break

                                        @case(2)
                                            {{ $ios_device }}
                                        @break

                                        @case (4)
                                            {{ $android_device }}
                                        @break

                                        @case (6)
                                            {{ $ios_android_device }}
                                        @break
                                    @endswitch
                                </span>QRコードからポイント獲得ページを開く
                            </p>
                            <div class="programs_error__qrcode">
                                {{ Tag::image(route('qr.image') . '?' . http_build_query(['d' => route('programs.show', ['program' => $program, 'rid' => 32]), 's' => 164]), '') }}
                            </div>
                            <p class="programs_error__txt__slim">QRコードの商標はデンソーウェーブの登録商標です。</p>
                        </div>

                        <dl class="programs_detail__chart">
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
                            <dt>獲得までの期間</dt>
                            <dd>{{ config('map.accept_days')[$affiriate->accept_days] }}</dd>
                            @if ($point->bonus == 1)
                                <dt>ボーナス</dt>
                                <dd>友達紹介&nbsp;
                                    @php
                                        $user_rank_map = [2 => ['class' => 'silver', 'label' => 'ランク特典+3%'], 3 => ['class' => 'gold', 'label' => 'ランク特典+10%']];
                                        $user_rank = Auth::check() ? Auth::user()->rank : 0;
                                    @endphp
                                    @if (isset($user_rank_map[$user_rank]))
                                        {{ $user_rank_map[$user_rank]['label'] }}
                                    @else
                                        ランク特典非対象
                                    @endif
                                </dd>
                            @endif
                            <dt>獲得条件</dt>
                            <dd>{{ $program->fee_condition }} ※本ページ下部に詳細あり</dd>
                            <dt>注意事項</dt>
                            <dd>本ページ下部に記載</dd>
                        </dl>
                    @elseif ($program->multi_course == 1)
                        @if ($program->programStock)
                            @if ($program->programStock->stock_cv && $program->programStock->stock_cv > 0)
                                <div class="program_stock_detail_content_pc">
                                    <div class="program_stock_detail_pc">
                                        参加可能人数　残り{{ $program->programStock->stock_cv ?? 0 }}人
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="programs_detail__box">
                            <div class="programs_detail__box__l">
                                <div class="programs_detail__box__thumb">
                                    {{ Tag::image($affiriate->img_url, $program->title) }}</div>
                                @if ($program->multi_join == 1)
                                    <div class="programs_detail__box__caption">何度でもOK</div>
                                @elseif ($program->join_status == 1)
                                    <div class="programs_detail__box__caption">獲得済</div>
                                @elseif ($program->join_status == 2)
                                    <div class="programs_detail__box__caption">獲得予定</div>
                                @elseif ($program->join_status == 0)
                                    <div class="programs_detail__box__caption">未参加</div>
                                @endif
                            </div><!-- programs_detail__box__l -->

                            <div class="programs_detail__box__r">
                                <p class="programs_detail__box__txt">{{ $program->fee_condition }}</p>
                                @if ($is_time_sale)
                                    <!--タイムセール処理の分岐 -->
                                    <p class="programs_detail__box__point__countdown counter"
                                        timestamp="{{ $point->stop_at->timestamp }}">
                                        残り
                                        <span class="countDownDay"></span>
                                        日
                                        <span class="countDownTime"></span>
                                    </p>
                                    <p class="programs_detail__box__point__linethrough">
                                        {{ $point->previous_point->fee_label }}P</p>
                                @endif
                                <p class="programs_detail__box__point">
                                    @if ($is_time_sale)
                                        <!--タイムセール処理の分岐 -->
                                        <img src="{{ asset('/images/common/ico_arrow.svg') }}">
                                    @endif
                                    @if ($point->fee_type == 2)
                                        購入額の
                                    @endif
                                    <span class="large">{{ $point->fee_label_s }}</span>
                                    <span>P</span>
                                </p>
                            </div><!-- programs_detail__box__r -->
                        </div><!-- programs_detail__box -->

                        <div class="programs_error">
                            <p class="programs_error__headline">
                                <span>
                                    @switch($program->devices)
                                        @case(1)
                                            {{ $pc_device }}
                                        @break

                                        @case(2)
                                            {{ $ios_device }}
                                        @break

                                        @case (4)
                                            {{ $android_device }}
                                        @break

                                        @case (6)
                                            {{ $ios_android_device }}
                                        @break
                                    @endswitch
                                </span>QRコードからポイント獲得ページを開く
                            </p>
                            <div class="programs_error__qrcode">
                                {{ Tag::image(route('qr.image') . '?' . http_build_query(['d' => route('programs.show', ['program' => $program, 'rid' => 32]), 's' => 164]), '') }}
                            </div>
                            <p class="programs_error__txt__slim">QRコードの商標はデンソーウェーブの登録商標です。</p>
                        </div>

                        <div class="programs_course__box u-mt-20">
                            <p class="programs_course__box__txt">【StepUpミッション】</p>
                            <ul class="programs_course__box__list">
                                <li class="programs_course__box__list__item">
                                    <p class="programs_course__box__txt">
                                        達成条件
                                    </p>
                                    獲得P
                                </li>
                                <hr class="programs_course__box__separetor">
                                @foreach ($program->point->point_list as $course_no => $point)
                                    @php
                                        $course = $point->course;
                                    @endphp
                                    <li class="programs_course__box__list__item">
                                        <span class="programs_course__box__txt">
                                            {!! '&#' . (9312 + $course_no) !!}
                                            {{ $course->course_name }}
                                        </span>
                                        <span class="programs_course__box__list__item__point">
                                            @if ($point->fee_type == 2)
                                                購入額の
                                            @endif
                                            <p class="programs_course__box__point">
                                                {{ $point->fee_label_s }}P
                                            </p>
                                        </span>
                                    </li>
                                @endforeach
                                <hr class="programs_course__box__separetor">
                                <li class="programs_course__box__list__item__footer">
                                    <span class="programs_course__box__list__item__total">
                                        累計&nbsp;
                                        <p style="font-weight: 700;">
                                            @if ($point->fee_type == 2)
                                                購入額の
                                            @endif
                                        </p>
                                        <p class="programs_course__box__point">
                                            {{ $program->point->fee_label_s }}P
                                        </p>
                                    </span>
                                </li>
                            </ul>
                        </div><!-- programs_course__box -->

                        <dl class="programs_detail__chart">
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
                            <dt>獲得までの期間</dt>
                            <dd>{{ config('map.accept_days')[$affiriate->accept_days] }}</dd>
                            @if ($point->bonus == 1)
                                <dt>ボーナス</dt>
                                <dd>友達紹介&nbsp;
                                    @php
                                        $user_rank_map = [2 => ['class' => 'silver', 'label' => 'ランク特典+3%'], 3 => ['class' => 'gold', 'label' => 'ランク特典+10%']];
                                        $user_rank = Auth::check() ? Auth::user()->rank : 0;
                                    @endphp
                                    @if (isset($user_rank_map[$user_rank]))
                                        {{ $user_rank_map[$user_rank]['label'] }}
                                    @else
                                        ランク特典非対象
                                    @endif
                                </dd>
                            @endif
                            <dt>獲得条件</dt>
                            <dd>{{ $program->fee_condition }} ※本ページ下部に詳細あり</dd>
                            <dt>注意事項</dt>
                            <dd>本ページ下部に記載</dd>
                        </dl>

                        <div class="programs_detail__box__l">
                            @if ($has_user_program)
                                <div class="programs_detail__box__btn__gray">
                                    {{ Tag::link(route('users.remove_program', ['program' => $program]), 'お気に入り削除', ['class' => 'save_scroll'], null, false) }}
                                </div>
                            @else
                                <div class="programs_detail__box__btn">
                                    {{ Tag::link(route('users.add_program', ['program' => $program]), 'お気に入り追加', ['class' => 'save_scroll'], null, false) }}
                                </div>
                            @endif
                            <div class="programs_detail__box__twitter">
                                <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-lang="ja"
                                    data-text="{{ $program->title }} | GMOポイ活のオススメ広告" data-hashtags="ポイ活,ポイントサイト,お得,GMOポイ活"
                                    data-show-count="false" data-size="large">Tweet</a>
                                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                            </div><!-- programs_detail__box__twitter -->
                        </div><!-- programs_detail__box__l -->

                        <div class="programs_detail__box__r">
                            <ul class="programs_detail__box__kuchikomi">
                                <li>
                                    <i><img src="{{ asset('/images/programs/ico_kuchikomi_star.svg') }}"></i>
                                    総合評価：
                                    @if ($program->review_avg > 0)
                                        <a href="#reviews">{{ $program->review_avg }}</a>
                                    @else
                                        -
                                    @endif
                                </li>
                                <li>
                                    <i><img src="{{ asset('/images/programs/ico_kuchikomi_chat.svg') }}"></i>
                                    口コミ：
                                    @if ($program->review_total > 0)
                                        <a href="#reviews">{{ number_format($program->review_total) }}件</a>
                                    @else
                                        0件
                                    @endif
                                </li>
                            </ul>
                            <ul class="programs_detail__box__tag">
                                @foreach ($program->tag_list as $tag)
                                    <li>{{ Tag::link(\App\Search\ProgramCondition::getStaticListUrl((object) ['keyword_list' => [$tag]]), $tag) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div><!-- programs_detail__box__r -->
                    @endif
                @break
            @endswitch
        </div><!--/contents__box-->

        <div class="contents__box">
            <h2 class="contents__ttl">{{ $program->title }}の広告概要</h2>
            <p class="text--15 u-mt-20">{{ $program->description }}</p>
            <div class="contents__box__ad">{!! $program->detail !!}</div>
        </div><!-- contents__box -->

        <div class="contents__box">
            <h2 class="contents__ttl">獲得条件・注意事項</h2>
            <div class="contents__box__ad">
                {!! $program->reward_condition !!}<br>
                @php
                    $affiriate = $program->affiriate;
                    $report_expire_days = [2 => 150, 10 => 50];
                @endphp
                ポイントの獲得は、上記「獲得目安」から若干前後することがあります。<br>
                特段の記載がある場合を除き、各案件の「獲得予定ポイント」は商品やサービスのお申込み完了日時を基準といたします。GMOポイ活サイト内の「ポイントを獲得」ボタンを押した時点の判定ではございませんので、タイムセールの終了間近などは特にご注意ください。<br>
                ポイントが獲得できない等のお問い合わせについては、当社が特に定めた場合を除いてすべて当サイトサポートにて受け付けております。直接広告元にお問い合わせされないようお願いいたします。<br>
                一部、ポイント獲得に関する調査をお受けできない広告もございますので、あらかじめご了承ください。<br>
                ポイントが獲得できない等のお問い合わせについて、ご購入、ご登録などが{{ $report_expire_days[$affiriate->asp_id] ?? 180 }}日以前である場合は調査等の対応ができかねますのでご了承ください。（※関係各所の参加確認データ保存期間を過ぎているため。）<br>
                @if ($affiriate->asp_id == 10)
                    獲得予定に記載されていた広告が消えた場合、ポイント配付の条件を満たしていないと広告主側で判定された可能性がございます。<br>
                    その際、判定から20日以内にお問い合わせでない場合は調査等の対応ができかねますので、合わせてご了承ください。<br>
                @endif
                広告サイトより送付される登録完了・購入確認メール等は、会員様が広告に参加されたという重要な証拠書類となります。ポイント獲得完了まで大切に保管いただきますようお願いいたします。<br>
                ポイント獲得できない等のお問い合わせの際、登録完了・購入確認メールを提示いただけない場合は、調査等の対応ができかねますので、ご注意ください。<br>
            </div>
            <div class="programs_detail__btn__wrap">
                <div class="programs_detail__btn__yellow">
                    {{ Tag::link('/support/?p=86', '【重要】参加に際してのご注意', null, null, false) }}
                </div>
                <div class="programs_detail__btn__pink">
                    {{ Tag::link(route('inquiries.index', ['inquiry_id' => 3]) . '?title=' . rawurlencode($program->title), 'この広告についてのお問合せ', null, null, false) }}
                </div>
            </div>
        </div><!-- contents__box -->

<script>
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
        var textLength = reviewMessage.val() ? reviewMessage.val().length : 0;

        $('#ReviewMessageLength').text(textLength);
        if (textLength < 100 || textLength > 1000) {
            reviewMessage.css({'backgroundColor':'#ffcccc'});
        } else {
            reviewMessage.css({'backgroundColor':'#f0f0f0'});
        }
    }
</script>

        @php
        if (Auth::check() && $program->reviewsAccepted) {
            $userReviewAccepted = $program->reviewsAccepted->where('user_id', Auth::user()->id);
        } else {
            $userReviewAccepted = [];
        }
        @endphp
        <!-- 口コミ投稿フォーム -->
        @if ($program->join_status != 4 && $program->join_status > 0 && isset($userReviewAccepted) && WrapPhp::count($userReviewAccepted) == 0)
            <h2 class="contents__ttl u-mt-40">口コミ投稿</h2>
            <div class="contents__box">
                <h2 class="programs_post__ttl">口コミ投稿で<span class="large">{{ $review_point_management ? $review_point_management->point : 5}}</span><span>ポイント</span>ゲット！</h2>
                <h3 class="text--18 u-mt-20">評価</h3>
                {{ Tag::formOpen(['route' => 'reviews.confirm', 'method' => 'post', 'name' => 'review_form', 'id' => 'review_form', 'class' => 'programs_post__form']) }}
                @csrf
                {{ Tag::formHidden('program_id', $program->id) }}
                <div class="programs_post__form__select">
                    @php
                        $assessment_map = [5 => '★★★★★（とても良い）', 4 => '★★★★☆（良い）', 3 => '★★★☆☆（普通）', 2 => '★★☆☆☆（まあまあ）', 1 => '★☆☆☆☆（良くない）'];
                    @endphp
                    {{ Tag::formSelect('assessment', $assessment_map, old('assessment', 5), ['class' => 'blackstar', 'id' => 'review_stars']) }}
                </div>

                <div class="programs_post__form__textarea">
                    {{ Tag::formTextarea('message', old('message', ''), ['required' => 'required', 'cols' => '50', 'rows' => '8', 'minlength' => 100, 'maxlength' => 1000, 'id' => 'ReviewMessage', 'placeholder' => 'コメントを記入']) }}
                    <div class="counter">
                        <span class="show-count" id="ReviewMessageLength">0</span>文字
                    </div>
                </div>
                <div class="programs_post__form__btn">
                    {{ Tag::formSubmit('口コミ投稿確認', ['class' => 'p_send btn_more', 'id' => 'post_review']) }}
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
                <p class="programs_post__caution text--15"><img
                        src="{{ asset('/images/questions/ico_caution.svg') }}">口コミ投稿の注意事項</p>
                <p class="text--15">・口コミは<b>100字以上</b>1,000文字以下で投稿できます。<br>
                    ・口コミ投稿後、掲載された時点で、{{ $review_point_management ? $review_point_management->point : 5}}ポイントをプレゼントいたします。<br>
                    ・同じサービスで口コミをした場合、初回投稿分のみポイントが付与されます。<br>
                    ・<b>投稿いただいた口コミは事前予告なく、本サービスの提供や広告、宣伝を目的として利用する可能性がございます。</b><br>
                    ・一度投稿した口コミの編集、削除はできません。<br>
                    ・口コミの掲載までには5営業日程お時間をいただく場合がありますので、ご了承ください。<br>
                    ・サービスが終了している場合、口コミの投稿は出来ません。<br>
                    ・<b>以下のような内容の口コミ投稿はご遠慮ください。</b><br>
                    <b>※ご自身の実体験をもとに投稿していただいた口コミではないもの<br>
                        ※事実と異なるもの、参考にならないと判断されるもの<br>
                        ※公序良俗に反する内容や誹謗中傷・批判など不快な表現が含まれているもの<br>
                        ※コピペなど転用<br>
                        ※虚偽、重複、不備、いたずら</b>
                </p>
            </div>
        @endif

        @php
            // ここから下はみんなが参考にした口コミ
            $helpful_review_list = \App\Review::ofProgram($program->id)
                ->ofHelpful()
                ->ofSort(1)
                ->take(3)
                ->get();
        @endphp

        @if (!$helpful_review_list->isEmpty())
            <div class="programs_detail__review">
                <h2 id="reviews" class="contents__ttl">みんなが参考にした口コミ</h2>
            </div>
            <div class="">
                <ul class="programs_detail__list">
                    @foreach ($helpful_review_list as $review)
                        <li>
                            <div class="programs_detail__list__head">
                                <div class="programs_detail__list__name"><!--ユーザー名-->
                                    @if (isset($review->user))
                                        {{ Tag::link(route('reviews.reviewer', ['user' => $review->user]), $review->reviewer) }}
                                    @else
                                        {{ $review->reviewer }}
                                    @endif
                                </div><!--programs_detail__list__name-->
                                <div class="programs_detail__list__star"><!--★-->
                                    <ul>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>{{ Tag::image($i <= $review->assessment ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}
                                            </li>
                                        @endfor
                                        <p class="programs_detail__list__star__txt">（{{ $review->assessment }}/5）</p>
                                    </ul>
                                </div><!--programs_detail__list__star-->
                            </div>
                            <p class="text--15">{{ $review->message }}</p>
                            <time datetime="{{ $review->created_at->format('Y-m-d') }}"
                                class="programs_detail__list__data">{{ $review->created_at->format('Y-m-d H:i') }}</time>
                        </li>
                    @endforeach
                </ul>
            </div><!--""-->
        @endif

        <!-- みんなの口コミ -->
        @php
            $review_total = \App\Review::ofProgram($program->id)->count();
        @endphp

        @if ($review_total < 1)
            <!--口コミがまだ投稿されていない場合-->
            <div class="programs_detail__review">
                <h2 class="contents__ttl">{{ $program->title }}の口コミ一覧</h2>
            </div>
            <div class="">
                <ul class="programs_detail__list js_accordion">
                    <li>
                        <div class="programs_detail__list__head">
                            <div class="programs_detail__list__name">{{ $program->title }}に関する口コミはまだありません。</div>
                        </div>
                        <div class="programs_detail__list__bottom__img">{{ Tag::image('/images/img_noreviews.svg') }}
                        </div>
                        <p class="text--15">サービスに参加して口コミを書いてみませんか？</p>
                        <p class="text--15">口コミが掲載されると、初回<span class="no-review">{{ $review_point_management ? $review_point_management->point : 5}}ポイント</span>をプレゼント！</p>
                    </li>
                </ul>
            </div><!--""-->
        @else
            @include('elements.program_review_list', [
                'condition' => (object) [
                    'program_id' => $program->id,
                    'all' => $review_total > 5 ? 0 : 1,
                    'sort' => 0,
                    'limit' => 5,
                ],
            ])
        @endif

        <meta name="csrf-token" content="{{ csrf_token() }}">
        

    </section><!--/contents-->
@endsection

