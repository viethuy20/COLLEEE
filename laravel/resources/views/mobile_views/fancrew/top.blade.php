@extends('layouts.fancrew')

@section('layout.title', 'お店でお得 | いつもの生活がちょっとお得になるGMOポイ活')
@section('layout.keywords', 'モニター,覆面調査')
@section('layout.description', 'お店や商品を選んでモニターを体験！体験後のアンケート提出等でポイントが貰える！')
@section('og_type', 'website')
@section('fancrew.title', 'お店でお得')

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
    <div class="contentsbox clearfix">
        <div class="fl lnk_guide"><span class="icon-question"></span>{!! Tag::link(route('abouts.fancrew'), 'ご利用ガイド') !!}</div>
        @if (\Auth::check() && \Auth::user()->aff_accounts()->ofType(\App\AffAccount::FANCREW_TYPE)->exists())
        <p class="fr btn_guide">
            {!! Tag::link(route('fancrew.pages', ['action' => 'pages']).'?'.http_build_query(['_pf' => 'my']), '<span class="icon-worksheet"></span>&nbsp;ご利用履歴', null, null, false) !!}
        </p>
        @endif
    </div><!--/contentsbox-->

    <h2>ご利用の流れ</h2>
    <div class="contentsbox"><ul class="flow">
        <li><dl>
            <dt class="imgbox">{!! Tag::image('/images/img_monitorflow1.svg', '') !!}</dt>
            <dd><span>1</span>お店を選んで当選したら…</dd>
        </dl></li>
        <li><dl>
            <dt><span class="icon-arrowr"></span></dt>
            <dd></dd>
        </dl></li>
        <li><dl>
            <dt class="imgbox">{!! Tag::image('/images/img_monitorflow2.svg', '') !!}</dt>
            <dd><span>2</span>モニターを体験！</dd>
        </dl></li>
        <li><dl>
            <dt><span class="icon-arrowr"></span></dt>
            <dd></dd>
        </dl></li>
        <li><dl>
            <dt class="imgbox">{!! Tag::image('/images/img_monitorflow3.svg', '') !!}</dt>
            <dd><span>3</span>アンケート等提出でポイントをゲット！</dd>
        </dl></li>
    </ul></div><!--/contentsbox-->
</section><!--/head_monitoir-->

<section class="search_monitor">
    <h2>エリアを選択</h2>
    @include('elements.fancrew_form', ['use_freeword' => false, 'use_category' => false])
</section><!--/search_monitor-->

@include('elements.fancrew_s_shop_list')
@include('elements.fancrew_p_shop_list')
@endsection
