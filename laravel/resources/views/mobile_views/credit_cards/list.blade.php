@php 
$base_css_type = 'credit_card';
@endphp
@extends('layouts.default')

@section('layout.head')
<script>
$(function(){
    $('.andmore').each(function(){
        var $ele = $(this);
        $ele.prepend('<div class="open"><a href="#"><span>＋</span>カードの情報をもっと見る</a></div>');
        $ele.append('<div class="close"><a href="#"><span>－</span>カード情報を閉じる</a></div>');
        $ele.find('.open').nextAll().hide();
        $ele.find('.open').click(function(){
            $ele.find('.open').hide();
            $ele.find('.open').nextAll().slideDown();
            return false;
        });
         $ele.find('.close').click(function(){
            $ele.find('.open').nextAll().slideUp('slow', function() {
                $ele.find('.open').show();
            });
            return false;
        });
    });
});
</script>
@endsection

@section('layout.title', 'クレジットカード徹底比較｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'クレジットカード,比較,お得,ポイント,GMOポイ活')
@section('layout.description', '自分のライフスタイルに合った条件でクレジットカードを絞り込めるから、便利で使いやすいお得なカードが見つかる！GMOポイ活経由でクレジットカードを発行してポイントをもらおう！')

@section('layout.breadcrumbs')
    @if(WrapPhp::count($arr_breadcrumbs) > 0)
        <section class="header__breadcrumb">
            <ol>
                @foreach($arr_breadcrumbs as $item)
                    <li>
                        <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                    </li>    
                @endforeach
                <li>{{empty($label_name) ? '対象クレジットカード一覧' : $label_name}}</li>
            </ol>
        </section>
    @endif
@endsection

@section('layout.content')

<!-- page title -->
<div class="inner">
    <div class="credit_cards_list__ttl">
        <h1 class="contents__ttl">
            {{ Tag::image('/images/credit_cards/credit_cards_ttl.png', 'クレジットカード比較') }}
        </h1>
    </div>
</div>

@php
$accept_days_map = config('map.accept_days');

$brand_map = config('map.credit_card_brand');
$emoney_map = config('map.credit_card_emoney');
$insurance_map = config('map.credit_card_insurance');
$point_type_map = config('map.credit_card_point_type');
$apple_pay_map = config('map.credit_card_apple_pay');

$sort = $condition->getParam('sort') ?? 1;
$sort_map = [$condition->getListUrl((object) ['sort' => 1, 'page' => 1]) => '獲得ポイント順',
    $condition->getListUrl((object) ['sort' => 2, 'page' => 1]) => 'ポイント獲得時期順',
    $condition->getListUrl((object) ['sort' => 3, 'page' => 1]) => '還元率順'];

$point_label_map = [1 => ['class' => 'ico_status_t', 'initial' => 'T', ],
    2 => ['class' => 'ico_status_r', 'initial' => 'R', ],
    3 => ['class' => 'ico_status_j', 'initial' => 'J', ],
    4 => ['class' => 'ico_status_a', 'initial' => 'A', ]];
@endphp
@if ($paginator->total() > 0)
<!-- select -->
<section class="inner">
    <div class="credit_cards_list__select">
        {{ Tag::formSelect('sort', $sort_map, $condition->getListUrl((object) ['sort' => $sort, 'page' => 1]), ['onChange' => 'location.href=value']) }}
    </div>
</section>

<section class="inner">
    <div class="credit_cards_list__list">
        <ul class="credit_cards_list__list__wrap">
            @php
                $index = 0;
            @endphp
            @foreach ($paginator as $credit_card)
            @php
                $index++;
            @endphp            
            <li>    
                @php
                $program = $credit_card->program;
                $affiriate = $program->affiriate;
                @endphp
                <div class="credit_cards_list__detail">
                    <div class="credit_cards_list__detail__ttl">{{ $credit_card->title }}</div>
                    <div class="credit_cards_list__detail__l">
                        <div class="credit_cards_list__detail__thumb">
                            {{ Tag::image($credit_card->img_url, $program->title, ['width' => '150px']) }}
                        </div>
                    </div>
                    <div class="credit_cards_list__detail__r">
                        <dl class="credit_cards_list__detail__chart">
                            <dt>獲得条件</dt>
                            <dd>{{ $program->fee_condition }}</dd>
                            <dt>予定反映目安</dt>
                            @if (!isset($affiriate->give_days))
                            <dd>予定への反映なし</dd>
                            @elseif($affiriate->give_days == 0)
                            <dd>即時</dd>
                            @else
                            <dd>{{ $affiriate->give_days }}日</dd>
                            @endif
                            <dt>獲得時期目安</dt>
                            <dd>{{ $accept_days_map[$affiriate->accept_days] }}</dd>
                        </dl>
                        <p class="credit_cards_list__detail__point"><span>{{ $program->point->fee_label }}</span>P</p>
                    </div>
                    <div class="credit_cards_list__detail__b">
                        <div class="credit_cards_list__detail__btn">
                            {{ Tag::link(route('programs.show', ['program'=> $program]), '詳細を見る') }}
                        </div>
                        <p class="credit_cards_list__detail__txt">
                            {!! nl2br(e($credit_card->detail)) !!}
                        </p>
                        @php 
                        $common_points = json_decode($credit_card->point);
                        @endphp
                        @if (!empty($common_points))
                        <div class="credit_cards_list__detail__incommon">
                            <p class="credit_cards_list__detail__incommon__ttl">共通ポイントが貯まる！</p>
                            <dl>
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
                        </div><!--/incommon-->
                        @endif
                        <div class="credit_cards_list__more">
                            <input id="trigger_credit_cards_list{{ $index }}" class="credit_cards_list__detail__trigger" type="checkbox">
                            <div class="credit_cards_list__detail__spec">
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
                            </div><!-- /.credit_cards_list__detail__spec -->

                            @if (!empty($credit_card->campaign))
                            <div class="credit_cards_list__detail__campaign">
                                <p class="credit_cards_list__detail__campaign__ttl">必見！キャンペーン情報</p>
                                <p class="text--12">{!! $credit_card->campaign !!}</p>
                            </div><!--/info_camp-->
                            @endif

                            @php
                            $recommend_program_list = $credit_card->recommend_program_list;
                            @endphp
                            @if (!$recommend_program_list->isEmpty())
                            <div class="credit_cards_list__detail__recommend">
                                <p class="credit_cards_list__detail__recommend__ttl">このカードを利用するなら！おすすめショップ</p>
                                <dl class="credit_cards_list__detail__recommend__list clearfix">
                                    @foreach ($recommend_program_list as $recommend_program)
                                    <dt>{{ Tag::link(route('programs.show', ['program'=> $recommend_program]), $recommend_program->title) }}</dt>
                                    <dd><span>{{ $recommend_program->point->fee_label }}</span>P</dd>
                                    @endforeach
                                </dl>
                            </div><!--/recommend-->
                            @endif    
                            <label class="credit_cards_list__detail__more open" for="trigger_credit_cards_list{{ $index }}">カード情報をもっと見る</label>
                            <label class="credit_cards_list__detail__more close" for="trigger_credit_cards_list{{ $index }}">カード情報を閉じる</label>        
                        </div><!--/more-->
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div><!--/credit_cards_list__list-->

    {!! $paginator->render('elements.pager', ['pageUrl' => function($page) use($condition) { return $condition->getListUrl((object) ['page' => $page]); }]) !!}

	<!-- search -->
	<div class="inner">
        <div class="credit_cards_list__search">
            <div class="credit_cards_list__search__btn">
                {{ Tag::link(route('credit_cards.index'), '再検索する') }}
            </div>
        </div>
	</div>

</section><!--/card_list-->
@else
    <section class="inner">
        <div class="credit_cards_list__order__message u-mt-20">
           <p class="u-font-bold u-text-ac text--18 red">
                条件に該当するクレジットカードが<br/>見つかりませんでした。
            </p>
        </div>
        <div class="inner">
            <div class="credit_cards_list__search">
                <div class="credit_cards_list__search__btn">
                    {{ Tag::link(route('credit_cards.index'), '再検索する') }}
                </div>
            </div>
        </div>    
    </section>
@endif

<section class="inner">
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
</section>
<div class="foot-notes__wrap">
    <ul class="foot-notes">
        <li>カード情報は変更される場合がございます。詳細は各カード会社の公式サイトをご確認ください。</li>
    </ul>
</div>
@endsection
