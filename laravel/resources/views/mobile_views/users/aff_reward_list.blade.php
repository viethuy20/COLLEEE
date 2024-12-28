@php
$base_css_type = 'mypage';
@endphp
@extends('layouts.default')

@section('layout.title', '獲得予定｜ポイントサイトならGMOポイ活')
@section('layout.description', '無料で簡単にお小遣いが貯められるポイントサイトGMOポイ活の獲得予定ポイント確認ページです。獲得予定のポイントを確認することができます。')

@section('layout.content')
<div class="inner">
    <div class="able_contents__ttl">
        <h2 class="contents__ttl">獲得予定</h2>
    </div>

    <ul class="able_list__description">
        <li>獲得予定への反映には、参加から数日お時間をいただく場合がございます。</li>
        <li>{{ Tag::link('/support/?p=33', '獲得予定に反映されない場合', ['class' => 'textlink'], null, false) }}</li>
        <li>{{ Tag::link('/support/?p=31', 'ポイント獲得の時期について', ['class' => 'textlink'], null, false) }}</li>
    </ul>

    @if($paginator->total() < 1)
    <p class="u-font-bold u-text-ac text--18 red">獲得予定はありません。</p>
    @else
    <div class="">
        <ul class="able_list__contents">
            @foreach($paginator as $aff_reward)
            @php
            // アフィリエイト情報
            $affiriate = $aff_reward->affiriate;
            // プログラム情報
            $program = $affiriate->program ?? null;
             // get asp
            $asp = !empty($affiriate->asp_id ) ? \App\Asp::find($affiriate->asp_id) : '';
            @endphp
            <li>
                <p class="text--12">{{ $aff_reward->actioned_at->format('Y-m-d') }}</p>
                <p class="able_list__contents__ttl">
                @if (isset($program->id) && $program->is_enable)
                    {{ Tag::link(route('programs.show', ['program' => $program]), $aff_reward->title) }}
                @else
                    {{ $aff_reward->title }}
                @endif
                </p>
                <div class="able_list_hisotry_list__contents__flex">
                    <div class="able_list_hisotry_list__contents__flex__l">
                        @if (!empty($program->id) && !empty($asp->type) && $asp->type == 1)
                            <a class="access_condition" href="#reward_condition{{$program->id}}" rel="modal:open">獲得条件</a>
                        @endif
                    </div>
                    <div class="able_list_hisotry_list__contents__flex__r">
                        <p class="able_list_hisotry_list__contents__point"><span class="red">{{ number_format($aff_reward->point) }}</span>ポイント</p>
                    </div>
                </div>
            </li>
            <!-- start model  -->
                @if (!empty($program->id))
                <div id="reward_condition{{$program->id}}" class="modal">
                    @if(!empty($program->getScheduleWithActionedAt($aff_reward->actioned_at)))
                        {!! $program->getScheduleWithActionedAt($aff_reward->actioned_at)->reward_condition !!}
                    @endif
                </div>
                @endif
            <!-- end model  -->
            @endforeach
        </ul>
    </div>
    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) { return route('users.reward_list', ['page' => $page]); }]) !!}
    @endif
    <div class="mypage__btn">
        {{ Tag::link(route('users.show'), 'マイページへ戻る') }}
    </div>
</div>
{{ Tag::script('https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', ['type' => 'text/javascript']) }}
{{ Tag::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css   ') }}
<style type="text/css">
    .modal a.close-modal {
        position: absolute;
        top: 5px;
        right: 5px;
        display: block;
        width: 30px;
        height: 30px;
        text-indent: -9999px;
        background-size: unset;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .modal {
        max-width: 700px;
        font-size: 12px;
        border-radius: 0px;
        padding: 40px 30px 15px 30px;
        max-height: calc(100vh - 400px);
        overflow-y: auto;
    }

    .access_condition {
        color: #f39800;
        font-size: 1.4rem;
        text-decoration: underline;
        text-decoration-color: #f39800;
    }

    .access_condition:hover {
        text-decoration: none;
    }
</style>
@endsection