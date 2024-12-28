@php
$base_css_type = 'mypage';
@endphp
@extends('layouts.default')

@section('layout.head')
{!! Tag::style('/css/sp_mypage_bonus_point.css') !!}
<script type="text/javascript"><!--
$(function() {
    $('.addedfavo .contentsbox .heading, .r_clipped .contentsbox .heading, .r_clipped .contentsbox .r-article').each(function() {
        var $target = $(this);
        var html = $target.html();
        var $clone = $target.clone();
        $clone.css({
            display: 'none',
            position : 'absolute',
            overflow : 'visible'
        }).width($target.width()).height('auto');
        $target.after($clone);
        while((html.length > 0) && ($clone.height() > $target.height())) {
            html = html.substr(0, html.length - 1);
            $clone.html(html + '...');
        }
        $target.html($clone.html());
        $clone.remove();
    });
});
//-->
</script>
@endsection

@section('layout.title', 'マイページ｜ポイントサイトならGMOポイ活')
@section('layout.description', '無料で簡単にお小遣いが貯められるポイントサイトGMOポイ活のマイページです。貯めたポイントの確認や、現金やギフト券への交換はこちらから！')

@section('layout.content')

@php
$user = Auth::user();
@endphp

<!-- page title -->
<div class="inner">
    <h2 class="contents__ttl u-mt-20">マイページ</h2>
</div>
<!-- contents -->
<section class="inner">
    <div class="users__box">
        <div class="users__name">
            <span>{{ $user->nickname ? $user->nickname . " ($user->name)" : $user->name }}</span>さん
        </div>
        <div class="users__flex">
            <div class="users__flex__l">
                <div class="users__rank__thumb">
                    @php
                    $rank_data_map = [0 => ['img' => 'rank_general.png', 'class' => 'users__rank__normal'],
                        1 => ['img' => 'rank_bronze.png', 'class' => 'users__rank__bronze'],
                        2 => ['img' => 'rank_silver.png', 'class' => 'users__rank__silver'],
                        3 => ['img' => 'rank_gold.png', 'class' => 'users__rank__gold'],
                        4 => ['img' => 'rank_platina.png', 'class' => 'users__rank__platinum']];
                    $rank_label = config('map.user_rank')[$user->rank];
                    $rank_data = $rank_data_map[$user->rank];
                    @endphp
                    {{ Tag::image('/images/users/'.$rank_data['img'], $rank_label) }}
                </div>
                <p class="users__rank__txt {{ $rank_data['class'] }}">{{ $rank_label }}会員</p>
            </div>
            <div class="users__flex__r">
                <div class="users__poss__ttl">現在の所持ポイント</div>
                <div class="users__poss__list">
                    <ul>
                        <li class="point"><span>{{ number_format($user->point) }}</span>ポイント</li>
                        <li class="txt">交換申請中：{{ number_format($user->exchanging_point) }}ポイント</li>
                    </ul>
                </div>
                <div class="u-text-right mt-3" style="margin-top: 15px">
                    <b style="font-size: 1rem;font-weight: 500">ポイント失効期限 : {{ $user->actioned_at->copy()->startOfMonth()->addMonths(6)->endOfMonth()->format('Y年n月d日 H:i')}}
                    </b>
                </div>
                <p class="u-text-right u-mt-small">{{ Tag::link(route('abouts.member_rank'), '会員特典/条件を確認する', ['class' => 'textlink'], null, false) }}</p>
            </div>
        </div>
        <p class="users__poss__attention__ttl u-mt-small">※2022年12月1日よりポイントレートを1ポイント＝1円相当に変更しました。</p>
        <div class="users__btn">
            <div class="users__btn__small">
                {{ Tag::link(route('users.point_list'), '獲得履歴', null, null, false) }}
            </div>
            <div class="users__btn__small">
                {{ Tag::link(route('users.exchange_list'), '交換履歴', null, null, false) }}
            </div>
            <div class="users__btn__medium">
                {{ Tag::link(route('exchanges.index'), 'ポイント交換', null, null, false) }}
            </div>
        </div>
    </div><!-- users__box -->
</section><!-- inner -->

<!-- ユーザーメニュー -->
<section class="inner">
    <div class="users__menu">
        <ul>
            <li>{{ Tag::link(route('users.edit_email_setting'), 'メールマガジン受信設定') }}</li>
            <li>{{ Tag::link(route('users.edit'), '基本情報変更') }}</li>
            <li>{{ Tag::link(route('reviews.reviewer', ['user' => $user]), '投稿済みの口コミ一覧') }}</li>
            <li>{{ Tag::link(route('withdrawals.index'), '退会') }}</li>
        </ul>
    </div>
</section>

<!-- 現在の獲得予定ポイント -->
<section class="inner">
    <div class="schedule__box">
        <div class="schedule__ttl">現在の獲得予定ポイント</div>
        <div class="schedule__point"><span>{{ number_format($user->reward_point_total) }}</span>ポイント</div>
        <p class="u-mt-20">
            {{ Tag::link('/support/?p=33', '獲得予定に反映されない場合', ['class' => 'textlink'], null, false) }}<br>
            {{ Tag::link('/support/?p=31', 'ポイント獲得の時期について', ['class' => 'textlink'], null, false) }}
        </p>
        <p class="users__poss__attention__ttl u-mt-small">※2022年12月1日よりポイントレートを1ポイント＝1円相当に変更しました。</p>

        <hr class="bd_dot_gray u-mt-20">
        @if ($aff_reward_list->isEmpty())
        <p class="u-font-bold u-text-ac u-mt-20 text--18 red">配付予定ポイントはありません。</p>
        @else
        <div class="schedule_able">
            <ul><!--最大3件-->
                @foreach($aff_reward_list as $aff_reward)
                @php
                // プログラム情報
                $program = $aff_reward->affiriate->program ?? null;
                @endphp
                <li>
                    <div class="date txt">{{ $aff_reward->actioned_at->format('Y-m-d') }}</div>
                    <div class="able__ttl txt">
                        @if (isset($program->id))
                        {{ Tag::link(route('programs.show', ['program' => $program]), $aff_reward->title) }}
                        @else
                        {{ $aff_reward->title }}
                        @endif
                    </div><!--/name-->
                    <div class="willget">
                        <div class="howmuch txt">{{ number_format($aff_reward->point) }} </div>
                        <div class="unit txt">ポイント</div>
                    </div><!--/willget-->
                </li>
                @endforeach
            </ul>
        </div>
        <div class="able__btn">
            {{ Tag::link(route('users.reward_list'), '全て見る') }}
        </div>
        @endif
    </div><!-- schedule__box -->
</section><!-- inner -->
<div class="inner">
    <div class="schedule__box">
        <div class="schedule__ttl">次回獲得分のお友達紹介状況</div>
        <ul>
            <li class="schedule__point"><span class="heading">紹介人数：</span><span>{{ $newUserCount }}</span>人</li>
            <li class="schedule__point"><span class="heading">紹介ボーナス：</span><span>{{ $referralBonus }}</span>ポイント</li>
            <li class="schedule__point"><span class="heading">友達還元ボーナス：</span><span>{{ $friendReturnBonus }}</span>ポイント</li>
        </ul>
        <p class="u-mt-20">
            <a class="textlink" href="/support/?p=33">獲得予定に反映されない場合</a><br>
            <a class="textlink" href="/friends">お友達紹介について</a>
        </p>
        <p class="u-text-right">※翌月の10日にポイント付与</p>
        <p class="u-text-right">※紹介人数は今月ご入会されたお友達の人数です。紹介ボーナスは条件達成されたお友達の人数×ボーナスポイントとなります。</p>
    </div>
</div>
<!-- お気に入りに追加した広告 -->
<section class="inner">
    <h2 class="contents__ttl u-mt-small">お気に入りに追加した広告</h2>
    @if ($user_program_list->isEmpty())
    <p class="u-font-bold u-text-ac u-mt-20 text--18 red">お気に入りに追加した広告はありません。</p>
    @else
    <div class="favorite__list">
        <ul>
            @foreach($user_program_list as $program)
            <li>
                <div class="favorite__flex">
                    <div class="favorite__flex__l">
                        <div class="favorite__thumb">
                            <a href="{{ route('programs.show', ['program' => $program]) }}">
                                {{ Tag::image($program->affiriate->img_url, $program->title) }}
                            </a>
                        </div>
                    </div>
                    <div class="favorite__flex__r">
                        <div class="favorite__ttl">
                            {{ Tag::link(route('programs.show', ['program' => $program]), $program->title, [], null, false) }}
                        </div>
                    </div>
                </div>
                <div class="favorite__delete">
                    <a href="{{ route('users.remove_program', ['program' => $program]) }}">
                        <i>{{ Tag::image('/images/users/ico_dustbox.svg', '削除') }}</i>
                        お気に入り削除
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
    </div><!-- favorite__list -->
    <div class="favorite__btn">
        {{ Tag::link(route('users.program_list'), '全て見る', null, null, false) }}
    </div>
    @endif
</section><!-- inner -->

<!-- クリップしたポイ活お得情報 -->
<section class="inner">
    <h2 class="contents__ttl u-mt-40">クリップしたポイ活お得情報</h2>
    @if (isset($fav_recipe_data) && $fav_recipe_data->result->status && !empty($fav_recipe_data->items))
    <div class="clip__list">
        <ul>
            @php
            $recipe_list = $fav_recipe_data->items;
            @endphp
            @foreach ($recipe_list as $recipe)
            <li>
                <div class="clip__flex">
                    <div class="clip__flex__l">
                        <div class="clip__thumb">
                            <a href="{{ $recipe->guid }}">
                                {{ Tag::image($recipe->img, $recipe->title, ['class' => 'img-recipe']) }}
                            </a>
                        </div>
                    </div>
                    <div class="clip__flex__r">
                        <div class="clip__ttl">
                            {{ Tag::link($recipe->guid, $recipe->title) }}
                        </div>
                        <div class="clip__txt">
                            {{ $recipe->catchText }}
                        </div>
                    </div>
                </div>
                <div class="clip__delete">
                    <a href="{{ route('users.remove_recipe', ['recipe' => $recipe->id]) }}">
                        <i>{{ Tag::image('/images/users/ico_dustbox.svg', '削除') }}</i>
                        クリップ削除
                    </a>
                </div>
            </li><!-- /li -->
            @endforeach
        </ul><!--/listview-->
    </div><!-- recipe__list -->
    <div class="clip__btn">
        {{ Tag::link(route('users.recipe_list'), '全て見る', null, null, false) }}
    </div>
    @else
    <div class="u-mt-small">
        <p class="u-font-bold text--18 red">クリップしたポイ活お得情報はありません。</p>
    </div>
    @endif
</section>

@endsection
