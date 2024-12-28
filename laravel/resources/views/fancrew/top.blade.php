@extends('layouts.fancrew')

@section('layout.title', 'モニター（お店でお得）｜ポイントサイトならGMOポイ活')
@section('layout.keywords', 'モニター,覆面調査')
@section('layout.description', '気になるお店をお得に楽しむなら、GMOポイ活でモニター参加！')
@section('og_type', 'website')

@section('layout.head')
<script type="text/javascript"><!--
$(function($) {
  $('.pickup_monitor li .txt_picup').each(function() {
    var $target = $(this);
    var html = $target.html();
    var $clone = $target.clone();
    $clone
      .css({
        display: 'none',
        position : 'absolute',
        overflow : 'visible'
      })
      .width($target.width())
      .height('auto');
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
@php
$meta = new \App\Services\Meta;
$arr_breadcrumbs = $meta->setBreadcrumbs(null);
$application_json = '';
$position = 1;
foreach($arr_breadcrumbs as $key => $val) {
    $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
    $position++;
}
$link = route('fancrew.pages');
$application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "モニター（お店でお得）", "item": "' . $link . '"},';

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
            モニター（お店でお得）
        </li>
    </ol>
</section>
@endsection

@section('fancrew.content')

<section class="head_monitoir">
    <div class="clearfix">
        <h1 class="ttl_review">モニター（お店でお得）</h1>

        <div class="fl lnk_guide">{!! Tag::link(route('abouts.fancrew'), 'ご利用ガイド') !!}</div>
        @if (\Auth::check() && \Auth::user()->aff_accounts()->ofType(\App\AffAccount::FANCREW_TYPE)->exists())
        <div class="fr lnk_guide">
            {!! Tag::link(route('fancrew.pages', ['action' => 'smartphone.pages']).'?'.http_build_query(['_pf' => 'my']), 'ご利用履歴', null, null, false) !!}
        </div>
        @endif
    </div><!--/clearfix-->

    <div class="contents_box">
        <h2>ご利用の流れ</h2>
        <ul class="flow">
        <li><dl>
            <dt class="imgbox">{!! Tag::image('/images/fancrew_flow1.png', '') !!}</dt>
            <dd>お店を選んで<br>当選したら…</dd>
        </dl></li>
        <li><dl>
            <dt class="imgbox">{!! Tag::image('/images/fancrew_flow2.png', '') !!}</dt>
            <dd>モニターを体験！</dd>
        </dl></li>
        <li><dl>
            <dt class="imgbox">{!! Tag::image('/images/fancrew_flow3.png', '') !!}</dt>
            <dd>アンケート等提出で<br>ポイントをゲット！</dd>
        </dl>
        </li>
    </ul></div><!--/contents_box-->

    <div class="flex_box">
        <div class="flex_box_l">
            <section class="search_monitor">
                <h2>エリアを選択</h2>
                @include('elements.fancrew_form', ['use_freeword' => false, 'use_category' => false])
            </section><!--/search_monitor-->
        </div>

        <div class="flex_box_r">
            @include('elements.fancrew_s_shop_list')
            @include('elements.fancrew_p_shop_list')
            <div class="flogo">{!! Tag::image('/images/logo_fancrew.png', 'ファンくる') !!}</div>
        </div>
    </div>

</section><!--/head_monitoir-->

@endsection
