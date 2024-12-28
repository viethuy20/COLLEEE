<!-- 特殊レイアウト廃止デフォルトのレイアウトに修正 -->
<!-- extends('layouts.question') -->
<!-- section('layout.use_question_daily', true) -->
@php
$base_css_type = 'question';
@endphp
@extends('layouts.default')
@section('layout.head')

<script type="text/javascript"><!--
$(function(){
    $('.questions:not(.questions:first-of-type)').css('display','none');
    $('.daily__form__label_not_login input[type=radio]').attr('disabled', true);
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
            {{ Tag::link(route('questions.index'), 'アンケート') }}
        </li>
        <li>
            GMOポイ活アンケート
        </li>
    </ol>
</section>
@endsection

@section('layout.title', 'アンケート | ポイントサイトならGMOポイ活')
@section('layout.keywords', 'アンケート,ポイント,無料,簡単,毎日')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪')
@section('layout.content')

<section class="contents">
    <h1><img src="{{ asset('/images/questions/questions_ttl.png')}}" alt="ポイントが貯まる！ GMOポイ活アンケート"></h1>

    @php
    $program_recommend = \App\Content::ofSpot(\App\Content::SPOT_PROGRAM_RECOMMEND)
        ->orderBy('priority', 'asc')
        ->first();
    @endphp
    @if (isset($program_recommend->program))
    <!-- <section class="froms">
        <h2>スタッフおすすめ広告</h2>
        <div class="contentsbox"><a href="{{ route('programs.show', ['program' => $program_recommend->program, 'rid' => '24']) }}"><div class="reco1">
            <div class="eins"><span>注目！</span></div> --><!--eins-->
    <!--        <div class="zwei">{{ $program_recommend->title }}</div> --><!--/zwei-->
    <!--         <div class="drei"><span>{{ $program_recommend->program->point->fee_label }}</span>ポイント</div> --><!--/drei-->
    <!--     </div></a></div> --><!--/reco1-->
    <!-- </section> -->
    @endif

    @php
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent(request()->header('User-Agent'));
    @endphp


    <div class="contents__box">
        <h2 class="contents__ttl orange">いま聞きたい！GMOポイ活アンケート</h2>
        @php
            // GMOポイ活アンケート取得
            $question = \App\Question::ofEnableAnswer()
                ->where('type', '=', 1)
                ->first();
        @endphp
        @if (!isset($question->id))
            <!--準備中の表示-->
            <div class="daily__que">
                <div class="daily__que__img"><img src="{{ asset('/images/img_preparation.svg')}}" alt="準備中"></div>
                <div class="daily__que__txt"><span>GMOポイ活アンケートは準備中です。<br />開催までしばらくお待ちください。</span></div>
            </div>
        @else
            @php
                $answer_map = $question->answer_map;
                $question_url = route('questions.show', ['question' => $question]);
            @endphp
            <div class="daily__que">
                <div class="daily__que__img"><img src="{{ asset('/images/questions/ico_question.svg')}}" alt="Q"></div>
                <div class="daily__que__txt">{{ $question->title }}</div>
            </div>
            @if ($question->answer_total > 0)
                <p class="u-text-right u-mt-20">&nbsp;{{ Tag::link($question_url.'#tocomments', 'みんなの回答・コメントを見る', ['class' => 'textlink']) }}</p>
            @endif
            @if (Auth::check())
                @php
                    $user_answer = \App\UserAnswer::where('question_id', '=', $question->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
                @endphp
                    {{ Tag::formOpen(['url' => route('questions.answer'),'class' => 'daily__form']) }}
                    @csrf
                    {{ Tag::formHidden('question_id', $question->id) }}
                    <div class="daily__form__label">
                        @foreach($answer_map as $answer_id => $label)
                        <label class="which">{{ Tag::formRadio('answer_id', $answer_id, (isset($user_answer->answer_id) && $user_answer->answer_id == $answer_id)) }}{{ $label }}</label>
                        @endforeach
                    </div>
                    @if (isset($user_answer->id))
                        <!--回答済みの場合-->
                        {{ Tag::formButton('回答済みです') }}
                    @else
                        <p class="daily__form__txt">回答で<span>1ポイント</span>GET！</p>
                        {{ Tag::formSubmit('上記内容で回答する', ['class' => 'getapoint']) }}
                    @endif
                {{ Tag::formClose() }}
            @else
                <!--非ログインの場合-->
                <div class="daily__form__label daily__form__label_not_login">
                    @foreach($answer_map as $answer_id => $label)
                        <label class="which">{{ Tag::formRadio('answer_id', $answer_id)}}{{$label}}</label>
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    @if ($agent->isTablet())
        @if (isset($mainte_message))
        <!--メンテナンス中の表示-->
        <div class="contents__box">
            <div class="daily__que">
                <div class="daily__que__img"><img src="{{ asset('/images/img_preparation.svg')}}" alt="準備中"></div>
                <div class="daily__que__txt"><span>{!! nl2br(e($mainte_message)) !!}</span></div>
            </div>
        </div>
        @elseif (isset($paginator) && !$paginator->isEmpty())
            <!--for tablet-->
            <div class="contents__box">
                <h2 class="contents__ttl orange">開催中のアンケート</h2>
                <p class="u-text-right text--12">{{ number_format($paginator->total()) }}件</p>
                <p class="u-mt-20 text--14">リンクをクリックすると、外部企業のアンケートサイトに接続いたします。<br>
                    会員様が当該アンケートサイトにおいて回答される内容は、当該外部企業が取得し、管理を行うものであり、弊社は一切取得及び関与いたしません。<br>
                    <br>
                    <span class="u-font-bold">【定期メンテナンスについて】</span><br>
                    毎月第2水曜日の午前4時～7時に「定期メンテナンス」が実施されます。メンテナンス中はアンケートにご参加いただけませんので、ご了承ください。
                </p>
                <hr class="u-mt-20 bd_gray">
                <p class="u-mt-20 text--14">
                    <span class="u-font-bold red">スマートフォン版アンケートの新ルールにつきまして</span><br>
                    2021/2/1<br>
                    <br>
                    2021年2月1日（月）より、アンケート回答でのポイント獲得のルールが変更となりました。<br>
                    <br>
                    <span class="u-font-bold">従来のルール</span><br>
                    アンケート1回答につき1ポイント獲得<br>
                    <br>
                    <span class="u-font-bold">変更後のルール</span><br>
                    アンケート1回答につき1スタンプ獲得<br>
                    5スタンプ獲得するごとに1ポイント獲得<br>
                    <br>
                    詳細につきましては{{ Tag::link('/support/?p=1347', 'こちらのページ', ['target' => '_blank', 'class' => 'textlink orange', 'rel' => 'noopener',]) }}でご確認ください。
                </p>
                <hr class="u-mt-20 bd_gray">
                <div class="u-mt-20 hold__list">
                    <ul>
                        @foreach ($paginator as $estlier_question)
                        @php
                        $url = route('asps.click', ['asp' => \App\Asp::ESTLIER_TYPE]).'?'.http_build_query(['ganre' => $estlier_question->ganre, 'enq_date' => $estlier_question->enq_date]);
                        $joined = Auth::check() && ($estlier_question->already == 1);
                        @endphp
                        @if ($joined)
                        <li class="hold__list__answered">
                        @else
                        <li class="hold__list__question"><a href="{{ $url }}">
                        @endif
                            <div class="ttl">{{ $estlier_question->enq_title }}</div><!--/title-->
                            <div class="limit"><p class="toanswer">
                                @if ($joined)
                                回答済み
                                @else
                                回答期限：<br>{{ $estlier_question->expire_at->format('Y-m-d H:i') }}
                                @endif
                            </p></div><!--/btn-->
                            <div class="txt">{{ $estlier_question->ganre_label }}</div><!--/number-->
                        @if ($joined)
                        </li><!--/view_question-->
                        @else
                        </a></li><!--/view_question-->
                        @endif
                        @endforeach
                    </ul><!--/questions-->
                </div>
                {!! $paginator->render('elements.pager', ['pageUrl' => function($page){ return $page == 1 ? route('questions.index') : route('questions.list', ['page' => $page]); }]) !!}
            </div>
        @endif
    @endif

    <!-- GMOポイ活アンケートの結果 -->
    @php
        $now = \Carbon\Carbon::now();
        // アーカイブ取得
        $old_question_builder = \App\Question::ofEnable()
            ->where('type', '=', 1)
            ->where('start_at', '<=', $now)
            ->orderBy('start_at', 'desc')
            ->take(5);

        $old_question_list = $old_question_builder->get();
    @endphp
    @if (!$old_question_list->isEmpty())
        <div class="contents__box">
            <h2 class="contents__ttl orange">GMOポイ活アンケートの結果</h2><!--本日から遡って5日分表示-->
            <div class="result__list">
                <ul>
                    @foreach ($old_question_list as $old_question)
                        <li>
                            <a href="{{ route('questions.show', ['question' => $old_question]) }}">
                                <p class="data">{{ $old_question->start_at->format('Y-m-d') }}</p>
                                <p class="ttl">{{ $old_question->title }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- GMOポイ活アンケートバックナンバー -->
    @php
        // バックナンバー作成
        $backnumber_end_at = Carbon\Carbon::yesterday()->endOfDay()->startOfMonth();
        $backnumber_start_at = Carbon\Carbon::parse('2017-12-01 00:00:00');
        $backnumber_map = [];
        while (true) {
            $data = $backnumber_map[$backnumber_end_at->year] ?? [];
            $data[] = $backnumber_end_at->month;
            $backnumber_map[$backnumber_end_at->year] = $data;
            $backnumber_end_at->subMonths(1);
            if ($backnumber_start_at->gt($backnumber_end_at)) {
                break;
            }
        }
    @endphp
        <div class="contents__box">
            <h2 class="contents__ttl orange">GMOポイ活アンケートバックナンバー</h2>
            <div class="backnumber">
                <dl>
                @foreach ($backnumber_map as $year => $month_list)
                    <dt>{{ $year }}年</dt>
                    <dd>
                        <ul>
                            @foreach ($month_list as $month)
                            <li>{{ Tag::link(route('questions.monthly', ['target' => sprintf("%04d%02d", $year, $month)]), $month.'月') }}</li>
                            @endforeach
                        </ul>
                    </dd>
                </dl>
                <dl>
                @endforeach
                </dl>
            </div>
        </div>

    @include('elements.pop_recipe_list')
</section>

@endsection
