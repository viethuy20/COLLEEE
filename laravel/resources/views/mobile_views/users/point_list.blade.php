@php
$base_css_type = 'mypage';
@endphp
@extends('layouts.default')

@section('layout.title', '獲得履歴｜ポイントサイトならGMOポイ活')
@section('layout.description', 'GMOポイ活はいつものショッピングや旅行予約、外食や、無料ゲームでポイントが貯まるポイントサイトです。貯まったポイントは現金やギフト券に交換！コツコツお小遣い稼ぎができます♪')

@section('layout.content')
<div class="inner">
    <div class="able_contents_history__ttl">
        <h2 class="contents__ttl">獲得履歴</h2>
    </div>

    <ul id="sortBox" class="able__tab">
        @if ($type == \App\UserPoint::AFF_GROUP_TYPE)
        <li class="active">{{ Tag::link(route('users.point_list', ['type' => \App\UserPoint::AFF_GROUP_TYPE]), '広告参加履歴', null, null, null, false) }}</li>
        <li>{{ Tag::link(route('users.point_list', ['type' => \App\UserPoint::OTHER_GROUP_TYPE]), 'その他の履歴', null, null, null, false) }}</li>
        @else
        <li>{{ Tag::link(route('users.point_list', ['type' => \App\UserPoint::AFF_GROUP_TYPE]), '広告参加履歴', null, null, null, false) }}</li>
        <li class="active">{{ Tag::link(route('users.point_list', ['type' => \App\UserPoint::OTHER_GROUP_TYPE]), 'その他の履歴', null, null, null, false) }}</li>
        @endif
    </ul>

    <section class="">
        @if($type != \App\UserPoint::AFF_GROUP_TYPE)
        <p class="text--12 u-mt-20">その他の履歴については、ご参加頂いてから半年で削除させていただいております。ご了承下さい。</p>
        @endif

        @if($paginator->total() < 1)
        <div class="able u-mt-20">
            <p class="u-font-bold u-text-ac text--18 red">獲得履歴はありません。</p>
        </div>
        @else
            <ul class="able_list_hisotry_list"><!--最大10件でページ送り-->
                @foreach($paginator as $user_point)
                @php
                // プログラム情報
                $program = $user_point->program;
                if (isset($program) && $program->reviewsAccepted) {
                    $userReviewAccepted = $program->reviewsAccepted->where('user_id', Auth::user()->id);
                }
                // 遷移先
                $show_url = (isset($program->id) && $program->is_enable) ? route('programs.show', ['program' => $program]) : null;
                @endphp
                <li class="able_list_hisotry_list__contents">
                    <p class="able_list_hisotry_list__contents__date">{{ $user_point->created_at->format('Y-m-d') }}</p>
                    <p class="able_list_hisotry_list__contents__ttl">
                        @if (isset($show_url))
                        {{ Tag::link($show_url, $user_point->title) }}
                        @else
                        {{ $user_point->title }}
                        @endif
                    </p>
                    <div class="able_list_hisotry_list__contents__flex">
                        <div class="able_list_hisotry_list__contents__flex__l"></div>
                        <div class="able_list_hisotry_list__contents__flex__r">
                            <p class="able_list_hisotry_list__contents__point"><span class="red">{{ number_format($user_point->diff_point + $user_point->bonus_point) }}</span>ポイント</p>
                        </div>
                    </div>

                    @if (isset($show_url))
                        @if (isset($userReviewAccepted) && WrapPhp::count($userReviewAccepted) > 0)
                            <div class="post_none_review__btn">
                                <a href="{{ $show_url }}#post" onclick="return false">口コミ投稿済み</a>
                            </div>
                        @else
                            <div class="post_review__btn">
                                {{ Tag::link($show_url . '#post', '口コミ投稿で' . ($review_point_management ? $review_point_management->point : 5) . 'ポイント！') }}
                            </div>
                        @endif
                    @endif
                </li>
                @endforeach
            </ul>

            {!! $paginator->render('elements.pager', ['pageUrl' => function($page) use($type) { return route('users.point_list', ['type' => $type, 'page' => $page]); }]) !!}
        @endif
    </section>
 
    <div class="basic__change__btn">
        {{ Tag::link(route('users.show'), 'マイページへ戻る') }}
    </div>
</div><!-- /.inner -->
@endsection
