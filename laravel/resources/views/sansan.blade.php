<?php $base_css_type = 'incentive'; ?>
@extends('layouts.plane')

@section('layout.head')
{!! Tag::script('/js/heightLine.js', ['type' => 'text/javascript']) !!}
@endsection

@section('layout.title', '魁！タイプ塾｜ポイントサイトならGMOポイ活')
@section('layout.description', 'ゲームで遊んで、クイズに答えて、記事の感想を投稿してポイントが貯めよう！コツコツ毎日ポイントをゲット♪貯めたポイントは現金やギフト券に交換することができます。')

@section('layout.content')
<div class="contents">

{{--
<ul class="breadcrumb forwidth">
    <li>{!! Tag::link(route('website.index'), 'トップページ') !!}</li>
    <li>＞{!! Tag::link(route('sp_programs.index'), '毎日ゲット') !!}</li>
    <li>＞魁！タイプ塾</li>
</ul>
--}}

<section id="type_juku">
    <h1>{!! Tag::image('/images/head_img.jpg', '魁！タイプ塾') !!}</h1>

    <section class="login">
        <!-- iFrame表示部分 ここから -->
        <iframe src="{!! $iframe_url !!}" frameborder="0" scrolling="no" width="970px" height="680px"></iframe>
        <!-- iFrame表示部分 ここまで -->
    </section>
    <div id="event">{!! Tag::image('/images/type_camp2.png', 'デイリーランキングボーナス', ['width' => '813', 'height' => '304']) !!}
    </div>
    <div id="information">
        <h2>「魁！！タイプ塾」について</h2>
        <ul>
            <li>・このコンテンツは、表示された画像の文字を判読して、その通りに入力したり、内容を種類ごとに分別することで<span class="red">ポイント(PP)</span>がもらえるサービスです。</li>
            <li>・このコンテンツでもらえる<span class="red">「PP(pinpon)」</span>は、<span class="red">100pp＝1ポイント</span>で換算し、自動的にポイントを配付いたします。<br>
            ポイントの配付はその日に貯めたPPに応じて、<span class="red">翌日</span>に行います。</li>
            <li>・100PP=1ポイントで換算した際の端数（1～99PP）は、翌日分に持ち越されます。</li>
            <li>・PPの獲得には基本的に制限はありません。</li>
            <li>・正誤判定中のポイントは判定後に加算されます。</li>
            <li>・詳しい操作方法については、ゲーム画面の「へルプ」をご覧ください。</li>
            <li>・「選択ワーク」「入力ワーク」の2種類のコンテンツがございますが、場合によってワークがなくなる（在庫切れ）場合がございます。その場合は申し訳ございませんが、再開するまでしばらくお待ちください。</li>
            <li>・利用条件に反する行為を行った場合は、ポイント付与の権利が失効いたします。</li>
            <li>・当コンテンツは予告なく変更・終了する場合がございます。あらかじめご了承ください。</li>
            <li>・このコンテンツには、世界最強の武人と噂される「塾長」が指導している「魁！！タイプ塾」の運営を助けるために、塾生がタイプ業に挑戦するというストーリーがあるのですが、実際の内容には全く影響がありませんので気になさらなくて結構です。</li>
            <li>・万が一、塾長の話し方に違和感を感じても、そこは極力スルーしてください。</li>
        </ul>
    </div>
</section>
</div>
@endsection
