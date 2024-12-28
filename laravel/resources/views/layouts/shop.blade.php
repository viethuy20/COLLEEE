@php
$base_css_type = 'shopping';
@endphp
@extends('layouts.plane')

@section('layout.sidebar')
{{ Tag::link('/support/?p=35', Tag::image('/images/bnr_aboutshopping.png', 'GMOポイ活 ショッピングについてよくある質問'), null, null, false) }}
@php
// 公開・非公開確認
$target_banner_list = [1034 => ['link' => '/images/bnr_rakuten2.png', 'title' => '楽天市場でお買い物をする方法はこちら'], 1022 => ['link' => '/images/bnr_yahoo.png', 'title' => 'Yahoo!ショッピングでお買い物する方法はこちら']];
$target_banner = App\Program::whereIn('id', array_keys($target_banner_list))->ofEnable()->get()->keyBy('id');
@endphp
<section class="bnrs_card"><ul>
    @foreach($target_banner_list as $program_id => $value)
    @if (isset($target_banner[$program_id]))
    <li>{{ Tag::link(route('programs.show', ['program' => $program_id]), Tag::image($value['link'], $value['title']), null, null, false) }}</li>
    @endif
    @endforeach
</ul></section>
<?php
    $label_item_map = [
        109 => ['icon' => 'ico_shop_cart.svg'],
        110 => ['icon' => 'ico_shop_diet.svg'],
        111 => ['icon' => 'ico_shop_beauty.svg', 'class' => 'xsmall'],
        112 => ['icon' => 'ico_shop_fashion.svg'],
        113 => ['icon' => 'ico_shop_gourmet.svg'],
        114 => ['icon' => 'ico_shop_gift.svg'],
        115 => ['icon' => 'ico_shop_kaden.svg'],
        116 => ['icon' => 'ico_shop_life.svg'],
        117 => ['icon' => 'ico_shop_sports.svg'],
        118 => ['icon' => 'ico_shop_kids.svg'],
        119 => ['icon' => 'ico_shop_pet.svg', 'class' => 'large'],
        120 => ['icon' => 'ico_shop_book.svg'],
        121 => ['icon' => 'ico_shop_game.svg']
    ];
?>
<h2 class="contents__ttl">ショッピングで探す</h2>
<ul class="sidebar__list">
    @php
    $label_list = \App\Label::whereIn('id', array_keys($label_item_map))->pluck('name', 'id')->all();
    @endphp
    @foreach($label_list as $label_id => $label)
        @php
        $icon = $label_item_map[$label_id]['icon'] ?? null;
        $class = $label_item_map[$label_id]['class'] ?? null;
        @endphp
    <li>
        <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$label_id]]) }}">
            <i>{{ Tag::image("/images/common/$icon", null, isset($class) ? ['class' => $class] : null) }}</i>{{ $label }}
        </a>
    </li>
    @endforeach
</ul>

@php
// 口コミ
$review_list = App\Review::ofEnableLabel([77])
    ->ofSort(0)
    ->take(3)
    ->get();
@endphp
@if (!$review_list->isEmpty())
<h2 class="contents__ttl">みんなの新着口コミ</h2>
<div class="newreview"><ul>
    @foreach($review_list as $review)
    @php
    $program = $review->program;
    @endphp
    <a href="{{ route('programs.show', ['program' => $program, 'rid' => '28']) }}"><li><dl>
        <dt class="heading"><span class="icon-arrowr"></span>{{ $program->title }}</dt>
        <dd class="uname">{{ $review->reviewer }}  </dd><!--ユーザー情報-->
        <dd class="evaluation"><ul class="stars"><!--
            @for ($i = 1; $i <= 5; $i++)
            --><li>{{ Tag::image(($i <= $review->assessment) ? '/images/programs/ico_kuchikomi_star_yellow.svg' : '/images/programs/ico_kuchikomi_star_gray.svg', 'star') }}</li><!--
            @endfor
        --></ul></dd><!--★-->
        <dd class="txt_ureview">{{ $review->message }}</dd>
    </dl></li></a>
    @endforeach
</ul></div><!--/contentsbox-->

@endif
@endsection
