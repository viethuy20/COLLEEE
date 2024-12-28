@extends('layouts.credit_card')

@section('layout.head')
<script type="text/javascript"><!--
$(function(){
    $('.txt_ureview').collapser({
        mode: 'chars',
        truncate: 70,
        showText: '続きを読む',
        hideText: '閉じる'
    });
});
-->
</script>
@endsection

@section('layout.title', 'クレジットカード徹底比較｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'クレジットカード,比較,お得,ポイント,GMOポイ活')
@section('layout.description', '自分のライフスタイルに合った条件でクレジットカードを絞り込めるから、便利で使いやすいお得なカードが見つかる！GMOポイ活経由でクレジットカードを発行してポイントをもらおう！')

@section('layout.verfiry_for_google')
    @include('common.verify_for_google')
@endsection

@section('layout.content')
@php
$accept_days_map = config('map.accept_days');

$brand_map = config('map.credit_card_brand');
$emoney_map = config('map.credit_card_emoney');
$insurance_map = config('map.credit_card_insurance');
$point_type_map = config('map.credit_card_point_type');
$apple_pay_map = config('map.credit_card_apple_pay');

$brands = $condition->getParam('brands');
$emoneys = $condition->getParam('emoneys');
$insurances = $condition->getParam('insurances');
@endphp

@section('layout.breadcrumbs')
    @if(WrapPhp::count($arr_breadcrumbs) > 0)
        <section class="header__breadcrumb">
            <ol>
                @foreach($arr_breadcrumbs as $item)
                    <li>
                        <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                    </li>    
                @endforeach
                <li>{{empty($label_name) ? 'クレジットカード徹底比較' : $label_name}}</li>
            </ol>
        </section>
    @endif
@endsection

<section class="contents">
    <h1>{{ Tag::image("/images/credit_cards/credit_cards_ttl.png", "クレジットカード比較") }}</h1>

    <section class="contents__box">
        <h2 class="contents__ttl orange">検索条件</h2>
        {{ Tag::formOpen(['url' => route('credit_cards.list'), 'method' => 'GET', 'class' => 'credit_cards_list__search']) }}
        @csrf
        <div class="credit_cards_list__search__checkbox">
            <dl>
                <dt>ブランド</dt>
                <dd>
                @foreach ($brand_map as $key => $label)
                @php
                $checked = ($brands >> ($key - 1) & 1 == 1);
                $value = 1 << ($key - 1);
                @endphp
                    <label>
                        {{ Tag::formCheckbox('brand[]', $value, $checked, ['class' => 'checkbox_card']) }}
                        {{ $label }}
                    </label>
                @endforeach
                </dd>
            </dl>
            <dl>
                <dt>電子マネー</dt>
                <dd>
                @foreach ($emoney_map as $key => $label)
                @php
                $checked = ($emoneys >> ($key - 1) & 1 == 1);
                $value = 1 << ($key - 1);
                @endphp
                    <label>
                        {{ Tag::formCheckbox('emoney[]', $value, $checked, ['class' => 'checkbox_card']) }}
                        {{ $label }}
                    </label>
                @endforeach
                </dd>
            </dl>
            <dl>
                <dt>付帯保険付き</dt>
                <dd>
                @foreach ($insurance_map as $key => $label)
                @php
                $checked = ($insurances >> ($key - 1) & 1 == 1);
                $value = 1 << ($key - 1);
                @endphp
                    <label>
                        {{ Tag::formCheckbox('insurance[]', $value, $checked, ['class' => 'checkbox_card']) }}
                        {{ $label }}
                    </label>
                @endforeach
                </dd>
            </dl>
            <dl>
                <dt>こだわり条件</dt>
                <dd>
                    <label>
                        {{ Tag::formCheckbox('annual_free', '1', $condition->getParam('annual_free'), ['class' => 'checkbox_card']) }}
                        年会費永年無料
                    </label>
                    <label>
                        {{ Tag::formCheckbox('etc', '1', $condition->getParam('etc'), ['class' => 'checkbox_card']) }}
                        <span class="checkbox_parts">ETCカード付き</span>
                    </label>
                    <label>
                        {{ Tag::formCheckbox('apple_pay', '1', $condition->getParam('apple_pay'), ['class' => 'checkbox_card']) }}
                        ApplePay 対応
                    </label>
                </dd>
            </dl>
        </div>
        {{ Tag::formButton('この条件で検索する', ['type' => 'submit']) }}
    {{ Tag::formClose() }}
    </section><!--/contents__box-->

    @php
    $sort = $condition->getParam('sort') ?? 1;
    $sort_map = [1 => '獲得ポイント順', 2 => 'ポイント獲得時期順', 3 => '還元率順'];

    $point_label_map = [1 => ['class' => 'ico_status_t', 'initial' => 'T', ],
        2 => ['class' => 'ico_status_r', 'initial' => 'R', ],
        3 => ['class' => 'ico_status_j', 'initial' => 'J', ],
        4 => ['class' => 'ico_status_a', 'initial' => 'A', ]];
    @endphp
    @if ($paginator->total() > 0)
    <section id="sortBox" class="tab_card_4"><ul id="tabs" class="credit_cards_list__order__tab">
        @foreach ($sort_map as $key => $label)
        <li {!! $key == $sort ? 'class="active"' : '' !!}>
            {{ Tag::link($condition->getListUrl((object) ['sort' => $key, 'page' => 1]), $label, null, null, false) }}
        </li>
        @endforeach
    </ul></section><!--/tab_card-->

    <section class="credit_cards_list__order">
        <ul class="credit_cards_list__order__list">
        @foreach ($paginator as $credit_card)
        @php
        $program = $credit_card->program;
        $affiriate = $program->affiriate;
        @endphp
            <li>
                <p class="credit_cards_list__order__list__ttl">{{ $credit_card->title }}</p>
                <div class="credit_cards_list__order__list__item">
                    <div class="credit_cards_list__order__list__item__l">
                        <div class="credit_cards_list__order__list__item__img">{{ Tag::image($credit_card->img_url, $program->title, ['width' => '150px']) }}</div>
                        <div class="credit_cards_list__order__list__item__btn">{{ Tag::link(route('programs.show', ['program'=> $program]), '詳細を見る') }}</div>
                    </div>
                    <div class="credit_cards_list__order__list__item__r">
                        <dl class="credit_cards_list__order__list__item__chart">
                            <dt>獲得条件</dt>
                            <dd>{{ $program->fee_condition }}</dd>
                            <dt>予定反映目安</dt>
                            <dd>
                                @if (!isset($affiriate->give_days))
                                予定への反映なし
                                @elseif($affiriate->give_days == 0)
                                即時
                                @else
                                {{ $affiriate->give_days }}日
                                @endif</dd>
                            <dt>獲得時期目安</dt>
                            <dd>{{ $accept_days_map[$affiriate->accept_days] }}</dd>
                        </dl>
                        <p class="credit_cards_list__order__list__item__point">{{ $program->point->fee_label }}P</p>
                        <p class="text--15 u-mt-20">{!! nl2br(e($credit_card->detail)) !!}</p>
                    </div>
                </div>

                @php 
                $common_points = json_decode($credit_card->point);
                @endphp
                @if (!empty($common_points))
                <p class="credit_cards_list__order__list__incommon__ttl">共通ポイントが貯まる！</p>
                <dl class="credit_cards_list__order__list__incommon">
                    @foreach ($common_points as $common_point)
                    @if (!empty($common_point->detail))
                    <dt>
                        <span class="{{ $point_label_map[$common_point->type]['class'] ?? '' }}">
                            {{ $point_label_map[$common_point->type]['initial'] ?? '' }}
                        </span>
                        {{ $point_type_map[$common_point->type] ?? '' }}
                    </dt>
                    <dd>{{ $common_point->detail }}</dd>
                    @endif
                    @endforeach
                </dl>
                @endif

                <div class="credit_cards_list__order__list__spec">
                    <dl>
                        <dt>ブランド</dt>
                        <dd><ul>
                            @foreach ($brand_map as $brand_id => $label)
                            @if (in_array($brand_id, $credit_card->brand_ids))
                            <li>{{ $label }}</li>
                            @endif
                            @endforeach
                        </ul></dd>
                    </dl>
                    <dl>
                        <dt>年会費</dt>
                        <dd>
                            @if ($credit_card->annual_free == 1)
                            永年無料<br />
                            @else
                            {{ $credit_card->annual_detail }}
                            @endif
                        </dd>
                    </dl>
                    <dl>
                        <dt>ポイント還元率</dt>
                        <dd>{{ $credit_card->back }}%</dd>
                    </dl>
                    <dl>
                        <dt>電子マネー</dt>
                        <dd><ul>
                            @foreach ($emoney_map as $emoney_id => $label)
                            @if (in_array($emoney_id, $credit_card->emoney_ids))
                            <li>{{ $label }}</li>
                            @endif
                            @endforeach
                        </ul></dd>
                    </dl>
                    <dl>
                        <dt>ETCカード</dt>
                        <dd>{{ $credit_card->etc_detail }}</dd>
                    </dl>
                    <dl>
                        <dt>Apple Pay</dt>
                        <dd>{{ $apple_pay_map[$credit_card->apple_pay] ?? '' }}</dd>
                    </dl>
                    <dl>
                        <dt>付帯保険</dt>
                        <dd><ul>
                            @foreach ($insurance_map as $insurance_id => $label)
                            @if (in_array($insurance_id, $credit_card->insurance_ids))
                            <li>{{ $label }}</li>
                            @endif
                            @endforeach
                        </ul></dd>
                    </dl>
                </div><!--/spec-->

                @if (!empty($credit_card->campaign))
                <div class="credit_cards_list__order__list__campaign">
                    <p class="credit_cards_list__order__list__campaign__ttl">キャンペーン情報</p>
                    <p class="text--15">{!! $credit_card->campaign !!}</p>
                </div><!--/info_camp-->
                @endif

                @php 
                $recommend_program_list = $credit_card->recommend_program_list;
                @endphp
                @if (!$recommend_program_list->isEmpty())
                <div class="credit_cards_list__order__list__recommend">
                    <p class="credit_cards_list__order__list__recommend__ttl">
                        このカードを利用するなら！おすすめショップ
                    </p>
                    <dl class="credit_cards_list__order__list__recommend__list">
                        @foreach ($recommend_program_list as $recommend_program)
                        <dt>{{ Tag::link(route('programs.show', ['program'=> $recommend_program]), $recommend_program->title) }}</dt>
                        <dd><span class="red">{{ $recommend_program->point->fee_label }}</span>P</dd>
                        @endforeach
                    </dl>
                </div><!--/recommend-->
                @endif
            </li>
        @endforeach
        </ul>
    </section>

    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) use($condition) { return $condition->getListUrl((object) ['page' => $page]); }]) !!}
    @else
    <section class="credit_cards_list__order__message u-mt-20">
        <p class="u-font-bold u-text-ac text--18 red">条件に該当するクレジットカードが<br/>見つかりませんでした。</p>
    </section><!--/card_list-->
    @endif

    @php
    // スタッフおすすめカード情報取得
    $credit_card_recommend_list = App\Content::ofSpot(App\Content::SPOT_CREDIT_CARD_RECOMMEND)
        ->orderBy('id', 'asc')
        ->take(2)
        ->get();
    @endphp
    @if (!$credit_card_recommend_list->isEmpty())
    <h2 class="contents__ttl">スタッフのオススメ</h2>
    <ul>
        @foreach ($credit_card_recommend_list as $credit_card_recommend)
        @php 
        $program = $credit_card_recommend->program;
        @endphp
        @continue (!isset($program->id))
        @php
        $affiriate = $program->affiriate;
        @endphp
        <div class="credit_cards_list__staff">
            <div class="credit_cards_list__staff__img">{{ Tag::image($affiriate->img_url, $program->title) }}</div>
            <div class="credit_cards_list__staff__txt">
                <p class="credit_cards_list__staff__txt__main">{{ $program->title }}</p>
                <p class="credit_cards_list__staff__txt__sub">{{ $program->fee_condition }}</p>
                <p class="credit_cards_list__staff__txt__point">{{ $program->point->fee_label }}P</p>
            </div>
            <div class="credit_cards_list__staff__btn"><a href="{{ route('programs.show', ['program'=> $program]) }}">詳細を見る</a></div>
        </div>
        @endforeach
    </ul>
    @endif

    @php
    $credit_card_recipe_data = App\External\Recipe::getRecipeListFromTag('クレジットカード');
    @endphp
    @if (isset($credit_card_recipe_data) && $credit_card_recipe_data->result->status && !empty($credit_card_recipe_data->items))
    <h2>クレジットカードに関するポイ活お得情報</h2>
    <div class="contentsbox_r"><ul>
        @php
        $recipe_list = $credit_card_recipe_data->items;
        array_splice($recipe_list, 3);

        $recipe_id_list = [];
        foreach ($recipe_list as $recipe) {
            $recipe_id_list[] = $recipe->id;
        }
        $recipe_total_map = App\UserRecipe::getTotalMap($recipe_id_list);
        @endphp
        @foreach ($recipe_list as $i => $recipe)
        <a href="{{ $recipe->guid }}"><li><dl class="clearfix">
            <dt class="img_recipe">{{ Tag::image($recipe->img, $recipe->title) }}</dt>
            <dd class="heading">{{ $recipe->title }}</dd>
            <dd class="date">{{ $recipe->update }}</dd>
            <dd class="clipped">
                <span class="icon-clip"></span>&nbsp;
                クリップ：<span class="clipCount-242">{{ number_format($recipe_total_map[$recipe->id] ?? 0) }}人</span>
            </dd>
        </dl></li></a>
        @endforeach
    </ul></div><!--/contentsbox_r-->
    @endif

    @php
    // クレジットカード口コミ取得
    $review_list = App\Review::ofEnableLabel([58])
        ->ofSort(0)
        ->take(3)
        ->get();
    @endphp
    @if (!$review_list->isEmpty())
    @include('elements.review_list', ['review_list' => $review_list])
    @endif

    @php
    $links_for_category = [
                  ['link_1' => '/programs/list?ll%5B0%5D=125', 'text_link_1' => 'クレジットカードの広告一覧を見る'],
                  ['link_1' => '/article/category/creditcard/', 'text_link_1' => 'クレジットカードの新着お得情報を見る']
                ];
    @endphp
    <div class="programs_list__list">
        <h2 class="contents__ttl u-mt-40">関連コンテンツ</h2>
        <div class="article__cat" id="article__cat">
            <ul>
                @foreach ($links_for_category as $link)
                    <li><a href="{{ $link['link_1'] }}">{{$link['text_link_1']}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="foot-notes__wrap">
        <ul class="foot-notes">
            <li>カード情報は変更される場合がございます。詳細は各カード会社の公式サイトをご確認ください。</li>
        </ul>
    </div>
</section><!--/contents-->
@endsection
